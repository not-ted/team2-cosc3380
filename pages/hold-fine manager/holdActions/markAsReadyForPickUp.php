<?php
include("../../../connection.php");

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

$holdID = $_GET['holdID'];

// Set hold to requestStatus = 'readyForPickUp'
$paidFineQuery = "UPDATE `holds` SET `requestStatus` = 'readyForPickUp' WHERE `holdID` = $holdID;";
mysqli_query($conn, $paidFineQuery);

// Get the tuple where holdID = $holdID
$getHoldQuery = "SELECT * FROM `holds` WHERE `holdID` = $holdID";
$result = mysqli_query($conn, $getHoldQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $searchCopyQuery = null;
    $copyTable = null;

    if ($row['itemType'] === 'book') {
        // Search for the first available book copy
        $bookID = $row['itemID'];
        $searchCopyQuery = "SELECT * FROM `bookcopy` WHERE `bookID` = $bookID AND `available` = 1 LIMIT 1";
        $copyTable = 'bookcopy';
    } elseif ($row['itemType'] === 'movie') {
        // Search for the first available movie copy
        $movieID = $row['itemID'];
        $searchCopyQuery = "SELECT * FROM `moviecopy` WHERE `movieID` = $movieID AND `available` = 1 LIMIT 1";
        $copyTable = 'moviecopy';
    } elseif ($row['itemType'] === 'tech') {
        // Search for the first available tech copy
        $techID = $row['itemID'];
        $searchCopyQuery = "SELECT * FROM `techcopy` WHERE `techID` = $techID AND `available` = 1 LIMIT 1";
        $copyTable = 'techcopy';
    }

    if ($searchCopyQuery !== null) {
        $copyResult = mysqli_query($conn, $searchCopyQuery);

        if ($copyResult && mysqli_num_rows($copyResult) > 0) {
            $copyRow = mysqli_fetch_assoc($copyResult);
            // Now $copyRow contains the first available copy

            // Update itemCopyID attribute
            $itemCopyID = $copyRow[$row['itemType'] . 'CopyID'];
            $updateHoldQuery = "UPDATE `holds` SET `itemCopyID` = $itemCopyID WHERE `holdID` = $holdID";
            mysqli_query($conn, $updateHoldQuery);

            // Set 'available' attribute of copy to 0
            $copyID = $copyRow[$row['itemType'] . 'CopyID'];
            $setCopyUnavailableQuery = "UPDATE `$copyTable` SET `available` = 0 WHERE `$copyTable" . "ID` = $copyID";
            mysqli_query($conn, $setCopyUnavailableQuery);

            echo "itemCopyID updated to $itemCopyID";

        } else {
            echo "No available copy found.";
        }

        mysqli_free_result($copyResult);
    }
} else {
    echo "Invalid itemType.";
}

mysqli_free_result($result);
mysqli_close($conn);
?>
