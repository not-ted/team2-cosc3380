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

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//Something was posted
		$username = $_POST['username'];
		$password = $_POST['password'];

		//Check if the username and password are valid
		if(!empty($username) && !empty($password)){
            try {
                $query = "SELECT * FROM users WHERE uhID = '$username' limit 1";
                //Checks if username exists in the database
                $result = mysqli_query($conn, $query);
                //Checks if the password matches the username
                if($result){
                    if($result && mysqli_num_rows($result) > 0){
                        $user_data = mysqli_fetch_assoc($result);
                        if($user_data['password'] === $password){
                            // redirects to home page if login is successful
                            $_SESSION['user_id'] = $user_data['userID'];
                            $_SESSION['user_type'] = $user_data['userType'];
                            $_SESSION['can_borrow'] = $user_data['canBorrow'];
                            header("Location: ../home/home.php");
                            die;
                        }
                    }
                    throw new Exception("Incorrect username or password!"); // Throw an exception if the username or password is incorrect
                }
                else{
                    throw new Exception("Incorrect username or password!"); // Throw an exception if the username or password is incorrect
                }
            } catch (Exception $e) {
                $error = $e->getMessage(); // Catch the exception and set the error message
            }
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
	<title>Login</title>
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
                <li style="float:right; margin-right:20px"><a class="Sign In" href="../login/login.php">Sign In</a></li>
            <?php } ?>
		</ul>
	</div>

<div class = "container">
	<h2>Login</h2>
	<?php if (!empty($error)) { ?>
            <p class="error"><?php echo $error; ?></p> <!-- Display the error message -->
    <?php } ?>
    <p class="error"><?php echo $message; ?></p>
	<form method = "POST">
		<label for = "username">Username</label><br>
		<input type = "text" name = "username" id = "username" required><br><br>

		<label for = "password">Password</label><br>
		<input type = "password" name = "password" id = "password" required><br><br>

		<input type = "submit" value = "Login"><br><br>

		<a href = "register.php">Register</a>
	</form>
</div>
</body>
</html>