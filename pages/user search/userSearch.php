<!DOCTYPE html>
<html>
<head>
    <title>Library User Search</title>
    <link rel="stylesheet" type="text/css" href="userSearch.css">
</head>
<body>
    <h1>Library User Search</h1>
    <div class="search-container">
        <form method="post" action="">
            <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <select name="userType">
                <option value="all" <?php echo ($_POST['userType'] === 'all') ? 'selected' : ''; ?>>All Users</option>
                <option value="faculty" <?php echo ($_POST['userType'] === 'faculty') ? 'selected' : ''; ?>>Faculty</option>
                <option value="students" <?php echo ($_POST['userType'] === 'students') ? 'selected' : ''; ?>>Students</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
    <div id="results">
        <?php
        $host = "localhost";
        $database = "librarydatabase";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password

        $mysqli = new mysqli($host, $username, $password, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if (isset($_POST['search'])) {
            $search = $_POST['search'];
            $userType = $_POST['userType'];

            // Prepare the SQL statement based on the selected user type
            if ($userType === 'all') {
                $sql = "SELECT * FROM users WHERE firstName LIKE '%$search'";
            } else {
                $sql = "SELECT * FROM users WHERE userType = '$userType' AND firstName LIKE '%$search'";
            }

            $result = $mysqli->query($sql);

            // Display the search results in a table
            echo '<table>';
            echo '<tr>';
            echo '<th>Username</th>';
            echo '<th>User Type</th>';
            echo '</tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['firstName'] . '</td>';
                echo '<td>' . $row['userType'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        // Close the database connection
        $mysqli->close();
        ?>
    </div>
    <script src="userSearch.js"></script>
</body>
</html>
