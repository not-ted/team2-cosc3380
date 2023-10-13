<?php
    include ("../../connection.php");

    $reportType = $_GET['reportType'];
    $dateFrom = $_GET['dateFrom'];
    $dateFrom = date('Y-m-d H:i:s', strtotime($dateFrom));
    $dateTo = $_GET['dateTo'];
    $dateTo = date('Y-m-d H:i:s', strtotime($dateTo));

    //Set query based on report type
    $includeItemCondition = '';
    if ($reportType == 'mostBorrowed')
    {
        $includeBooks = $_GET['includeBooks'];
        $includeMovies = $_GET['includeMovies'];
        $includeTech = $_GET['includeTech'];

        //Create query part
        if ($includeBooks == 'true') {
            $includeItemCondition .= "borrowed.itemType = 'book'";
        }
    
        if ($includeMovies == 'true') {
            if ($includeItemCondition != "") {
                $includeItemCondition .= " OR ";
            }
            $includeItemCondition .= "borrowed.itemType = 'movie'";
        }

        if ($includeTech == 'true') {
            if ($includeItemCondition != "") {
                $includeItemCondition .= " OR ";
            }
            $includeItemCondition .= "borrowed.itemType = 'tech'";
        }

        if ($includeItemCondition != "") {
            $includeItemCondition = "AND (" . $includeItemCondition .")";
        } else {
            $includeItemCondition = "AND borrowed.itemType = 'INVALID'";
        }

        $reportQuery = "SELECT * FROM borrowed
                        INNER JOIN users ON borrowed.userID = users.userID
                        WHERE (borrowed.checkoutDate >= '$dateFrom' AND borrowed.checkoutDate <= '$dateTo')
                        $includeItemCondition";
    }
    else if ($reportType == 'usersWithMostToLeastFines')
    {
        $includeStudents = $_GET['includeStudents'];
        $includeFaculty = $_GET['includeFaculty'];

        //Create query part
        if ($includeStudents == 'true') {
            $includeItemCondition .= "users.userType = 'student'";
        }

        if ($includeFaculty == 'true') {
            if ($includeItemCondition != "") {
                $includeItemCondition .= " OR ";
            }
            $includeItemCondition .= "users.userType = 'faculty'";
        }

        if ($includeItemCondition != "") {
            $includeItemCondition = "AND (" . $includeItemCondition .")";
        } else {
            $includeItemCondition = "AND userType = 'INVALID'";
        }

        $reportQuery = "SELECT 
                            f.fineID,
                            users.userID,
                            users.firstName,
                            users.lastName,
                            users.uhID, 
                            users.userType, 
                            COUNT(f.fineID) as numberOfFines
                        FROM 
                            fines f
                        INNER JOIN 
                            users ON f.userID = users.userID
                        INNER JOIN 
                            borrowed ON f.borrowID = borrowed.borrowID
                        WHERE (borrowed.checkoutDate >= '$dateFrom' AND borrowed.checkoutDate <= '$dateTo') $includeItemCondition
                        GROUP BY 
                            users.userID, users.uhID, users.userType
                        ORDER BY 
                            numberOfFines DESC;";
    }
    else // reportType = 'finesGreatestToLeast'
    {
        $includeLate = $_GET['includeLate'];
        $includeLost = $_GET['includeLost'];

        //Create query part
        if ($includeLate == 'true') {
            $includeItemCondition .= "fines.type = 'late'";
        }

        if ($includeLost == 'true') {
            if ($includeItemCondition != "") {
                $includeItemCondition .= " OR ";
            }
            $includeItemCondition .= "fines.type = 'lost'";
        }

        if ($includeItemCondition != "") {
            $includeItemCondition = "AND (" . $includeItemCondition .")";
        } else {
            $includeItemCondition = "AND fines.type = 'INVALID'";
        }
        $reportQuery = "SELECT * FROM `fines`
                        INNER JOIN users ON fines.userID = users.userID
                        INNER JOIN borrowed ON fines.borrowID = borrowed.borrowID
                        WHERE (borrowed.checkoutDate >= '$dateFrom' AND borrowed.checkoutDate <= '$dateTo')
                        $includeItemCondition
                        ORDER BY fineAmount DESC;";
    }

    //Run query
    //echo $reportQuery;
    $result = mysqli_query($conn, $reportQuery);
    
    // If no fines are in database
    if (mysqli_num_rows($result) == 0) 
    {
        echo "<div class = 'horContainer'>";
            echo " <p> No data availiable. </p>";
        echo "</div>";
    }
    else
    {
        if ($reportType == 'mostBorrowed')
        {
            echo " <p class = 'reportTitle'> Items Most To Least Borrowed </p> ";
            echo "<p class = 'reportTitle'> FROM " . $dateFrom . " TO " . $dateTo . "</p>";
        }
        else if ($reportType == 'usersWithMostToLeastFines')
        {
            echo " <p class = 'reportTitle'> Users with Most to Least Fines </p> ";
            echo "<p class = 'reportTitle'> FROM " . $dateFrom . " TO " . $dateTo . "</p>";
        }
        else // reportType = 'finesGreatestToLeast'
        {
            echo " <p class = 'reportTitle'> Fines Greatest to Least </p> ";
            echo "<p class = 'reportTitle'> FROM " . $dateFrom . " TO " . $dateTo . "</p>";
        }

        // Print out table    
        echo "<table>";
                echo "<tr>";
                    if ($reportType == 'mostBorrowed') {
                        echo "<th style = 'font-weight: bold;'> Borrow ID </th>";
                        echo "<th style = 'font-weight: bold;'> UH ID </th>";
                        echo "<th style = 'font-weight: bold;'> User Type </th>";
                        echo "<th style = 'font-weight: bold;'> Item Type </th>";
                        echo "<th style = 'font-weight: bold;'> Item Name </th>";
                    }
                    if ($reportType == 'usersWithMostToLeastFines') {
                        echo "<th style = 'font-weight: bold;'> Fine ID </th>";
                        echo "<th style = 'font-weight: bold;'> UH ID </th>";
                        echo "<th style = 'font-weight: bold;'> First Name </th>";
                        echo "<th style = 'font-weight: bold;'> Last Name </th>";
                        echo "<th style = 'font-weight: bold;'> User Type </th>";
                        echo "<th style = 'font-weight: bold;'> Number of Fines in Period </th>";
                    }
                    if ($reportType == 'finesGreatestToLeast') {
                        echo "<th style = 'font-weight: bold;'> Fine ID </th>";
                        echo "<th style = 'font-weight: bold;'> UH ID </th>";
                        echo "<th style = 'font-weight: bold;'> First Name </th>";
                        echo "<th style = 'font-weight: bold;'> Last Name </th>";
                        echo "<th style = 'font-weight: bold;'> User Type </th>";
                        echo "<th style = 'font-weight: bold;'> Fine Type </th>";
                        echo "<th style = 'font-weight: bold;'> Fine Amount </th>";
                        echo "<th style = 'font-weight: bold;'> Have Paid? </th>";
                    }
                echo "</tr>";
        while ($data = mysqli_fetch_assoc($result)) {
            if ($reportType == 'mostBorrowed')
            {
                //Get item data
                if ($data['itemType'] == "book") {
                    $itemQuery = "SELECT * 
                    FROM books
                    INNER JOIN bookCopy ON books.bookID = bookCopy.bookID
                    WHERE bookCopy.bookCopyID = {$data['itemCopyID']};";
                }
                if ($data['itemType'] == "movie") {
                    $itemQuery = "SELECT * 
                    FROM movies
                    INNER JOIN movieCopy ON movies.movieID = movieCopy.movieID
                    WHERE movieCopy.movieCopyID = {$data['itemCopyID']};";
                }
                if ($data['itemType'] == "tech") {
                    $itemQuery = "SELECT * 
                    FROM tech
                    INNER JOIN techCopy ON tech.techID = techCopy.techID
                    WHERE techCopy.techCopyID = {$data['itemCopyID']};";
                }
                $itemResult = mysqli_query($conn, $itemQuery);
                $itemData = mysqli_fetch_assoc($itemResult);
                
                //Print out info
                echo "<tr>";
                    echo "<th> {$data['borrowID']} </th>";
                    echo "<th> {$data['uhID']} </th>";
                    echo "<th>" . strtoupper($data['userType']) . "</th>";
                    echo "<th>" . strtoupper($data['itemType']) . "</th>";
                    if ($data['itemType'] == "book")
                    {
                        echo "<th> {$itemData['bookName'] }</th>";
                    }
                    if ($data['itemType'] == "movie")
                    {
                        echo "<th> {$itemData['movieName'] }</th>";
                    }
                    if ($data['itemType'] == "tech")
                    {
                        echo "<th> {$itemData['techName'] }</th>";
                    }
                echo "</tr>";
            }
            if ($reportType == 'usersWithMostToLeastFines')
            {
                //Print out info
                echo "<tr>";
                echo "<th> {$data['fineID']} </th>";
                echo "<th> {$data['uhID']} </th>";
                echo "<th> {$data['firstName']} </th>";
                echo "<th> {$data['lastName']} </th>";
                echo "<th>" . strtoupper($data['userType']) . "</th>";
                echo "<th> {$data['numberOfFines']} </th>";
                echo "</tr>";
            }
            if ($reportType == 'finesGreatestToLeast')
            {
                //Print out info
                echo "<tr>";
                    echo "<th> {$data['fineID']} </th>";
                    echo "<th> {$data['uhID']} </th>";
                    echo "<th> {$data['firstName']} </th>";
                    echo "<th> {$data['lastName']} </th>";
                    echo "<th>" . strtoupper($data['userType']) . "</th>";
                    echo "<th>" . strtoupper($data['type']) . "</th>";
                    echo "<th> {$data['fineAmount']} </th>";
                    echo "<th> {$data['havePaid']} </th>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    
?>
