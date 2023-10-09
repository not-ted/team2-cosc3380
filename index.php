<!--- This is the login page for the library management system. --->
<?php require 'connection.php'; ?>
<?php
session_start();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // TODO: Check if the username and password are valid

    if ($username == 'admin' && $password == 'password') {
        $_SESSION['loggedin'] = true;
        header('Location: index.php');
        exit;
    } 
	else {
        $error = 'Invalid username or password';
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

<h1>University Library</h1>
<body>
	<div><h2>Login</h2></div>
	<form action = 'connection.php' method = "POST">
		<div class = "input">
			<label for = "username">Username</label>
			<input type = "text" name = "username" id = "username" required>
		</div>
		<div class = "input">
			<label for = "password">Password</label>
			<input type = "password" name = "password" id = "password" required>
		</div>
		<div class = "input">
			<input type = "submit" value = "Login">
		</div>
</body>
</html>