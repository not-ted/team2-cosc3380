<?php
    include ("../../connection.php");

    // Get data from the AJAX request
    $transactionType = $_GET['transactionType'];
    $userID = $_GET['userID'];
    $itemType = $_GET['itemType'];
    $itemCopyID = $_GET['itemCopyID'];

    // Validate input (you might want to add more validation)
    if (!is_numeric($userID) || !is_numeric($itemCopyID)) {
        http_response_code(400);
        echo "Invalid input. Please provide valid numeric values for UserID and ItemCopyID.";
        exit;
    }

    // Check if the user exists
    $userQuery = "SELECT * FROM users WHERE uhID = $userID";
    $userResult = mysqli_query($conn, $userQuery);

    if (!$userResult) {
        http_response_code(400);
        echo "Error checking user existence: " . mysqli_error($conn);
        exit;
    }

    // Fetch the user data
    $userData = mysqli_fetch_assoc($userResult);

    if (!$userData) {
        http_response_code(400);
        echo "Error: User with ID $userID not found.";
        exit;
    }

    // Depending on the itemType, search the corresponding table
    $tableName = "";
    $copyIDColumn = "";

    switch ($itemType) {
        case "book":
            $tableName = "bookcopy";
            $copyIDColumn = "bookCopyID";
            break;
        case "movie":
            $tableName = "moviecopy";
            $copyIDColumn = "movieCopyID";
            break;
        case "tech":
            $tableName = "techcopy";
            $copyIDColumn = "techCopyID";
            break;
        default:
        http_response_code(400);
            echo "Invalid item type.";
            exit;
    }

    // Search for the item in the corresponding table
    $itemQuery = "SELECT * FROM $tableName WHERE $copyIDColumn = $itemCopyID";
    $itemResult = mysqli_query($conn, $itemQuery);


    if ($itemResult->num_rows === 0) {
        http_response_code(400);
        echo "Error: No matching item found for $itemType with Copy ID $itemCopyID.";
        exit;
    }

    // Fetch the row data
    $itemData = $itemResult->fetch_assoc();

    // Check if the item is checked out
    if ($itemData['available'] === 1) {
        http_response_code(400);
        echo "Error: This item has already been returned.";
        exit;
    }
    // Update the item availability to 1 (returned)
    $updateAvailabilityQuery = "UPDATE $tableName SET available = 1 WHERE $copyIDColumn = $itemCopyID";
    $updateAvailabilityQueryResult = mysqli_query($conn, $updateAvailabilityQuery);

    if (!$updateAvailabilityQueryResult) {
        http_response_code(400);
        echo "Error updating item availability: " . mysqli_error($conn);
        exit;
    }

    // Update borrowed table
    $returnedDate = date('Y-m-d H:i:s');

    $updateBorrowQuery = "UPDATE borrowed SET returnedDate='$returnedDate', borrowStatus='returned' WHERE itemType='$itemType' AND itemCopyID='$itemCopyID'";
    $updateBorrowQueryResult = mysqli_query($conn, $updateBorrowQuery);

    if (!$updateBorrowQueryResult) {
        http_response_code(400);
        echo "Error updating borrowed table: " . mysqli_error($conn);
        exit;
    } else {
        echo "User: {$userData['firstName']} {$userData['lastName']} ($userID) has returned $itemType (ID: $itemCopyID) successfully.";
    }

?>
