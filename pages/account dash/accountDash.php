<?php
// Start the user's session or resume if it exists
session_start();

// Check if the user is authenticated (custom logic required)
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not authenticated
    header('Location: ../../index.php'); // Redirect to the login page
    exit;
}

// Include the database connection file
include("../../connection.php");

// Retrieve the user's profile information
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE userID = $userId";
$result = $conn->query($sql);
$userData = $result->fetch_assoc();

// Initialize variables for password change
$newPassword = $confirmPassword = '';
$passwordChangeError = '';

// Handle password change if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newPassword'])) {
    // Sanitize and process the password change
    $newPassword = $conn->real_escape_string($_POST['newPassword']);
    $confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);

    // Check if new password matches the confirmation password
    if ($newPassword === $confirmPassword) {
        // Update the user's password in the database (without hashing)
        $updateSql = "UPDATE users SET password = '$newPassword' WHERE userID = $userId";
        if ($conn->query($updateSql)) {
            // Password updated successfully, you can also add a success message
            header('Location: ../../index.php');
            exit;
        } else {
            $passwordChangeError = "Error updating password: " . $conn->error;
        }
    } else {
        $passwordChangeError = "Password did not match, Please enter your new password again and confirm it.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account Dashboard</title>
    <!-- Import your CSS styles here -->
    <link rel="stylesheet" href="accountDash.css">

</head>

<body>
    <h1>Welcome, <?php echo htmlspecialchars($userData['firstName']); ?></h1>

    <h2>Your Profile Information:</h2>
    <ul>
        <li>User ID: <?php echo htmlspecialchars($userData['uhID']); ?></li>
        <li>Email: <?php echo htmlspecialchars($userData['email']); ?></li>
        <li>User Type: <?php echo htmlspecialchars($userData['userType']); ?></li>
        <li>Can Borrow: <?php echo htmlspecialchars($userData['canBorrow']); ?></li>
        <li>Borrow Limit (days): <?php echo htmlspecialchars($userData['borrowLimit']); ?></li>
        <!-- Add more profile information here -->
    </ul>

    <!-- ... (previous HTML code) ... -->

    <h2>Change Password:</h2>
    <form method="post">
        <label for="newPassword">New Password:</label>
        <input type="password" name="newPassword" id="newPassword" required>

        <label for="confirmPassword">Confirm New Password:</label>
        <input type="password" name="confirmPassword" id="confirmPassword" required>

        <input type="submit" value="Change Password">
    </form>

    <!-- Display password change error -->
    <?php if (!empty($passwordChangeError)) : ?>
        <div class="error"><?php echo $passwordChangeError; ?></div>
    <?php endif; ?>

    <div class="logout-container">
        <button class="logout-button" onclick="location.href='logout.php'">Logout</button>
    </div>


    <!-- ... (rest of your HTML code) ... -->

</body>

</html>