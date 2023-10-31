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

            $sql = "SELECT $idColumn AS ID";

            if ($category === 'books') {
                $columnName = 'bookName';
                $sql .= ", $columnName, ISBN, publicationCompany";
            } elseif ($category === 'movies') {
                $columnName = 'movieName';
                $sql .= ", $columnName, publishedDate, productionCompany";
            } elseif ($category === 'tech') {
                $columnName = 'techName';
                $sql .= ", $columnName, modelNumber";
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
                    echo '<td><a href="../item Detail/itemDetail.php?itemType=';
                    if ($category === 'books') {
                        echo 'books';
                    } elseif ($category === 'movies') {
                        echo 'movies';
                    } elseif ($category === 'tech') {
                        echo 'tech';
                    }
                    echo '&';

                    if ($category === 'books') {
                        echo 'bookID=' . $row['ID'];
                    } elseif ($category === 'movies') {
                        echo 'movieID=' . $row['ID'];
                    } elseif ($category === 'tech') {
                        echo 'techID=' . $row['ID'];
                    }
                    echo '">' . $row['ID'] . '</a></td>';
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
                echo '</table';
            }
            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>