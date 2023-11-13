<?php
//Check if user is manager
session_start();

if ($_SESSION['user_type'] !== 'management') {
    header("Location: /index.php"); // Redirect to index.php
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines-Holds Manager</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="report.css">
    <link rel="stylesheet" href="../../main resources/main.css">
    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body onload = 'reportOnload()'>
    <!-- Header -->
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
                <li><a href="../user search/userSearch.php">User Search</a></li>
                <li><a class="active" href="../report/report.php">Reports</a></li>
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

    <!-- Report Option Container -->
    <div class = 'horContainer'>
        <div class = 'verContainer reportOptionBox'>
            <!-- Report Type Option -->
            <p class = 'optionLabel' > Report Type </p>
            <div class = 'horContainer'>
                <select id = 'reportType'>
                    <option value = 'mostBorrowed'> [ITEMS] Most-to-Least Borrowed </option>
                    <option value = 'usersWithMostToLeastFines'> [USERS] Most-to-Least Fines </option>
                    <option value = 'finesGreatestToLeast'> [FINES] Greatest-to-Least Fines </option>
                </select>
            </div>
             
            <!-- Date-Time Option -->
            <div id = 'dateTimeOption' class =  'option'>
                <hr>
                <p class = 'optionLabel' > Date Range </p>
                <div class = 'horContainer'>
                    <label for="datetime">From:</label>
                    <input type="datetime-local" id="datetimeFrom" name="datetimeFrom" value="<?php echo date('Y-m-d\TH:i', strtotime('-1 month', strtotime('00:00:00'))); ?>">
                    <label for="datetime">To:</label>
                    <input type="datetime-local" id="datetimeTo" name="datetimeTo" value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>
            </div>
            
            <!-- Item Option -->
            <div id = 'itemOption' class =  'option'>
                <hr> 
                <p class = 'optionLabel' > Included Items </p>
                <div class = 'horContainer'>
                    <div>
                        <input type="checkbox" id="includeBooks" name="includeBooks" checked onchange="handleIncludeBooksChange(this)">
                        <label for="includeBooks">Books</label>
                    </div>
                    <div>
                        <input type="checkbox" id="includeMovies" name="includeMovies" onchange="handleIncludeMoviesChange(this)">
                        <label for="includeMovies">Movies</label>
                    </div>
                    <div>
                        <input type="checkbox" id="includeTech" name="includeTech" onchange="handleIncludeTechChange(this)">
                        <label for="includeTech">Tech</label>
                    </div>
                </div>
            </div>

            <!-- User Option -->
            <div id = 'userOption' class =  'option'>
                <hr> 
                <p class = 'optionLabel' > Included Users </p>
                <div class = 'horContainer'>
                    <div>
                        <input type="checkbox" id="includeStudents" name="includeStudents" checked onchange="handleIncludeStudentsChange(this)">
                        <label for="includeStudents">Students</label>
                    </div>
                    <div>
                        <input type="checkbox" id="includeFaculty" name="includeFaculty" onchange="handleIncludeFacultyChange(this)">
                        <label for="includeFaculty">Faculty</label>
                    </div>
                </div>
            </div>

            <!-- Fine Option -->
            <div id = 'fineOption' class =  'option'>
                <hr> 
                <p class = 'optionLabel' > Included Fines </p>
                <div class = 'horContainer'>
                    <div>
                        <input type="checkbox" id="includeLate" name="includeLate" checked onchange="handleIncludeLateFineChange(this)"> 
                        <label for="includeLate">Late</label>
                    </div>
                    <div>
                        <input type="checkbox" id="includeLost" name="includeLost" checked onchange="handleIncludeLostFineChange(this)">
                        <label for="includeLost">Lost</label>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
                <div class = 'horContainer' onclick = 'generateReport()'>
                    <button> Generate Report </button>
                </div>
        </div>
    </div>
    
    <!-- Report Output -->
    <div class = 'horContainer'>
        <p id = 'haveChangedMessage' > Filters have changed. Click generate to view updated report. </p>
        <div id = 'reportOutputContainer'></div>
    </div>
    <!-- Import External Javsacript -->
    <script src="report.js"></script>
</body>
</html>