<!--- This is the login page for the library management system. --->
<?php
session_start();
	include("connection.php");

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//Something was posted
		$username = $_POST['username'];
		$password = $_POST['password'];

		//Check if the username and password are valid
		if(!empty($username) && !empty($password)){
			$query = "select * from users where uhID = '$username' limit 1";
			//Checks if username exists in the database
			$result = mysqli_query($conn, $query);
			//Checks if the password matches the username
			if($result){
				if($result && mysqli_num_rows($result) > 0){
					$user_data = mysqli_fetch_assoc($result);
					if($user_data['password'] === $password){
						// redirects to home page if login is successful
						$_SESSION['user_id'] = $user_data['userID'];
						header("Location: pages/home/home.php");
						die;
					}
				}
				$error =  "Incorrect username or password!";
			}
			else{
				$error =  "Incorrect username or password!";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang = "en">

<head>
	<link rel = "stylesheet" href = "index.css">
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Login</title>
</head>

<body>

<h1>University Library</h1>

<div class = "container">
	<h2>Login</h2>
	<form method = "POST">
		<label for = "username">Username</label><br>
		<input type = "text" name = "username" id = "username" required><br><br>

		<label for = "password">Password</label><br>
		<input type = "password" name = "password" id = "password" required><br><br>

		<input type = "submit" value = "Login"><br><br>

		<a href = "reset.php">Forgot Password?</a>
		<a href = "register.php">Register</a>
	</form>
</div>
</body>
</html>