<?php
//Check if user is manager
session_start();

if ($_SESSION['user_type'] !== 'management') {
    header("Location: ../../index.php"); // Redirect to index.php
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Return</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="checkout-return.css">
    <link rel="stylesheet" href="../../main resources/main.css">
    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body>
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
                <li><a href="../hold-fine manager/holdFineManager.php">Holds & Fines</a></li>
                <li><a class="active" href="../checkout-return/checkout-return.php">Checkout & Returns</a></li>
            <?php } ?>
            <?php if(isset ($_SESSION['user_id'])) { ?>
			    <li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
            <?php } else { ?>
                <li style="float:right; margin-right:20px"><a class="Sign In" href="../login/login.php">Sign In</a></li>
            <?php } ?>
		</ul>
	</div>

        <div class = 'horContainer'>
            <div class = 'verContainer'>
                <label for="transactionType">Transaction Type:</label>
                <select id="transactionType" name="transactionType">
                    <option value="checkout" selected>Checkout</option>
                    <option value="return">Return</option>
                </select>
                <label for="userID">UH ID:</label>
                <input type="text" id="userID" name="userID" required>
                <label for="itemType">Item Type:</label>
                <select id="itemType" name="itemType">
                    <option value="book">Book</option>
                    <option value="movie">Movie</option>
                    <option value="tech">Tech</option>
                </select>
                <label for="itemCopyID">Item Copy ID:</label>
                <input type="number" id="itemCopyID" name="itemCopyID" required>
                <br>

                <!-- Result message  -->
                <div id="resultMessage"></div>

                 <!-- Check out button -->
                    <button id="checkoutButton" onclick="checkoutSubmit()">Check out</button>
                    
                    <!-- Return button -->
                    <button id="returnButton" onclick="returnSubmit()">Return</button>
            </div>
        </div>


  <!-- Import External Javsacript -->
  <script src="checkout-return.js"></script>
</body>
</html>