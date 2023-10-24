<?php
// Start the user's session or resume if it exists
session_start();

// Check if the user is authenticated, redirect to the login page if not
if (!isset($_SESSION['user_id'])) {
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

// Define buttons for regular users
$Buttons = [
    '<form action="../item search/itemSearch.php" method="GET"><button type="submit">Item Search</button></form>',
    '<form action="../account dash/accountDash.php" method="GET"><button type="submit">Your Dashboard</button></form>'
];

// Define buttons for management users
$managementButtons = [
    '<form action="../item search/itemSearch.php" method="GET"><button type="submit">Item Search</button></form>',
    '<form action="../item add/itemAdd.php" method="GET"><button type="submit">Item Add</button></form>',
    '<form action="../user search/userSearch.php" method="GET"><button type="submit">User Search</button></form>',
    '<form action="../account dash/accountDash.php" method="GET"><button type="submit">Your Dashboard</button></form>',
    '<form action="../report/report.php" method="GET"><button type="submit">Generate Reports</button></form>',
    '<form action="../hold-fine manager/holdFineManager.php" method="GET"><button type="submit">Management Holds & Fines</button></form>'
];

// Determine which buttons to display based on the user's userType
if ($userData['userType'] === 'management') {
    $buttonsToDisplay = $managementButtons;
} else {
    $buttonsToDisplay = $Buttons;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Library Homepage</title>
    <!-- Link to an external CSS stylesheet -->
    <link rel="stylesheet" href="home.css">
    <style>
        .button-container {
            display: flex;
            /* Arrange buttons side by side */
        }
    </style>
</head>

<body>
    <!-- Display user information at the top right corner -->
    <div style="position: absolute; top: 10px; right: 10px;">
        Logged in as <?php echo $userData['uhID']; ?> : <?php echo $userData['userType']; ?>
    </div>

    <h1>Library Project</h1>
    <div class="button-container">
        <?php
        // Display the buttons based on userType
        foreach ($buttonsToDisplay as $button) {
            echo $button;
        }
        ?>
    </div>

    <!-- Display a welcome message for the user -->
    <?php
    $welcomeMessage = "Our most recently added books:";
    echo "<p>$welcomeMessage</p>";
    ?>

    <?php
    // Include the connection to the database
    include("../../connection.php");

    // Define the base URL of your web server
    $baseURL = "http://localhost:3000/team2-cosc3380";

    // Query the database to retrieve book cover file paths
    $sql = "SELECT coverFilePath FROM books";
    $result = $conn->query($sql);

    // Create a container for book covers
    if ($result) {
        echo '<div class="book-covers-container">';
        // Loop through the results and display book covers
        while ($row = $result->fetch_assoc()) {
            $coverPath = $row['coverFilePath']; // Relative file path
            $imageURL = $baseURL . $coverPath; // Construct the absolute image URL

            // Display book covers with the book-cover class
            echo '<img src="' . $imageURL . '" alt="Book Cover" class="book-cover">';
        }
        echo '</div>'; // Close the book-covers-container div
    } else {
        echo "Error: " . $conn->error;
    }
    ?>
</body>

</html>
