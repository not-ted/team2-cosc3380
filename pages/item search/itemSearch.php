<!DOCTYPE html>
<html>
<head>
    <title>Library Item Search</title>
    <link rel="stylesheet" type="text/css" href="itemSearch.css">
</head>
<body>
    <h1>Library Item Search</h1>
    <div class="search-container">
        <form method="post" action="">
            <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <select name="category">
                <option value="all" <?php echo ($_POST['category'] === 'all') ? 'selected' : ''; ?>>All</option>
                <option value="books" <?php echo ($_POST['category'] === 'books') ? 'selected' : ''; ?>>Books</option>
                <option value="movies" <?php echo ($_POST['category'] === 'movies') ? 'selected' : ''; ?>>Movies</option>
                <option value="tech" <?php echo ($_POST['category'] === 'tech') ? 'selected' : ''; ?>>Technology</option>
            </select>
            <button type="submit" name="search-button">Search</button>
        </form>
    </div>
    <div id="results">
        <?php
        if (isset($_POST['search-button'])) {
            $host = "localhost";
            $database = "librarydatabase";
            $username = "root"; // Replace with your database username
            $password = ""; // Replace with your database password (if any)

            $mysqli = new mysqli($host, $username, $password, $database);

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            $search = isset($_POST['search']) ? $_POST['search'] : '';
            $category = isset($_POST['category']) ? $_POST['category'] : 'all';

            // Prepare the SQL statement based on the selected category
            if ($category === 'all') {
                $sql = "SELECT * FROM books WHERE bookName LIKE '%$search%'
                        UNION
                        SELECT * FROM movies WHERE movieName LIKE '%$search%'
                        UNION
                        SELECT * FROM tech WHERE techName LIKE '%$search%'";
            } else {
                $table = $category;
                $columnName = ($category === 'books') ? 'bookName' : (($category === 'movies') ? 'movieName' : 'techName');
                $sql = "SELECT * FROM $table WHERE $columnName LIKE '%$search%'";
            }

            $result = $mysqli->query($sql);

            // Display the search results
            while ($row = $result->fetch_assoc()) {
                echo '<div class="result-item">';
                if ($category === 'books') {
                    echo 'Book Name: ' . $row['bookName'] . '<br>';
                    echo 'ISBN: ' . $row['ISBN'] . '<br>';
                    echo 'Publication Company: ' . $row['publicationCompany'] . '<br>';
                } elseif ($category === 'movies') {
                    echo 'Movie Name: ' . $row['movieName'] . '<br>';
                    echo 'Published Date: ' . $row['publishedDate'] . '<br>';
                    echo 'Production Company: ' . $row['productionCompany'] . '<br>';
                } elseif ($category === 'tech') {
                    echo 'Technology Name: ' . $row['techName'] . '<br>';
                    echo 'Model Number: ' . $row['modelNumber'] . '<br>';
                }
                echo '</div>';
            }

            // Close the database connection
            $mysqli->close();
        }
        ?>
    </div>
    <script src="script.js"></script>
</body>
</html>