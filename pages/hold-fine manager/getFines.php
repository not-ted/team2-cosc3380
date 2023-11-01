<?php
    include ("../../connection.php");

    $viewUnpaid = $_GET['viewUnpaid'];
    $viewPaid = $_GET['viewPaid'];
    $viewWaived = $_GET['viewWaived'];
    $sortBy = $_GET['finesSortBy'];

    //View Condition
    $viewCondition = "";

    if ($viewUnpaid == 'true') {
        $viewCondition .= "havePaid = 'No'";
    }

    if ($viewPaid == 'true') {
        if ($viewCondition != "") {
            $viewCondition .= " OR ";
        }
        $viewCondition .= "havePaid = 'Yes'";
    }

    if ($viewWaived == 'true') {
        if ($viewCondition != "") {
            $viewCondition .= " OR ";
        }
        $viewCondition .= "havePaid = 'Waived'";
    }

    if ($viewCondition != "") {
        $viewCondition = "WHERE " . $viewCondition;
    } else {
        $viewCondition = "WHERE havePaid = 'INVALID'";
    }

    //Sort By Condition
    if ($sortBy == 'newestFirst') {
        $orderByCondition = "ORDER BY dueDate DESC;";
    }
    if ($sortBy == 'oldestFirst') {
        $orderByCondition = "ORDER BY dueDate ASC;";
    }
    if ($sortBy == 'fineAmountASC') {
        $orderByCondition = "ORDER BY fineAmount ASC;";
    }
    if ($sortBy == 'fineAmountDESC') {
        $orderByCondition = "ORDER BY dueDate DESC;";
    }

    //Create query from viewBy sortBy variables

    $finesQuery = "SELECT * 
                    FROM fines
                    INNER JOIN users ON fines.userID = users.userID
                    INNER JOIN borrowed ON fines.borrowID = borrowed.borrowID
                    $viewCondition
                    $orderByCondition";
    $result = mysqli_query($conn, $finesQuery);
    
    // If no fines are in database
    if (mysqli_num_rows($result) == 0) 
    {
        echo "<div class = 'horContainer'>";
            echo " <p> No fines documented based on view options. </p>";
        echo "</div>";
    }
    else
    {
        // Print out list of fines    
        while ($data = mysqli_fetch_assoc($result)) 
        {
            
            echo "<div class = 'fineContainer'>";
                //Paid/Unpaid Label
                echo "<div class = 'horContainer'>";
                    if ($data['havePaid'] == 'No')
                    {
                        echo "<p class = 'paidUnpaidLabel' style = 'color: rgb(210, 70, 70);'> UNPAID </p>";
                    }
                    else if ($data['havePaid'] == 'Yes')
                    {
                        echo "<p class = 'paidUnpaidLabel'style = 'color: rgb(85, 162, 78);' > PAID </p>";
                    }
                    else
                    {
                        echo "<p class = 'paidUnpaidLabel'style = 'color: #b386cf;' > Waived </p>";
                    }
                echo "</div>";
                
                //Get user data
                echo "<p> User UHID: <span class='data'>" . $data['uhID'] . "</span></p>";
                echo "<p> User's Last Name: <span class='data'>" . $data['lastName'] . "</span></p>";
                echo "<p> User's First Name: <span class='data'>" . $data['firstName'] . "</span></p>";
                echo "<hr>";

                //Get item data
                if ($data['itemType'] == "book") {
                    $itemQuery = "SELECT * 
                    FROM books
                    INNER JOIN bookcopy ON books.bookID = bookcopy.bookID
                    WHERE bookcopy.bookCopyID = {$data['itemCopyID']};";
                }
                if ($data['itemType'] == "movie") {
                    $itemQuery = "SELECT * 
                    FROM movies
                    INNER JOIN moviecopy ON movies.movieID = moviecopy.movieID
                    WHERE moviecopy.movieCopyID = {$data['itemCopyID']};";
                }
                if ($data['itemType'] == "tech") {
                    $itemQuery = "SELECT * 
                    FROM tech
                    INNER JOIN techcopy ON tech.techID = techcopy.techID
                    WHERE techcopy.techCopyID = {$data['itemCopyID']};";
                }
                

                $itemResult = mysqli_query($conn, $itemQuery);
                $itemData = mysqli_fetch_assoc($itemResult);
                //Get fine data
                echo "<p> Fine Type: <span class='data'>" . strtoupper($data['type']) . "</span></p>";
                echo "<p> Checked out: <span class='data'>" . $data['checkoutDate'] . "</span></p>";
                echo "<p> Due date: <span class='data'>" . $data['dueDate'] . "</span></p>";
                if ($data['returnedDate']) 
                {
                    echo "<p> Returned date: <span class='data'>" . $data['returnedDate'] . "</span></p>";
                }
                else
                {
                    echo "<p> Returned date: <span class='data'> Have not returned </span></p>";
                }
                if ($data['type'] == 'late') //If fine is late, charge $5 late fee 
                {
                    echo "<p> Fine Amount: <span class='data'> $5.00 </span></p>";
                }
                else //If fine is lost, charge value of item 
                {
                    echo "<p> Fine Amount: <span class='data'> $" . $itemData['value'] . "</span></p>";
                }
                
                
                echo "<p> Have Paid?: <span class='data'>" . $data['havePaid'] . "</span></p>";
                echo "<hr>";
                //Print item data
                echo "<p> Item Type: <span class='data'>" .strtoupper($data['itemType']) . "</span></p>";
                if ($data['itemType'] == "book")
                {
                    echo "<p> Book Name: <span class='data'>" . $itemData['bookName'] . "</span></p>";
                    echo "<p> Book Copy ID: <span class='data'>" . $itemData['bookCopyID'] . "</span></p>";
                    echo "<p> Book Cover Type: <span class='data'>" . $itemData['coverType'] . "</span></p>";
                }
                if ($data['itemType'] == "movie")
                {
                    echo "<p> Movie Name: <span class='data'>" . $itemData['movieName'] . "</span></p>";
                    echo "<p> Movie Copy ID: <span class='data'>" . $itemData['movieCopyID'] . "</span></p>";
                }
                if ($data['itemType'] == "tech")
                {
                    echo "<p> Tech Name: <span class='data'>" . $itemData['techName'] . "</span></p>";
                    echo "<p> Tech ID: <span class='data'>" . $itemData['techCopyID'] . "</span></p>";
                }

                

                //Action Buttons
                echo "<div class = 'horContainer'>";
                    if ($data['havePaid'] == 'No') 
                    {
                        echo "<button style = 'background-color: #b8d6c0;' onclick = markFineAsPaid({$data['fineID']})> Mark as paid </button>";
                    }
                    else
                    {
                        echo "<button style = 'background-color: #e6caca;' onclick = markFineAsUnpaid({$data['fineID']})> Mark as unpaid </button>";
                    }
                    
                    if ($data['type'] == 'late') 
                    {
                        echo "<button style = 'background-color: #f2db94;' onclick = markAsLostFine({$data['fineID']})> Mark as lost fine </button>";
                    }
                    else
                    {
                        echo "<button style = 'background-color: #e6caca;' onclick = markAsLateFine({$data['fineID']})> Mark as late fine </button>";
                    }
                    if ($data['havePaid'] == 'Waived') 
                    {
                        echo "<button style = 'background-color: #e6caca;' onclick = markFineAsUnpaid({$data['fineID']})> Unwaive Fine </button>";
                    }
                    if ($data['havePaid'] == 'No') 
                    {
                        echo "<button style = 'background-color: #dfcef0;' onclick = waiveFine({$data['fineID']})> Waive Fine </button>";
                    }
                    
                echo "</div>";
            echo "</div>";
            
        }
    }

    
?>
