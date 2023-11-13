<?php 
    session_start();
    if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'management'){
        header("Location: ../../login.php");
    }

    include("../../connection.php");

?>



<!DOCTYPE html>
<html>
<head>
    <title>User Search</title>
    <link rel="stylesheet" type="text/css" href="userSearch.css">
    <link rel = "stylesheet" href = "../../main resources/main.css">
</head>
<body>

<div class="header">
		<h1>University Library</h1>
</div>
	<div class="navbar">
		<ul>
			<li><a href="../home/home.php">Home</a></li>
			<li><a href="../item search/itemSearch.php">Search</a></li>
            <?php if(isset ($_SESSION['user_id'])) { ?>
                <li><a href="../account dash/accountDash.php">My Account</a></li>
            <?php } ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'management'){ ?>
                <li><a href="../item add/itemAdd.php">Add Items</a></li>
                <li><a class="active" href="../user search/userSearch.php">User Search</a></li>
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

    <h1>User Search</h1>
    <div class="search-container">
        <form method="post" action="">
            <input type="text" name="search" placeholder="Search by Name or uhID" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <select name="userType">
                <option value="all">All</option>
                <option value="faculty" <?php echo (isset($_POST['userType']) && $_POST['userType'] === 'faculty') ? 'selected' : ''; ?>>Faculty</option>
                <option value="student" <?php echo (isset($_POST['userType']) && $_POST['userType'] === 'student') ? 'selected' : ''; ?>>Student</option>
            </select>
            <button type="submit" name="search-button">Search</button>
        </form>
    </div>
    <div id="results">
    <?php
        include("../../connection.php");

        if (isset($_POST['search-button'])) {
            $search = isset($_POST['search']) ? $_POST['search'] : '';
            $userType = (isset($_POST['userType'])) ? $_POST['userType'] : 'all';

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
                echo '<td><a href="../user-detail/userDetail.php?id=' . $row['userID'] . '">' . $row['userID'] . '</a></td>';
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