<?php
session_start();
include("detailFunctions.php");

if(!isset($_SESSION['user_id'])){
	header("Location: ../../index.php");
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

	<div class="logout-container">
		<button class="back-button" onclick="location.href='../../userSearch.php'">Back to Search</button>
		<button class="home-button" onclick="location.href='../../home.php'">Home</button>
        <button class="logout-button" onclick="location.href='../../logout.php'">Logout</button>
    </div>

	<h1><?php echo htmlspecialchars($user_info['firstName']) . " " . htmlspecialchars($user_info['lastName']); ?></h1>
    <ul class="user-profile">
        <li class="user-profile-item">User ID: <?php echo htmlspecialchars($user_info['uhID']); ?></li>
        <li class="user-profile-item">Email: <?php echo htmlspecialchars($user_info['email']); ?></li>
        <li class="user-profile-item">User Type: <?php echo htmlspecialchars($user_info['userType']); ?></li>
        <li class="user-profile-item">Can Borrow: <?php echo ($user_info['canBorrow'] == 1) ? 'Yes' : 'No'; ?></li>
        <li class="user-profile-item">Borrow Limit (days): <?php echo htmlspecialchars($user_info['borrowLimit']); ?></li>
    </ul>

	<div class="edit-container">
  		<button class="edit-button" onclick="<?php changeBorrow($userID, $conn)?>">Change Borrow Privilege</button>		
		<button class="edit-button" onclick="clearFines()">Clear Fine</button>	
	</div>

	<script>
		function clearFines() {
  			document.getElementById("clearFine").style.display = "block";
		}
	</script>

	<div class =fines-container" id="clearFine" style="display:none">
		<h2>Clear Fine</h2>
		<form method="POST">
			<label for="fineID">Fine ID:</label>
			<input type="text" id="fineID" name="fineID"><br><br>
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
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php getHistory($userId, $conn); ?>
		</tbody>

</body>

</html>
