

<!--- This is the password reset page for the library management system. --->
<?php
session_start();
include("connection.php");

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
    <link rel="stylesheet" href="index.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>

<body>
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

                <input type="submit" name="form1" value="Submit">
            </form>
        <?php } ?>
    </div>
</body>

</html>