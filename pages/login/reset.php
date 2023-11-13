

<!--- This is the password reset page for the library management system. --->
<?php
session_start();
include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form1'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($email)) {
        $query = "SELECT * FROM users WHERE uhID = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['email'] === $email) {
                // User identity verified, display security questions
                $question1 = $user_data['securityQ1'];
                $question2 = $user_data['securityQ2'];
                $question3 = $user_data['securityQ3'];
            } else {
                $error = "Incorrect username or email address";
            }
        } else {
            $error = "Incorrect username or email address";
        }
    } else {
        $error = "Please enter your username and email address";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form2'])) {
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];

    if ($user_data['securityA1'] === $answer1 && $user_data['securityA2'] === $answer2 && $user_data['securityA3'] === $answer3) {
        // User answers verified, update password
        $query = "UPDATE users SET password = '$password' WHERE uhID = '$username'";
        mysqli_query($conn, $query);
        $message = "Password updated successfully";
        
    } 
    else {
        $error = "Incorrect security answers";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../../main resources/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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

    <div class="container">
        <h2>Password Reset</h2>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <?php if (isset($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>

        <?php if (isset($question1)) { ?>
            <form method="POST" name="form2">
                <input type="hidden" name="username" value="<?php echo $username; ?>">

                <label for="question1"><?php echo $question1; ?></label><br>
                <input type="text" name="answer1" id="answer1" required><br><br>

                <label for="question2"><?php echo $question2; ?></label><br>
                <input type="text" name="answer2" id="answer2" required><br><br>

                <label for="question3"><?php echo $question3; ?></label><br>
                <input type="text" name="answer3" id="answer3" required><br><br>

                <label for="password">New Password</label><br>
                <input type="password" name="password" id="password" required><br><br>

                <input type="submit" value="Submit">
            </form>
        <?php } else { ?>
            <form method="POST" name="form1">
                <label for="username">Username</label><br>
                <input type="text" name="username" id="username" required><br><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" required><br><br>

                <input type="submit" name="form1" value="Submit"><br><br>
            </form>
        <?php } ?>

        <a href = "login.php">Login</a>
    </div>
</body>

</html>