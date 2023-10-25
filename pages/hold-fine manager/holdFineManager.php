<?php
//Check if user is manager
session_start();

if ($_SESSION['user_id'] !== 'management') {
    header("Location: /index.php"); // Redirect to index.php
    exit(); 
}
?>
<?php
    include("lateFineRetrieve.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines-Holds Manager</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="holdFineManager.css">

    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body onload = 'onload()'>
    <!-- Header -->
    <div id = 'header'>
        <h1> Management Page for Fines and Holds </h1> 
    </div>

    <div class = 'horContainer' style = 'align-items: start;'>
        <!-- Fine Section, obtain data from getFines.php -->
        <div id = 'finesListWholeContainer'> 

            <p class = 'listName'> Fines </p>
            <!-- View options container -->
            <div class = 'horContainer' style = 'margin: 5px;'>
                <!-- Have Paid? View -->
                <input type="checkbox" id="viewUnpaid" checked  onchange="handleViewUnpaidChange(this)">
                <label for="viewUnpaid">View Unpaid</label>
                <input type="checkbox" id="viewPaid" onchange="handleViewPaidChange(this)">
                <label for="viewPaid">View Paid</label>
                <input type="checkbox" id="viewWaived" onchange="handleViewWaivedChange(this)">
                <label for="viewWaived">View Waived</label>
            </div>
            <div class = 'horContainer' style = 'margin: 5px;'>
                <select id="selectFinesSortBy">
                    <option value="newestFirst">Newest First</option>
                    <option value="oldestFirst">Oldest First</option>
                    <option value="fineAmountASC">Fine Amount Ascending</option>
                    <option value="fineAmountDESC">Fine Amount Descending</option>
                </select>
            </div>

            <!-- List of Fines container -->
            <div class = 'horContainer'>
                <div id = 'finesListContainer'> </div>
            </div>
        </div>

        <!-- Holds Section, obtain data from getHolds.php -->
        <div id = 'holdsListWholeContainer'> 
            <p class = 'listName'> Holds </p>
            <!-- View options container -->
            <div class = 'horContainer' style = 'margin: 15px; width: 90%;'>
                <input type="checkbox" id="viewReadyForPickup" checked onchange="handleViewReadyForPickupChange(this)">
                <label for="viewReadyForPickup">View Ready for Pickup</label>
                <input type="checkbox" id="viewPending"  checked onchange="handleViewPendingChange(this)">
                <label for="viewPending">View Pending</label>
                <input type="checkbox" id="viewDenied" onchange="handleViewDeniedChange(this)">
                <label for="viewDenied">View Denied</label>
                <input type="checkbox" id="viewPickedUp" onchange="handleViewPickedUpChange(this)">
                <label for="viewPickedUp">View Picked Up</label>
            </div>
            
            <div class = 'horContainer' style = 'margin: 15px;'>
                <select id="selectHoldsSortBy">
                    <option value="readyForPickup">Ready for Pickup</option>
                    <option value="newestFirst">Newest First</option>
                    <option value="oldestFirst">Oldest First</option>
                </select>
            </div>

            <!-- List of Holds container -->
            <div class = 'horContainer'>
                <div id = 'holdsListContainer'> </div>
            </div>
        </div>
    </div>

    <!-- Import External Javsacript -->
    <script src="holdFineManager.js"></script>
</body>
</html>