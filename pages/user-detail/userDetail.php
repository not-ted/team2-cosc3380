<?php
session_start();
include("detailFunctions.php");

if(!isset($_SESSION['user_id'])){
	header("Location: ../../index.php");
}

if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // unset the message after displaying it
}
else{
    $message = "";
}

// check if a user ID has been passed in the URL
if (isset($_GET["id"])) {
  // get the user ID from the URL
  $userId = $_GET["id"];

  // query the database for the user with the specified ID
  $query = "SELECT * FROM users WHERE userID = $userId LIMIT 1";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $user_info = mysqli_fetch_assoc($result);
  } 
  else {
    echo "User not found";
  }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payFine'])){
	$fineID = $_POST['fineID'];
	$amount = $_POST['amount'];
	$query1 = "SELECT * FROM fines WHERE fineID = '$fineID' AND userID = '$userId' LIMIT 1";
	$result1 = mysqli_query($conn, $query1);
	if (mysqli_num_rows($result1) > 0) {
		$row = mysqli_fetch_assoc($result1);
		if($row['fineAmount'] == $amount){
			$query2 = "UPDATE fines SET havePaid = 'Yes' WHERE fineID = '$fineID' AND userID = '$userId'";
			$conn->query($query2);
			$_SESSION['message'] = "Fine paid!";
		}
		else{
			$_SESSION['message'] = "Incorrect amount";
		}
		header("Refresh:0");
	}
	else{
		$_SESSION['message'] = "Fine not found";
	}
	header("Refresh:0");
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clearFine'])){
	$fineID = $_POST['fineID'];
	$query1 = "SELECT * FROM fines WHERE fineID = '$fineID' AND userID = '$userId' LIMIT 1";
	$result1 = mysqli_query($conn, $query1);
	if (mysqli_num_rows($result1) > 0) {
		if($row['havePaid'] == 'Yes'){
			$_SESSION['message'] = "Fine already paid";
		}
		else if($row['havePaid'] == 'Waived'){
			$_SESSION['message'] = "Fine already waived";
		}
		else{
			$query2 = "UPDATE fines SET havePaid = 'Waived' WHERE fineID = '$fineID' AND userID = '$userId'";
			$conn->query($query2);
			$_SESSION['message'] = "Fine waived!";
		}
		header("Refresh:0");
	}
	else{
		$_SESSION['message'] = "Fine not found";
	}
	header("Refresh:0");
}

if($_SERVER['REQUEST_METHOD'] =='POST' && isset($_POST['changePrivilege'])){
	$query = "SELECT * FROM users WHERE userID = '$userId' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		if($row['canBorrow'] == 1){
			$query2 = "UPDATE users SET canBorrow = 0 WHERE userID = '$userId'";
			$conn->query($query2);
			$_SESSION['message'] = "Borrow privilege revoked";
		}
		else if($_SESSION['hasFines'] == false){
			$query2 = "UPDATE users SET canBorrow = 1 WHERE userID = '$userId'";
			$conn->query($query2);
			$_SESSION['message'] = "Borrow privilege restored";
		}
		else{
			$_SESSION['message'] = "User has unpaid fines";
		}
		header("Refresh:0");
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" href="userDetail.css">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Detail</title>
</head>

<body>

	<div class="navbar">
		<ul>
			<li><a href="../home/home.php">Home</a></li>
			<li><a href="../account dash/accountDash.php">Dashboard</a></li>
			<li><a href="../item search/itemSearch.php">Search</a></li>
			<li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
		</ul>
	</div>

	<div class="user-detail">
		<h1><?php echo htmlspecialchars($user_info['firstName']) . " " . htmlspecialchars($user_info['lastName']); ?></h1>
		<ul class="user-profile">
			<li class="user-profile-item">User ID: <?php echo htmlspecialchars($user_info['uhID']); ?></li>
			<li class="user-profile-item">Email: <?php echo htmlspecialchars($user_info['email']); ?></li>
			<li class="user-profile-item">User Type: <?php echo htmlspecialchars($user_info['userType']); ?></li>
			<li class="user-profile-item">Can Borrow: <?php echo ($user_info['canBorrow'] == 1) ? 'Yes' : 'No'; ?></li>
			<li class="user-profile-item">Borrow Limit (days): <?php echo htmlspecialchars($user_info['borrowLimit']); ?></li>
		</ul>
	</div>

	<script>
		function payFines() {
  			document.getElementById("payFine").style.display = "block";
		}
		function clearFines() {
  			document.getElementById("clearFine").style.display = "block";
		}
	</script>

	<div class="edit-container">
  		<form method="POST" name = "changePrivilege">
			<input type="submit" name="changePrivilege" value="Change Borrow Privilege">
		</form>
		<button class="edit-button" onclick="payFines()">Pay Fine</button>
		<button class="edit-button" onclick="clearFines()">Clear Fine</button>
	</div>

	<?php if(isset($message)) { ?>
		<p class="message"><?php echo $message; ?></p>	
	<?php } ?>

	<div class ="fines-container" id="payFine" style="display:none">
		<h2>Pay Fine: </h2>
		<form method="POST" name = "payFine" onsubmit="return confirm('Are you sure?')">
			<label for="fineID">Fine ID:</label>
			<input type="text" id="fineID" name="fineID">
			<label for ="amount">Amount:</label>
			<input type="number" id="amount" name="amount"><br><br>
			<input type="submit" value="Submit">
		</form>
	</div>

	<div class ="fines-container" id="clearFine" style="display:none">
		<h2>Clear Fine</h2>
		<h3>Input ID of fine to be cleared</h3>
		<form method="POST" name = "clearFine" onsubmit="return confirm('Are you sure you want to waive this fine?\n Fine status will be set to WAIVED.')">
			<label for="fineID">Fine ID:</label>
			<input type="text" id="fineID" name="fineID">
			<input type="submit" value="Submit">
		</form>
	</div>

	<div id="fines" class="fines-container">
		<h2>Fines</h2>
		<table class="generic-table">
			<thead>
				<tr>
					<th>Fine ID</th>
					<th>Fine Amount</th>
					<th>Reason</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php getFines($userId, $conn); ?>
			</tbody>
		</table>
  	</div>


	<div class = "holds-borrow-fines">
		<h2>Currently Borrowed</h2>
		<table class="generic-table">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>Item Type</th>
					<th>Checkout Date</th>
					<th>Due Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php getCheckouts($userId, $conn); ?>
			</tbody>
		</table>

		<h2>Current Hold Requests</h2>
		<table class="generic-table">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>Item Type</th>
					<th>Request Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php getHolds($userId, $conn); ?>
			</tbody>
		</table>

		<h2>Checkout History</h2>	
		<table class="generic-table">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>Item Type</th>
					<th>Checkout Date</th>
					<th>Return Date</th>
				</tr>
			</thead>
			<tbody>
				<?php getHistory($userId, $conn); ?>
			</tbody>
		</table>
	</div>
	

</body>

</html>
