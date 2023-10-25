<?php
//Check if user is manager
session_start();

if ($_SESSION['user_id'] !== 'management') {
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

    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body onload = 'reportOnload()'>
    <!-- Header -->
    <div id = 'header'>
        <h1> Report Generator </h1> 
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