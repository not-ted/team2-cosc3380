function onload()
{
    getFines(); //Load fines list
    getHolds(); //Load holds list
}

// ✩ -------------------------------------------------- ✩ //
// |                                                     | //
// |                 Functions for Fines                 | //
// |                                                     | //
// ✩ -------------------------------------------------- ✩ //


//View and update sort variables
let viewUnpaid = true;
function handleViewUnpaidChange(checkbox) {
    if (checkbox.checked) {
        viewUnpaid = true;
    } else {
        viewUnpaid = false;
    }
    //Reprint fines list
    getFines();
}

let viewPaid = false;
function handleViewPaidChange(checkbox) {
    if (checkbox.checked) {
        viewPaid = true;
    } else {
        viewPaid = false;
    }

    //Reprint fines list
    getFines();
}

let viewWaived = false;
function handleViewWaivedChange(checkbox) {
    if (checkbox.checked) {
        viewWaived = true;
    } else {
        viewWaived = false;
    }

    //Reprint fines list
    getFines();
}

let finesSortBy = 'newestFirst';
function handleSortChange() {
    var selectedValue = document.getElementById("selectFinesSortBy").value;
    finesSortBy = selectedValue;

    //Reprint fines list
    getFines();
}

document.getElementById("selectFinesSortBy").addEventListener("change", handleSortChange);

//Get fines list
function getFines() {

    var xhttp = new XMLHttpRequest();

    // Construct URL with parameters
    var url = "getFines.php";
    url += "?viewUnpaid=" + viewUnpaid;
    url += "&viewPaid=" + viewPaid;
    url += "&viewWaived=" + viewWaived;
    url += "&finesSortBy=" + finesSortBy;


    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("finesListContainer").innerHTML = this.responseText;
        }
    };

    xhttp.open("GET", url);
    xhttp.send();
}

//Mark fine as paid
function markFineAsPaid(fineID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload fines list
            getFines();
        }
    };

    xhttp.open("GET", "fineActions/markFineAsPaid.php?fineID=" + fineID, true);
    xhttp.send();
}

//Mark fine as unpaid
function markFineAsUnpaid(fineID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload fines list
            getFines();
        }
    };

    xhttp.open("GET", "fineActions/markFineAsUnpaid.php?fineID=" + fineID, true);
    xhttp.send();
}

//Change fine type to lost
function markAsLostFine(fineID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload fines list
            getFines();
        }
    };

    xhttp.open("GET", "fineActions/markAsLostFine.php?fineID=" + fineID, true);
    xhttp.send();
}

//Change fine type to late
function markAsLateFine(fineID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload fines list
            getFines();
        }
    };

    xhttp.open("GET", "fineActions/markAsLateFine.php?fineID=" + fineID, true);
    xhttp.send();
}

//Waive Fine
function waiveFine(fineID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload fines list
            getFines();
        }
    };

    xhttp.open("GET", "fineActions/waiveFine.php?fineID=" + fineID, true);
    xhttp.send();
}

// ✩ -------------------------------------------------- ✩ //
// |                                                     | //
// |                 Functions for Holds                 | //
// |                                                     | //
// ✩ -------------------------------------------------- ✩ //

//View and update sort variables

let viewPending = true;
function handleViewPendingChange(checkbox) {
    if (checkbox.checked) {
        viewPending = true;
    } else {
        viewPending = false;
    }

    //Reprint holds list
    getHolds();
}

let viewDenied = false;
function handleViewDeniedChange(checkbox) {
    if (checkbox.checked) {
        viewDenied = true;
    } else {
        viewDenied = false;
    }

    //Reprint holds list
    getHolds();
}

let viewReadyForPickUp = true;
function handleViewReadyForPickupChange(checkbox) {
    if (checkbox.checked) {
        viewReadyForPickUp = true;
    } else {
        viewReadyForPickUp = false;
    }

    //Reprint holds list
    getHolds();
}

let viewPickedUp = false;
function handleViewPickedUpChange(checkbox) {
    if (checkbox.checked) {
        viewPickedUp = true;
    } else {
        viewPickedUp = false;
    }

    //Reprint holds list
    getHolds();
}

let holdsSortBy = 'readyForPickup';
function handleHoldsSortChange() {
    var selectedValue = document.getElementById("selectHoldsSortBy").value;
    holdsSortBy = selectedValue;

    //Reprint fines list
    getHolds();
}

document.getElementById("selectHoldsSortBy").addEventListener("change", handleHoldsSortChange);

//Get holds list
function getHolds() {

    var xhttp = new XMLHttpRequest();

    // Construct URL with parameters
    var url = "getHolds.php";
    url += "?viewPending=" + viewPending;
    url += "&viewDenied=" + viewDenied;
    url += "&viewPickedUp=" + viewPickedUp;
    url += "&viewReadyForPickUp=" + viewReadyForPickUp;
    url += "&holdsSortBy=" + holdsSortBy;


    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("holdsListContainer").innerHTML = this.responseText;
        }
    };

    xhttp.open("GET", url);
    xhttp.send();
}

//Mark hold as pending
function markAsPending(holdID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload holds list
            getHolds();
        }
    };

    xhttp.open("GET", "holdActions/markAsPending.php?holdID=" + holdID, true);
    xhttp.send();
}

//Mark hold as picked up
function markAsPickedUp(holdID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload holds list
            getHolds();
        }
    };

    xhttp.open("GET", "holdActions/markAsPickedUp.php?holdID=" + holdID, true);
    xhttp.send();
}

//Mark hold as ready for pick up
function markAsReadyForPickUp(holdID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload holds list
            getHolds();
        }
    };

    xhttp.open("GET", "holdActions/markAsReadyForPickUp.php?holdID=" + holdID, true);
    xhttp.send();
}

//Mark hold as denied
function markAsDenied(holdID) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Reload holds list
            getHolds();
        }
    };

    xhttp.open("GET", "holdActions/markAsDenied.php?holdID=" + holdID, true);
    xhttp.send();
}
