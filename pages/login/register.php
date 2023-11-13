<?php
	session_start();
	include("../../connection.php");

	if(isset($_SESSION['message'])){
		$message = $_SESSION['message'];
		unset($_SESSION['message']); // unset the message after displaying it
	}
	else{
		$message = "";
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$fname = $_POST['Fname'];
		$lname = $_POST['Lname'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$confirmPassword = $_POST['confirmPassword'];
		$securityQuestion1 = $_POST['securityQuestion1'];
		$securityQuestion2 = $_POST['securityQuestion2'];
		$securityQuestion3 = $_POST['securityQuestion3'];
		$securityAnswer1 = $_POST['securityAnswer1'];
		$securityAnswer2 = $_POST['securityAnswer2'];
		$securityAnswer3 = $_POST['securityAnswer3'];

		try {
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$conn->set_charset("utf8mb4");
			// Insert user data into database
			$stmt = $conn->prepare("INSERT INTO users (firstName, lastName, uhID, email, password, securityQ1, securityQ2, securityQ3, securityA1, securityA2, securityA3, canBorrow, borrowLimit, userType) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 7, 'student')");
	
			$stmt->bind_param("sssssssssss", $fname, $lname, $username, $email, $password, $securityQuestion1, $securityQuestion2, $securityQuestion3, $securityAnswer1, $securityAnswer2, $securityAnswer3);
	
			if ($stmt->execute()) {
				$_SESSION['message'] = "Registration successful!";
				header("location: login.php");
			} 
		} catch (mysqli_sql_exception $e) {
			$_SESSION['message'] = $e->getMessage();
			header("Refresh:0");
		}
	} 
?>
<!DOCTYPE html>
<html lang = "en">

<head>
	<link rel = "stylesheet" href = "login.css">
	<link rel = "stylesheet" href = "../../main resources/main.css">
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Register</title>
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
                <li><a href="../checkout-return/checkout-return.php">Checkout & Returns</a></li>
            <?php } ?>
            <?php if(isset ($_SESSION['user_id'])) { ?>
			    <li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
            <?php } else { ?>
                <li style="float:right; margin-right:20px"><a class="Sign In" href="login.php">Sign In</a></li>
            <?php } ?>
		</ul>
	</div>

<div class = "container">

	<h2>Register</h2>

    <p class="error"><?php echo $message; ?></p>
	
	<p class = "error" id = "emptyError"></p>

	<form method = "POST">

		<label for = "Fname">First Name</label><br>
		<input type = "text" name = "Fname" id = "Fname" required><br><br>
		<span class = "error" id = "nameError"></span>

		<label for = "Lname">Last Name</label><br>
		<input type = "text" name = "Lname" id = "Lname" required><br><br>
		<span class = "error" id = "nameError"></span>

		<label for = "username">UH ID</label><br>
		<input type = "text" name = "username" id = "username" required><br><br>
		<span class = "error" id = "idError"></span>

		<label for = "email">Email</label><br>
		<input type = "email" name = "email" id = "email" required><br><br>	
		<span class = "error" id = "emailError"></span>

		<label for="securityQuestion1">Security Question 1</label><br>
		<select name="securityQuestion1" id="securityQuestion1" required>
			<option value="">--Select a security question--</option>
			<option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
			<option value="What is the name of your first pet?">What is the name of your first pet?</option>
			<option value="What is your favorite color?">What is your favorite color?</option>
		</select><br>
		<span class="error" id="securityQuestion1Error"></span><br>

		<label for="securityAnswer1">Answer</label><br>
		<input type="text" name="securityAnswer1" id="securityAnswer1" required><br>
		<span class="error" id="securityAnswer1Error"></span><br>

		<label for="securityQuestion2">Security Question 2</label><br>
		<select name="securityQuestion2" id="securityQuestion2" required>
			<option value="">--Select a security question--</option>
			<option value="What is your favorite food?">What is your favorite food?</option>
			<option value="What high school did you attend?">What high school did you attend?</option>
			<option value="What was the make of your first car?">What was the make of your first car?</option>
		</select><br>
		<span class="error" id="securityQuestion2Error"></span><br>

		<label for="securityAnswer2">Answer</label><br>
		<input type="text" name="securityAnswer2" id="securityAnswer2" required><br>
		<span class="error" id="securityAnswer2Error"></span><br>

		<label for="securityQuestion3">Security Question 3</label><br>
		<select name="securityQuestion3" id="securityQuestion3" required>
			<option value="">--Select a security question--</option>
			<option value="What city were you born in?">What city were you born in?</option>
			<option value="What was the first concert you attended?">What was the first concert you attended?</option>
			<option value="What year was your mother born?">What year was your mother born?</option>
		</select><br>
		<span class="error" id="securityQuestion3Error"></span><br>

		<label for="securityAnswer3">Answer</label><br>
		<input type="text" name="securityAnswer3" id="securityAnswer3" required><br>
		<span class="error" id="securityAnswer3Error"></span><br>

		<label for = "password">Password</label><br>
		<input type = "password" name = "password" id = "password" required><br><br>
		<span class = "error" id = "passwordError"></span>

		<label for = "confirmPassword">Confirm Password</label><br>
		<input type = "password" name = "confirmPassword" id = "confirmPassword" required><br><br>
		<span class = "error" id = "matchError"></span>

		<input type = "submit" value = "Register" onclick = 'validateSignup()'><br><br>

	</form>

	<a href = "login.php">Login</a>

</div>

<script src = "register.js"></script>

</body>