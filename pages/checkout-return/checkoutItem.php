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
        echo "Error: Invalid input. Please provide valid numeric values for UserID and ItemCopyID.";
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

    // Check if the item is available
    if ($itemData['available'] == 0) {
        http_response_code(400);
        echo "Error: This item is currently already checked out." ;
        exit;
    }
    // Update the item availability to 0 (checked out)
    $updateAvailabilityQuery = "UPDATE $tableName SET available = 0 WHERE $copyIDColumn = $itemCopyID";
    $updateAvailabilityQueryResult = mysqli_query($conn, $updateAvailabilityQuery);

    if (!$updateAvailabilityQueryResult) {
        http_response_code(400);
        echo "Error updating item availability: " . mysqli_error($conn);
        exit;
    }

    // Insert into borrowed table
    $insertBorrowedQuery = "INSERT INTO borrowed (userID, itemType, itemCopyID, borrowStatus, checkoutDate, dueDate) VALUES ('{$userData['userID']}', '$itemType', {$itemData[$copyIDColumn]}, 'checked out', NOW(), NOW() + INTERVAL 7 DAY)";
    $insertBorrowedQueryResult = mysqli_query($conn, $insertBorrowedQuery);

    if (!$insertBorrowedQueryResult) {
        http_response_code(400);
        echo "Error inserting into borrowed table: " . mysqli_error($conn);
        exit;
    } else {
        echo "User: {$userData['firstName']} {$userData['lastName']} ($userID) has checked out $itemType (ID: $itemCopyID) successfully.";
    }

?>
