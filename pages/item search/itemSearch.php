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
        include("../../connection.php"); // Include the database connection

        if (isset($_POST['search-button'])) {
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

            $result = $conn->query($sql); // Use $conn here

            // Display the search results in a table
            echo '<table>';
            echo '<tr>';
            // Table header based on the category
            if ($category === 'books') {
                echo '<th>Book ID</th>';
                echo '<th>Book Name</th>';
                echo '<th>ISBN</th>';
                echo '<th>Publication Company</th>';
            } elseif ($category === 'movies') {
                echo '<th>Movie ID</th>';
                echo '<th>Movie Name</th>';
                echo '<th>Published Date</th>';
                echo '<th>Production Company</th>';
            } elseif ($category === 'tech') {
                echo '<th>Tech ID</th>';
                echo '<th>Technology Name</th>';
                echo '<th>Model Number</th>';
            }
            echo '</tr>';

            // Loop through results and populate the table rows
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                if ($category === 'books') {
                    echo '<td><a href="../item detail/itemDetail.php?bookID='  . $row['bookID'] . '">' . $row['bookID'] . '</a></td>';
                    echo '<td>' . $row['bookName'] . '</td>';
                    echo '<td>' . $row['ISBN'] . '</td>';
                    echo '<td>' . $row['publicationCompany'] . '</td>';
                } elseif ($category === 'movies') {
                    echo '<td><a href="itemDetail.php?movieID='  . $row['movieID'] . '">' . $row['movieID'] . '</a></td>';
                    echo '<td>' . $row['movieName'] . '</td>';
                    echo '<td>' . $row['publishedDate'] . '</td>';
                    echo '<td>' . $row['productionCompany'] . '</td>';
                } elseif ($category === 'tech') {
                    echo '<td><a href="itemDetail.php?techID='  . $row['techID'] . '">' . $row['techID'] . '</a></td>';
                    echo '<td>' . $row['techName'] . '</td>';
                    echo '<td>' . $row['modelNumber'] . '</td>';
                }
                echo '</tr>';
            }
            echo '</table';

            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>