<?php
//Check if user is manager
session_start();

if ($_SESSION['user_type'] !== 'management') {
    header("Location: ../../index.php"); // Redirect to index.php
    exit(); 
}
?>
<?php
    //include("lateFineRetrieve.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines-Holds Manager</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="holdFineManager.css">
    <link rel="stylesheet" href="../../main resources/main.css">
    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>


    <div class="header">
		<h1>University Library</h1>
	</div>
	<div class="navbar">
		<ul>
			<li><a href="../home/home.php">Home</a></li>
			<li><a href="../item search/itemSearch.php">Search</a></li>
            <?php if(isset ($_SESSION['user_id'])) { ?>
                <li><a href="../account dash/accountDash.php">My Account</a></li>
            <?php } ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'management'){ ?>
                <li><a href="../item add/itemAdd.php">Add Items</a></li>
                <li><a href="../user search/userSearch.php">User Search</a></li>
                <li><a href="../report/report.php">Reports</a></li>
                <li><a class="active" href="../hold-fine manager/holdFineManager.php">Holds & Fines</a></li>
                <li><a href="../checkout-return/checkout-return.php">Checkout & Returns</a></li>
            <?php } ?>
            <?php if(isset ($_SESSION['user_id'])) { ?>
			    <li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
            <?php } else { ?>
                <li style="float:right; margin-right:20px"><a class="Sign In" href="../login/login.php">Sign In</a></li>
            <?php } ?>
		</ul>
	</div>

<body onload = 'onload()'>

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