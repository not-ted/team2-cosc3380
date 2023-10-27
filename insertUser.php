<?php
	session_start();
	include("connection.php");

		$fname = $_GET['Fname'];
		$lname = $_GET['Lname'];
		$username = $_GET['username'];
		$email = $_GET['email'];
		$password = $_GET['password'];
		$confirmPassword = $_GET['confirmPassword'];
		$securityQuestion1 = $_GET['securityQuestion1'];
		$securityQuestion2 = $_GET['securityQuestion2'];
		$securityQuestion3 = $_GET['securityQuestion3'];
		$securityAnswer1 = $_GET['securityAnswer1'];
		$securityAnswer2 = $_GET['securityAnswer2'];
		$securityAnswer3 = $_GET['securityAnswer3'];

		// Check if username or email already exists

		$query = "SELECT * FROM users WHERE uhID = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $query); 
		if($result && mysqli_num_rows($result) > 0) {
            $error = "Username or email already in use";
        } else {
            // Insert user data into database
            $query2 = "INSERT INTO users (firstName, lastName, uhID, email, password, securityQ1, securityQ2, securityQ3, securityA1, securityA2, securityA3, canBorrow, borrowLimit) 
          VALUES ('$fname', '$lname', '$username', '$email', '$password', '$securityQuestion1', '$securityQuestion2', '$securityQuestion3', '$securityAnswer1', '$securityAnswer2', '$securityAnswer3', 1, 7)";

            $result2 = mysqli_query($conn, $query2);
        
            if ($result2) {
                $message = "User created successfully";
            } else {
                $error = "Error: " . $query2 . "<br>" . mysqli_error($conn);
            }
        }
        
?>