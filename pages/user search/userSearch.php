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
            <input type="text" name="search" placeholder="Search by Name or uhID" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <select name="userType">
                <option value="all">All</option>
                <option value="faculty" <?php echo ($_POST['userType'] === 'faculty') ? 'selected' : ''; ?>>Faculty</option>
                <option value="student" <?php echo ($_POST['userType'] === 'student') ? 'selected' : ''; ?>>Student</option>
            </select>
            <button type="submit" name="search-button">Search</button>
        </form>
    </div>
    <div id="results">
    <?php
        include("../../connection.php");

        if (isset($_POST['search-button'])) {
            $search = isset($_POST['search']) ? $_POST['search'] : '';
            $userType = isset($_POST['userType']) ? $_POST['userType'] : 'all';

            // Prepare the SQL statement based on the selected user type
            if ($userType === 'all') {
                $sql = "SELECT * FROM users WHERE (firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR uhID LIKE '%$search')";
            } else {
                $sql = "SELECT * FROM users WHERE (firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR uhID LIKE '%$search') AND userType = '$userType'";
            }

            $result = $conn->query($sql);

            // Display the search results in a table
            echo '<table>';
            echo '<tr>';
            echo '<th>User ID</th>';
            echo '<th>uhID</th>';
            echo '<th>User Type</th>';
            echo '<th>Email</th>';
            echo '<th>First Name</th>';
            echo '<th>Last Name</th>';
            echo '</tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td><a href="userDetail.php?userID=' . $row['userID'] . '">' . $row['userID'] . '</a></td>';
                echo '<td>' . $row['uhID'] . '</td>';
                echo '<td>' . $row['userType'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['firstName'] . '</td>';
                echo '<td>' . $row['lastName'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
    <script src="userSearch.js"></script>
</body>
</html>