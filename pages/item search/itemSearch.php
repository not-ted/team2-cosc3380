<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

include("../../connection.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Item Search</title>
    <link rel="stylesheet" type="text/css" href="itemSearch.css">
    <link rel = "stylesheet" href = "../../main resources/main.css">
    </head>
    <body>

    <div class="header">
		<h1>University Library</h1>
    </div>
	<div class="navbar">
		<ul>
			<li><a href="../home/home.php">Home</a></li>
			<li><a class="active" href="../item search/itemSearch.php">Search</a></li>
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
        <h2>Search Library Catalog</h2>
        <div class="search-container">
            <form method="post" action="">
                <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
                <select name="category">
                    <option value="books" <?php if (isset($category) && $category === 'books') echo 'selected'; ?>>Books</option>
                    <option value="movies" <?php if (isset($category) && $category === 'movies') echo 'selected'; ?>>Movies</option>
                    <option value="tech" <?php if (isset($category) && $category === 'tech') echo 'selected'; ?>>Technology</option>
                </select>
                <button type="submit" name="search-button">Search</button>
            </form>
        </div>
        <div id="results">
            <?php
            include("../../connection.php"); // Include the database connection

            $category = isset($_POST['category']) ? $_POST['category'] : 'books'; // Default to 'books' if no category selected

            if (isset($_POST['search-button'])) {
                $search = isset($_POST['search']) ? $_POST['search'] : '';

                $table = $category;
                $idColumn = ($category === 'books') ? 'bookID' : (($category === 'movies') ? 'movieID' : 'techID');

                $sql = "SELECT $idColumn AS ID, coverFilePath"; // Add 'coverFilePath' to the SELECT

                if ($category === 'books') {
                    $sql .= ", bookName, ISBN, publicationCompany";
                    $columnName = 'bookName';
                } elseif ($category === 'movies') {
                    $sql .= ", movieName, publishedDate, productionCompany";
                    $columnName = 'movieName';
                } elseif ($category === 'tech') {
                    $sql .= ", techName, modelNumber";
                    $columnName = 'techName';
                }

                $sql .= " FROM $table WHERE $columnName LIKE '%$search%'";

                $result = $conn->query($sql); // Use $conn here

                if (!$result) {
                    echo "Error: " . $conn->error;
                } else {
                    // Display the search results in a table
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Item Cover</th>'; // Add a new column for 'item cover'
                    // Table header based on the category
                    if ($category === 'books') {
                        echo '<th>Book Name</th>';
                        echo '<th>ISBN</th>';
                        echo '<th>Publication Company</th>';
                    } elseif ($category === 'movies') {
                        echo '<th>Movie Name</th>';
                        echo '<th>Published Date</th>';
                        echo '<th>Production Company</th>';
                    } elseif ($category === 'tech') {
                        echo '<th>Technology Name</th>';
                        echo '<th>Model Number</th>';
                    }
                    echo '</tr>';

                    // Loop through results and populate the table rows
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        $paramType = ($category === 'books') ? 'book' : (($category === 'movies') ? 'movie' : 'tech');
                        $url = '../item detail/itemDetail.php?id=' . $row['ID'] . '&type=' . $paramType; // Pass 'itemType'
                        echo '<td><a href="' . $url . '">' . $row['ID'] . '</a></td>';

                        // Add an image tag with a CSS class to display the cover image
                        echo '<td><img src="' . $row['coverFilePath'] . '" alt="Item Cover" class="item-cover"></td>';

                        // Display the correct values for book name, ISBN, and publication company
                        if ($category === 'books') {
                            echo '<td>' . $row['bookName'] . '</td>';
                            echo '<td>' . $row['ISBN'] . '</td>';
                            echo '<td>' . $row['publicationCompany'] . '</td>';
                        } elseif ($category === 'movies') {
                            echo '<td>' . $row['movieName'] . '</td>';
                            echo '<td>' . $row['publishedDate'] . '</td>';
                            echo '<td>' . $row['productionCompany'] . '</td>';
                        } elseif ($category === 'tech') {
                            echo '<td>' . $row['techName'] . '</td>';
                            echo '<td>' . $row['modelNumber'] . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                }
                // Close the database connection
                $conn->close();
            }
            ?>
        </div>
    </body>
</html>