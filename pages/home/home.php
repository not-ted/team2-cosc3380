<?php
// Start the user's session or resume if it exists
session_start();

// Include the database connection file
include("../../connection.php");

// Check if the user is authenticated, retrieve user info
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE userID = $userId";
    $result = $conn->query($sql);
    $userData = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Library Homepage</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="../../main resources/main.css">
    <style>
        .button-container {
            display: flex;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>University Library</h1>
    </div>
    <div class="navbar">
        <ul>
            <li><a class="active" href="../home/home.php">Home</a></li>
            <li><a href="../item search/itemSearch.php">Search</a></li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li><a href="../account dash/accountDash.php">My Account</a></li>
            <?php } ?>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'management') { ?>
                <li><a href="../item add/itemAdd.php">Add Items</a></li>
                <li><a href="../user search/userSearch.php">User Search</a></li>
                <li><a href="../report/report.php">Reports</a></li>
                <li><a href="../hold-fine manager/holdFineManager.php">Holds & Fines</a></li>
                <li><a href="../checkout-return/checkout-return.php">Checkout & Returns</a></li>
            <?php } ?>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
            <?php } else { ?>
                <li style="float:right; margin-right:20px"><a class="Sign In" href="../login/login.php">Sign In</a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Display a welcome message for the user -->
    <?php
    $welcomeMessage = "Recently added books:";
    echo "<h2>$welcomeMessage</h2>";
    ?>

    <?php

    // Fetch recently added books
    $recentlyAddedBooks = array();
    $sqlBooks = "SELECT * 
                FROM books 
                WHERE deleted = 0 
                ORDER BY bookID DESC LIMIT 6";
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

    echo '<h2>Recently added movies:</h2>';

    // Fetch recently added movies
    $recentlyAddedMovies = array();
    $sqlMovies = "SELECT * FROM movies WHERE deleted = 0 ORDER BY movieID DESC LIMIT 6";
    $resultMovies = $conn->query($sqlMovies);

    if ($resultMovies && $resultMovies->num_rows > 0) {
        $recentlyAddedMovies = $resultMovies->fetch_all(MYSQLI_ASSOC);
        echo '<div class="movie-covers-container">';
        foreach ($recentlyAddedMovies as $movie) {
            // Ensure the coverFilePath is set and not empty
            if (!empty($movie['coverFilePath'])) {
                $coverPath = '../../' . $movie['coverFilePath'];

                // Create a clickable link that leads to itemDetail.php with the movieID
                echo '<div class="cover-fade">
                <a href="../item detail/itemDetail.php?id=' . $movie['movieID'] . '&type=movie">
                    <img src="' . $coverPath . '" alt="Book Cover" class="book-cover">
                </a>
            </div>';
            }
        }
        echo '</div>';
    } else {
        echo "No recently added movies.";
    }
    ?>

    <h2>Explore More Items</h2>

    <?php
    // Fetch random items from books, movies, and tech (6 items)
    $randomItems = array();
    $sqlRandom = "(
                        SELECT 'book' AS itemType, bookID AS itemID, bookName AS itemName, coverFilePath 
                        FROM books 
                        WHERE deleted = 0 
                        ORDER BY RAND() 
                        LIMIT 2
                    )
                    UNION ALL
                    (
                        SELECT 'movie' AS itemType, movieID AS itemID, movieName AS itemName, coverFilepath AS coverFilePath 
                        FROM movies 
                        WHERE deleted = 0 
                        ORDER BY RAND() 
                        LIMIT 2
                    )
                    UNION ALL
                    (
                        SELECT 'tech' AS itemType, techID AS itemID, techName AS itemName, coverFilePath 
                        FROM tech 
                        WHERE deleted = 0 
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

    <div class="notification" id="notification">
        <span class="alert-count">Fine Alerts (0)</span>
    </div>


    <?php
    $sqlUserFines = "SELECT COUNT(*) AS unpaidFines FROM fines WHERE userID = ? AND havePaid NOT IN ('Yes', 'Waived')";
    $stmtUserFines = $conn->prepare($sqlUserFines);

    if ($stmtUserFines) {
        $stmtUserFines->bind_param("i", $userId);
        $stmtUserFines->execute();
        $result = $stmtUserFines->get_result();
        $row = $result->fetch_assoc();
        $unpaidFines = $row['unpaidFines'];
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');
            const customTooltip = notification.querySelector('.custom-tooltip'); // Get the tooltip element
            const alertCount = <?php echo $unpaidFines; ?>;

            // Update the alert count text
            notification.querySelector('.alert-count').innerText = `Fine Alerts (${alertCount})`;

            // Toggle the tooltip when the notification box is clicked
            notification.addEventListener('click', function() {
                if (customTooltip.style.visibility === 'visible') {
                    customTooltip.style.visibility = 'hidden';
                } else {
                    customTooltip.style.visibility = 'visible';
                }
            });

            // Show/hide the alert box
            if (alertCount > 0) {
                notification.classList.add('show');
            } else {
                notification.classList.remove('show');
            }
        });
    </script>



</body>

</html>