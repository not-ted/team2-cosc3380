<!DOCTYPE html>
<html>
<head>
    <title>User Search</title>
    <link rel="stylesheet" type="text/css" href="userSearch.css">
</head>
<body>
    <h1>User Search</h1>
    <div class="search-container">
        <form method="post" action="">
            <select name="userType">
                <option value="all">All Users</option>
                <option value="faculty">Faculty</option>
                <option value="students">Students</option>
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

            $userType = $_POST["userType"];

            // Prepare the SQL statement based on the selected user type
            if ($userType === "all") {
                $sql = "SELECT * FROM users";
            } else {
                $sql = "SELECT * FROM users WHERE userType = '$userType'";
            }

            $result = $mysqli->query($sql);

            // Display the search results in a table
            echo '<table>';
            echo '<tr>';
            echo '<th>User ID</th>';
            echo '<th>UH ID</th>';
            echo '<th>User Type</th>';
            echo '<th>Email</th>';
            echo '<th>First Name</th>';
            echo '<th>Last Name</th>';
            echo '</tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['userID'] . '</td>';
                echo '<td>' . $row['uhID'] . '</td>';
                echo '<td>' . $row['userType'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['firstName'] . '</td>';
                echo '<td>' . $row['lastName'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            // Close the database connection
            $mysqli->close();
        }
        ?>
    </div>
    <script src="userSearch.js"></script>
</body>
</html>