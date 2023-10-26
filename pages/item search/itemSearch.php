<!DOCTYPE html>
<html>
<head>
    <title>Library Search</title>
</head>
<body>
    <h1>Library Item Search</h1>
    <form action="search.php" method="POST">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" />
        <select name="type">
            <option value="">All</option>
            <option value="book">Books</option>
            <option value="movie">Movies</option>
            <option value="technology">Technology</option>
        </select>
        <input type="submit" value="Search" />
    </form>

    <?php
    // Handle the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST["search"];
        $type = $_POST["type"];
        $query = "SELECT * FROM items WHERE ";
        if (!empty($search)) {
            $query .= "title LIKE '%$search%' AND ";
        }
        if (!empty($type)) {
            $query .= "type = '$type' AND ";
        }
        $query = rtrim($query, " AND "); // Remove the trailing "AND"

        // Perform the database query
        $conn = new mysqli("localhost", "root", "", "librarydatabase");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<h2>Search Results:</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<p>Title: " . $row["title"] . "<br>Type: " . $row["type"] . "</p>";
            }
        } else {
            echo "<p>No results found.</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
