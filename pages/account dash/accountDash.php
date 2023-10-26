<!--- This is the user dashboard page for the library management system. --->
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
$sqlUserProfile = "SELECT * FROM users WHERE userID = $userId";
$result = $conn->query($sqlUserProfile);
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
        <li>Can Borrow: <?php echo ($userData['canBorrow'] == 1) ? 'Yes' : 'No'; ?></li>
        <li>Borrow Limit (days): <?php echo htmlspecialchars($userData['borrowLimit']); ?></li>
        <!-- Add more profile information here -->
    </ul>

    <div class="logout-container">
        <button class="logout-button" onclick="location.href='logout.php'">Logout</button>
    </div>

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

    <h2>Your Current Fines:</h2>
    <table class="generic-table">
        <thead>
            <tr>
                <th>Fine ID</th>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Amount</th>
                <th>Have Paid?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Create a prepared statement to retrieve fines for unpaid items
            $sqlUserFines = "SELECT fines.fineID, fines.fineAmount, fines.havePaid, borrowed.itemType,
            CASE
                WHEN borrowed.itemType = 'book' THEN (SELECT bookName FROM books, bookcopy WHERE books.bookID = bookCopy.bookID AND bookCopy.bookCopyID = borrowed.itemCopyID)
                WHEN borrowed.itemType = 'movie' THEN (SELECT movieName FROM movies, moviecopy WHERE movies.movieID = moviecopy.movieID AND moviecopy.movieCopyID = borrowed.itemCopyID)
                WHEN borrowed.itemType = 'tech' THEN (SELECT techName FROM tech, techcopy WHERE tech.techID = techcopy.techID AND techcopy.techCopyID = borrowed.itemCopyID)
                ELSE NULL
            END AS itemName
            FROM fines
            JOIN borrowed ON fines.borrowID = borrowed.borrowID
            WHERE fines.userID = ? AND fines.havePaid = 'No' AND borrowed.returnedDate IS NULL";

            $stmtUserFines = $conn->prepare($sqlUserFines);

            if ($stmtUserFines) {
                // Bind the user ID to the statement
                $stmtUserFines->bind_param("i", $userId);

                // Execute the statement
                $stmtUserFines->execute();

                // Get the result set
                $result = $stmtUserFines->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fineID = $row['fineID'];
                        $fineAmount = $row['fineAmount'];
                        $havePaid = $row['havePaid'];
                        $itemType = $row['itemType'];
                        $itemName = $row['itemName'];

                        // Create a user-friendly message for each fine
                        $fineStatus =  $row['havePaid'];

                        echo "<tr>
                        <td>$fineID</td>
                        <td>$itemName</td>
                        <td>$itemType</td>
                        <td>$$fineAmount</td>
                        <td>$fineStatus</td>
                    </tr>";
                    }
                } else {
                    // Display a message if the user has no unpaid fines
                    echo "<tr><td colspan='5'>You don't have any unpaid fines at the moment.</td></tr>";
                }

                // Close the statement
                $stmtUserFines->close();
            }
            ?>
        </tbody>
    </table>

    <h2>Your Currently Borrowed Items:</h2>
    <table class="generic-table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Checkout Date</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $borrowedItems = [];

            function fetchCurrentlyBorrowedItems($conn, $userId, $itemType, $sql)
            {
                global $borrowedItems; // Access the global array

                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $itemName = $row['itemName'];
                        $checkoutDate = date("Y-m-d", strtotime($row['checkoutDate']));
                        $dueDate = date("Y-m-d", strtotime($row['dueDate']));

                        echo "<tr>
                    <td>$itemName</td>
                    <td>$itemType</td>
                    <td>$checkoutDate</td>
                    <td>$dueDate</td>
                </tr>";

                        $borrowedItems[] = $itemType; // Add item type to the global array
                    }
                    $stmt->close();
                }
            }

            // Create a prepared statement for currently borrowed books
            $sqlCurrBooks = "SELECT books.bookName AS itemName, c.checkoutDate, c.dueDate 
        FROM borrowed c
        JOIN bookCopy ON c.itemCopyID = bookCopy.bookCopyID
        JOIN books ON bookCopy.bookID = books.bookID
        WHERE c.userID = ? AND c.itemType = 'book' AND borrowStatus = 'checked out'";

            // Create a prepared statement for movies
            $sqlCurrMovies = "SELECT movies.movieName AS itemName, c.checkoutDate, c.dueDate 
        FROM borrowed c
        JOIN moviecopy ON c.itemCopyID = moviecopy.movieCopyID
        JOIN movies ON moviecopy.movieID = movies.movieID
        WHERE c.userID = ? AND c.itemType = 'movie' AND borrowStatus = 'checked out'";

            // Create a prepared statement for tech items
            $sqlCurrTech = "SELECT tech.techName AS itemName, c.checkoutDate, c.dueDate 
        FROM borrowed c
        JOIN techcopy ON c.itemCopyID = techcopy.techCopyID
        JOIN tech ON techcopy.techID = tech.techID
        WHERE c.userID = ? AND c.itemType = 'tech' AND borrowStatus = 'checked out'";

            // Fetch currently borrowed items for each category
            fetchCurrentlyBorrowedItems($conn, $userId, 'book', $sqlCurrBooks);
            fetchCurrentlyBorrowedItems($conn, $userId, 'movie', $sqlCurrMovies);
            fetchCurrentlyBorrowedItems($conn, $userId, 'tech', $sqlCurrTech);
            ?>
        </tbody>
    </table>

    <?php
    // Check if any items have been displayed
    if (empty($borrowedItems)) {
        echo "<p>You don't have any currently borrowed items.</p>";
    }
    ?>

    <h2>Your Reserved Items:</h2>
    <table class="generic-table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Request Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Create a prepared statement to retrieve reserved items with their names
            $sqlReservedItems = "SELECT h.itemType, h.requestStatus, h.itemID,
        CASE
            WHEN h.itemType = 'book' THEN (SELECT bookName FROM books WHERE bookID = h.itemID)
            WHEN h.itemType = 'movie' THEN (SELECT movieName FROM movies WHERE movieID = h.itemID)
            WHEN h.itemType = 'tech' THEN (SELECT techName FROM tech WHERE techID = h.itemID)
            ELSE NULL
        END AS itemName
        FROM holds h
        WHERE h.userID = ?";

            $stmtReservedItems = $conn->prepare($sqlReservedItems);

            if ($stmtReservedItems) {
                // Bind the user ID to the statement
                $stmtReservedItems->bind_param("i", $userId);

                // Execute the statement
                $stmtReservedItems->execute();

                // Get the result set
                $result = $stmtReservedItems->get_result();

                if ($result->num_rows > 0) { // Check if there are reserved items
                    while ($row = $result->fetch_assoc()) {
                        $itemType = $row['itemType'];
                        $requestStatus = $row['requestStatus'];
                        $itemID = $row['itemID'];
                        $itemName = $row['itemName']; // Fix for "itemName" here

                        // Display the reserved items in the table
                        echo "<tr>
                    <td>$itemName</td>
                    <td>$itemType</td>
                    <td>$requestStatus</td>
                </tr>";
                    }
                } else {
                    // Display a message if the user has no reserved items
                    echo "<tr><td colspan='3'>You don't have any reserved items at the moment.</td></tr>";
                }

                // Close the statement
                $stmtReservedItems->close();
            }
            ?>
        </tbody>
    </table>

    <h2> History:<h2>

            <h2>Your Returned items:</h2>
            <table class="generic-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Checkout Date</th>
                        <th>Due Date</th>
                        <th>Returned Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $itemsDisplayed = false; // Variable to track if any items have been displayed

                    function fetchItems($conn, $userId, $itemType, $sql)
                    {
                        global $itemsDisplayed; // Access the global variable

                        $items = [];

                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $itemName = $row['itemName'];
                                $checkoutDate = date("Y-m-d", strtotime($row['checkoutDate']));
                                $dueDate = date("Y-m-d", strtotime($row['dueDate']));
                                $returnedDate = date("Y-m-d", strtotime($row['returnedDate']));

                                echo "<tr>
                <td>$itemName</td>
                <td>$checkoutDate</td>
                <td>$dueDate</td>
                <td>$returnedDate</td>
            </tr>";

                                $itemsDisplayed = true; // Set to true when items are displayed
                            }
                            $stmt->close();
                        }
                    }

                    // Create a prepared statement to retrieve previously checked out books
                    $sqlPrevBooks = "SELECT books.bookName AS itemName, c.checkoutDate, c.dueDate, c.returnedDate
    FROM borrowed c
    JOIN bookCopy ON c.itemCopyID = bookCopy.bookCopyID
    JOIN books ON bookCopy.bookID = books.bookID
    WHERE c.userID = ? AND c.itemType = 'book' AND c.borrowStatus = 'returned'";

                    // Create a prepared statement to retrieve previously checked out movies
                    $sqlPrevMovies = "SELECT movies.movieName AS itemName, c.checkoutDate, c.dueDate, c.returnedDate
    FROM borrowed c
    JOIN moviecopy ON c.itemCopyID = moviecopy.movieCopyID
    JOIN movies ON moviecopy.movieID = movies.movieID
    WHERE c.userID = ? AND c.itemType = 'movie' AND c.borrowStatus = 'returned'";

                    // Create a prepared statement to retrieve previously checked out tech items
                    $sqlPrevTech = "SELECT tech.techName AS itemName, c.checkoutDate, c.dueDate, c.returnedDate
    FROM borrowed c
    JOIN techcopy ON c.itemCopyID = techcopy.techCopyID
    JOIN tech ON techcopy.techID = tech.techID
    WHERE c.userID = ? AND c.itemType = 'tech' AND c.borrowStatus = 'returned'";

                    // Fetch previously checked out items for each category
                    fetchItems($conn, $userId, 'book', $sqlPrevBooks);
                    fetchItems($conn, $userId, 'movie', $sqlPrevMovies);
                    fetchItems($conn, $userId, 'tech', $sqlPrevTech);
                    ?>
                </tbody>
            </table>

            <?php
            // Check if any items have been displayed
            if (!$itemsDisplayed) {
                echo "<p>You don't have any returned items.</p>";
            }
            ?>
            <h2>Your Previously Paid Fines:</h2>
            <table class="generic-table">
                <thead>
                    <tr>
                        <th>Fine ID</th>
                        <th>Item Name</th>
                        <th>Item Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Create a prepared statement to retrieve fine history for paid fines
                    $sqlFineHistory = "SELECT fines.fineID, fines.fineAmount, fines.havePaid, borrowed.itemType,
        CASE
            WHEN borrowed.itemType = 'book' THEN (SELECT bookName FROM books, bookcopy WHERE books.bookID = bookCopy.bookID AND bookCopy.bookCopyID = borrowed.itemCopyID)
            WHEN borrowed.itemType = 'movie' THEN (SELECT movieName FROM movies, moviecopy WHERE movies.movieID = moviecopy.movieID AND moviecopy.movieCopyID = borrowed.itemCopyID)
            WHEN borrowed.itemType = 'tech' THEN (SELECT techName FROM tech, techcopy WHERE tech.techID = techcopy.techID AND techcopy.techCopyID = borrowed.itemCopyID)
            ELSE NULL
        END AS itemName
        FROM fines
        JOIN borrowed ON fines.borrowID = borrowed.borrowID
        WHERE fines.userID = ? AND (fines.havePaid = 'Yes' OR fines.havePaid = 'Waived')";

                    $stmtFineHistory = $conn->prepare($sqlFineHistory);

                    if ($stmtFineHistory) {
                        // Bind the user ID to the statement
                        $stmtFineHistory->bind_param("i", $userId);

                        // Execute the statement
                        $stmtFineHistory->execute();

                        // Get the result set
                        $result = $stmtFineHistory->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $fineID = $row['fineID'];
                                $fineAmount = $row['fineAmount'];
                                $havePaid = $row['havePaid'];
                                $itemType = $row['itemType'];
                                $itemName = $row['itemName'];

                                // Create a user-friendly message for each fine
                                $fineStatus = $row['havePaid'];

                                echo "<tr>
                    <td>$fineID</td>
                    <td>$itemName</td>
                    <td>$itemType</td>
                    <td>$$fineAmount</td>
                    <td>$fineStatus</td>
                </tr>";
                            }
                        } else {
                            // Display a message if the user has no fine history
                            echo "<tr><td colspan='5'>You don't have any fine history at the moment.</td></tr>";
                        }

                        // Close the statement
                        $stmtFineHistory->close();
                    }
                    ?>
                </tbody>
            </table>

</body>

</html>