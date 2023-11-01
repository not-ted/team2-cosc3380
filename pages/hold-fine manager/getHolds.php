<?php
    include ("../../connection.php");

    $viewPending = $_GET['viewPending'];
    $viewDenied = $_GET['viewDenied'];
    $viewPickedUp = $_GET['viewPickedUp'];
    $viewReadyForPickUp = $_GET['viewReadyForPickUp'];
    $sortBy = $_GET['holdsSortBy'];

    //View Condition
    $viewCondition = "";

    if ($viewPending == 'true') {
        $viewCondition .= "requestStatus = 'pending'";
    }

    if ($viewDenied == 'true') {
        if ($viewCondition != "") {
            $viewCondition .= " OR ";
        }
        $viewCondition .= "requestStatus = 'denied'";
    }

    if ($viewPickedUp == 'true') {
        if ($viewCondition != "") {
            $viewCondition .= " OR ";
        }
        $viewCondition .= "requestStatus = 'pickedUp'";
    }

    if ($viewReadyForPickUp == 'true') {
        if ($viewCondition != "") {
            $viewCondition .= " OR ";
        }
        $viewCondition .= "requestStatus = 'readyForPickup'";
    }

    if ($viewCondition != "") {
        $viewCondition = "WHERE " . $viewCondition;
    } else {
        $viewCondition = "WHERE requestStatus = 'INVALID'";
    }

    //Sort By Condition
    if ($sortBy == 'readyForPickup') {
        $orderByCondition = "ORDER BY 
                                CASE 
                                    WHEN requestStatus = 'readyForPickup' THEN 0
                                    ELSE 1
                                END, requestStatus;";
    }
    if ($sortBy == 'newestFirst') {
        $orderByCondition = "ORDER BY requestDate DESC;";
    }
    if ($sortBy == 'oldestFirst') {
        $orderByCondition = "ORDER BY requestDate ASC;";
    }

    //Create query from viewBy sortBy variables

    $holdsQuery = "SELECT * 
                    FROM holds
                    INNER JOIN users ON holds.userID = users.userID
                    $viewCondition
                    $orderByCondition";
    $result = mysqli_query($conn, $holdsQuery);
    
    // If no holds are in database
    if (mysqli_num_rows($result) == 0) 
    {
        echo "<div class = 'horContainer'>";
            echo " <p> No holds documented based on view options. </p>";
        echo "</div>";
    }
    else
    {
        // Print out list of holds    
        while ($data = mysqli_fetch_assoc($result)) 
        {
            
            echo "<div class = 'holdContainer'>";
                //Pending/Ready For Pickup/Denied/PickedUp Label
                echo "<div class = 'horContainer'>";
                    if ($data['requestStatus'] == 'pending')
                    {
                        echo "<p class = 'requestStatusLabel' style = 'color: #7296cc;'> PENDING </p>";
                    }
                    else if ($data['requestStatus'] == 'readyForPickUp')
                    {
                        echo "<div>";
                            echo "<p class = 'requestStatusLabel'style = 'color: #6eba82; text-align: center' > READY FOR PICKUP </p>";
                        echo "</div>";
                    }
                    else if ($data['requestStatus'] == 'pickedUp')
                    {
                        echo "<p class = 'requestStatusLabel'style = 'color: #d69a4b;' > PICKED UP </p>";
                    }
                    else
                    {
                        echo "<p class = 'requestStatusLabel'style = 'color: rgb(210, 70, 70);' > DENIED </p>";
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
                    WHERE bookcopy.bookCopyID = {$data['itemID']};";
                }
                if ($data['itemType'] == "movie") {
                    $itemQuery = "SELECT * 
                    FROM movies
                    INNER JOIN moviecopy ON movies.movieID = moviecopy.movieID
                    WHERE moviecopy.movieCopyID = {$data['itemID']};";
                }
                if ($data['itemType'] == "tech") {
                    $itemQuery = "SELECT * 
                    FROM tech
                    INNER JOIN techcopy ON tech.techID = techcopy.techID
                    WHERE techcopy.techCopyID = {$data['itemID']};";
                }

                $itemResult = mysqli_query($conn, $itemQuery);
                $itemData = mysqli_fetch_assoc($itemResult);

                //Get hold data
                $requestDateTime = strtotime($data['requestDate']); // Convert the date string to a timestamp
                $formattedDateTime = date('F j, Y \a\t g:i A', $requestDateTime); // Format the timestamp

                echo "<p> Request Date: <span class='data'>" . $formattedDateTime . "</span></p>";

                echo "<p> Request Status: <span class='data'>" . strtoupper($data['requestStatus']) . "</span></p>";

                echo "<hr>";
                
                //Print item data
                echo "<p> Item Type: <span class='data'>" .strtoupper($data['itemType']) . "</span></p>";
                if ($data['itemType'] == "book")
                {
                    echo "<p> Book Name: <span class='data'>" . $itemData['bookName'] . "</span></p>";
                    echo "<p> Book ID: <span class='data'>" . $itemData['bookCopyID'] . "</span></p>";
                }
                if ($data['itemType'] == "movie")
                {
                    echo "<p> Movie Name: <span class='data'>" . $itemData['movieName'] . "</span></p>";
                    echo "<p> Movie ID: <span class='data'>" . $itemData['movieCopyID'] . "</span></p>";
                }
                if ($data['itemType'] == "tech")
                {
                    echo "<p> Tech Name: <span class='data'>" . $itemData['techName'] . "</span></p>";
                    echo "<p> Tech ID: <span class='data'>" . $itemData['techCopyID'] . "</span></p>";
                }

                //Action Buttons
                echo "<div class = 'horContainer'>";
                    if ($data['requestStatus'] != 'pending') 
                    {
                        echo "<button style = 'background-color: #9ebbe6;' onclick = markAsPending({$data['holdID']})> Mark as <br> pending </button>";
                    }
                    if ($data['requestStatus'] != 'readyForPickUp') 
                    {
                        echo "<button style = 'background-color: #b8d6c0;' onclick = markAsReadyForPickUp({$data['holdID']})> Mark as <br> Ready for Pickup </button>";
                    }
                    if ($data['requestStatus'] != 'pickedUp') 
                    {
                        echo "<button style = 'background-color: #ffd9a8;' onclick = markAsPickedUp({$data['holdID']})> Mark as <br> Picked Up </button>";
                    }
                    if ($data['requestStatus'] != 'denied') 
                    {
                        echo "<button style = 'background-color: #e69ea2;' onclick = markAsDenied({$data['holdID']})> Mark as <br> Denied </button>";
                    }
                    
                echo "</div>";
            echo "</div>";
            
        }
    }

    
?>
