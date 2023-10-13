function reportOnload()
{
    $('#userOption').hide();
    $('#fineOption').hide();
    $('#haveChangedMessage').hide();
    $('#reportOutputContainer').hide();
}

function resetWhenChange()
{
    if ($(reportOutputContainer).is(":visible"))
    {
        $('#reportOutputContainer').hide();
        $('#haveChangedMessage').show();
    }
}
//Detect and handle report type change
let reportType = 'mostBorrowed';

document.getElementById('reportType').addEventListener("change", handleReportTypeChange);

function handleReportTypeChange()
{
    var selectedValue = document.getElementById("reportType").value;
    reportType = selectedValue;

    if (reportType == 'mostBorrowed')
    {
        $('#itemOption').show();
        $('#userOption').hide();
        $('#fineOption').hide();
    }
    else if (reportType == 'usersWithMostToLeastFines')
    {
        $('#itemOption').hide();
        $('#userOption').show();
        $('#fineOption').hide();
    }
    else 
    {
        $('#itemOption').hide();
        $('#userOption').hide();
        $('#fineOption').show();
    }
    resetWhenChange();
}

//Detect and handle date range change
let dateFrom = document.getElementById('datetimeFrom').value;
let dateTo = document.getElementById('datetimeTo').value;

document.getElementById('datetimeFrom').addEventListener("change", handleDateFromChange);
document.getElementById('datetimeTo').addEventListener("change", handleDateToChange);

function handleDateFromChange()
{
    var selectedValue = document.getElementById("datetimeFrom").value;
    dateFrom = selectedValue;
    resetWhenChange();
}

function handleDateToChange()
{
    var selectedValue = document.getElementById("datetimeTo").value;
    dateTo = selectedValue;
    resetWhenChange();
}

//Detect and handle included items change
let includeBooks = true;
let includeMovies = false;
let includeTech = false;

function handleIncludeBooksChange(checkbox)
{
    if (checkbox.checked) {
        includeBooks = true;
    } else {
        includeBooks = false;
    }
    resetWhenChange();
}

function handleIncludeMoviesChange(checkbox)
{
    if (checkbox.checked) {
        includeMovies = true;
    } else {
        includeMovies = false;
    }
    resetWhenChange();
}

function handleIncludeTechChange(checkbox)
{
    if (checkbox.checked) {
        includeTech = true;
    } else {
        includeTech = false;
    }
    resetWhenChange();
}

//Detect and handle included user change
let includeStudents = true;
let includeFaculty = false;

function handleIncludeStudentsChange(checkbox)
{
    if (checkbox.checked) {
        includeStudents = true;
    } else {
        includeStudents = false;
    }
    resetWhenChange();
}

function handleIncludeFacultyChange(checkbox)
{
    if (checkbox.checked) {
        includeFaculty = true;
    } else {
        includeFaculty = false;
    }
    resetWhenChange();
}

//Detect and handle included fine change
let includeLate = true;
let includeLost = true;

function handleIncludeLateFineChange(checkbox)
{
    if (checkbox.checked) {
        includeLate = true;
    } else {
        includeLate = false;
    }
    resetWhenChange();
}

function handleIncludeLostFineChange(checkbox)
{
    if (checkbox.checked) {
        includeLost = true;
    } else {
        includeLost = false;
    }
    resetWhenChange();
}

//GENERATE THE REPORT :D
function generateReport() {

    var xhttp = new XMLHttpRequest();

    // Construct URL with parameters based on report type
    if (reportType == 'mostBorrowed')
    {
        var url = "generateReport.php";
        url += "?reportType=" + reportType;
        url += "&dateFrom=" + dateFrom;
        url += "&dateTo=" + dateTo;
        url += "&includeBooks=" + includeBooks;
        url += "&includeMovies=" + includeMovies;
        url += "&includeTech=" + includeTech;
    }
    else if (reportType == 'usersWithMostToLeastFines')
    {
        var url = "generateReport.php";
        url += "?reportType=" + reportType;
        url += "&dateFrom=" + dateFrom;
        url += "&dateTo=" + dateTo;
        url += "&includeStudents=" + includeStudents;
        url += "&includeFaculty=" + includeFaculty;
    }
    else // reportType = 'finesGreatestToLeast'
    {
        var url = "generateReport.php";
        url += "?reportType=" + reportType;
        url += "&dateFrom=" + dateFrom;
        url += "&dateTo=" + dateTo;
        url += "&includeLate=" + includeLate;
        url += "&includeLost=" + includeLost;
    }


    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("reportOutputContainer").innerHTML = this.responseText;
        }
    };

    xhttp.open("GET", url);
    xhttp.send();
    $('#reportOutputContainer').show();
    $('#haveChangedMessage').hide();
}
