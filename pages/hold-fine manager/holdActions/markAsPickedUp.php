<?php
    include ("../../../connection.php");

    $holdID = $_GET['holdID'];

    // Set hold to requestStatus = 'pickedUp'
    $updateQuery = "UPDATE `holds` SET `requestStatus`= 'pickedUp' WHERE `holdID`= $holdID;";
    mysqli_query($conn, $updateQuery);

    // Select the updated hold
    $selectQuery = "SELECT * FROM `holds` WHERE `holdID` = $holdID;";
    $result = mysqli_query($conn, $selectQuery);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Insert into borrowed table
        $userID = $row['userID'];
        $itemType = $row['itemType'];
        $itemCopyID = $row['itemCopyID'];
        $checkoutDate = date('Y-m-d H:i:s'); // Current date and time
        $dueDate = date('Y-m-d H:i:s', strtotime('+7 days')); // 7 days from now

        $insertQuery = "INSERT INTO `borrowed` (`userID`, `itemType`, `itemCopyID`, `borrowStatus`, `checkoutDate`, `dueDate`) 
                        VALUES ($userID, '$itemType', $itemCopyID, 'checkedOut', '$checkoutDate', '$dueDate');";
        mysqli_query($conn, $insertQuery);

        // $row now contains the selected hold data
        // You can use $row['columnName'] to access specific columns
    } else {
        echo "Error: " . mysqli_error($conn);
    }
?>
