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
    <link rel="stylesheet" href="home.css">
    <style>
        .button-container {
            display: flex;
        }
    </style>
</head>

<body>
    <!-- Display user information at the top right corner -->
    <div style="position: absolute; top: 10px; right: 10px;">
        Logged in as <?php echo $userData['uhID']; ?> : <?php echo $userData['userType']; ?>
    </div>

    <h1>Library Homepage</h1>
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

    // Fetch recently added books
    $recentlyAddedBooks = array();
    $sqlBooks = "SELECT * FROM books ORDER BY bookID DESC LIMIT 6";
    $resultBooks = $conn->query($sqlBooks);

    if ($resultBooks && $resultBooks->num_rows > 0) {
        $recentlyAddedBooks = $resultBooks->fetch_all(MYSQLI_ASSOC);
        echo '<div class="book-covers-container">';
        foreach ($recentlyAddedBooks as $book) {
            // Ensure the coverFilePath is set and not empty
            if (!empty($book['coverFilePath'])) {
                $coverPath = '../../' . $book['coverFilePath'];

                // Create a clickable link that leads to itemDetail.php with the bookID
                echo '<div class="cover-fade">
                <a href="../item detail/itemDetail.php?id=' . $book['bookID'] . '&type=book">
                    <img src="' . $coverPath . '" alt="Book Cover" class="book-cover">
                </a>
            </div>';
            
            }
        }
        echo '</div>';
    } else {
        echo "No recently added books.";
    }
    ?>


    <h2>Explore More Items</h2>

    <?php
    // Fetch random items from books, movies, and tech (6 items)
    $randomItems = array();
    $sqlRandom = "(
                        SELECT 'book' AS itemType, bookID AS itemID, bookName AS itemName, coverFilePath 
                        FROM books 
                        ORDER BY RAND() 
                        LIMIT 2
                    )
                    UNION ALL
                    (
                        SELECT 'movie' AS itemType, movieID AS itemID, movieName AS itemName, coverFilepath AS coverFilePath 
                        FROM movies 
                        ORDER BY RAND() 
                        LIMIT 2
                    )
                    UNION ALL
                    (
                        SELECT 'tech' AS itemType, techID AS itemID, techName AS itemName, coverFilePath 
                        FROM tech 
                        ORDER BY RAND() 
                        LIMIT 2
                    )
                    ORDER BY RAND() 
                    LIMIT 6;
    
                    ";

    $resultRandom = $conn->query($sqlRandom);
    if ($resultRandom) {
        $randomItems = $resultRandom->fetch_all(MYSQLI_ASSOC);
    }

    // Display random item covers
    if (!empty($randomItems)) {
        echo '<div class="random-items-container">';
        foreach ($randomItems as $item) {
            if (isset($item['coverFilePath'])) {
                $coverPath = '../../' . $item['coverFilePath'];
                $itemName = $item['itemName'];

                // Wrap the cover image in an anchor tag to make it clickable
                echo '<div class="item-cover-container cover-fade" style="display: inline-block; margin-right: 10px;">';
                echo '<a href="../item detail/itemDetail.php?id=' . $item['itemID'] . '&type=' . $item['itemType'] . '">
                            <img src="' . $coverPath . '" alt="Book Cover" class="book-cover">
                        </a>'; 
                echo '</a>';
                echo '</div>';
            }
        }
        echo '</div>';
    } else {
        echo "No random items available.";
    }

    ?>
</body>

</html>