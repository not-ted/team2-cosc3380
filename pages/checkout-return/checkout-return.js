
 // Update buttons when page loads
 updateButtons();

 // Update buttons when transaction type changes
 $('#transactionType').change(function () {
     updateButtons();
 });

function updateButtons() {
    var transactionType = $('#transactionType').val();
    
    // Hide both buttons
    $('#checkoutButton').hide();
    $('#returnButton').hide();
    
    if (transactionType === 'checkout') {
        $('#checkoutButton').show();
    } else if (transactionType === 'return') {
        $('#returnButton').show();
    }
}


function checkoutSubmit()
{
    $('#resultMessage').html('');

    // Get values from the form inputs
    var transactionType = $('#transactionType').val();
    var userID = $('#userID').val();
    var itemType = $('#itemType').val();
    var itemCopyID = $('#itemCopyID').val();

    // Validate userID and itemCopyID
    if (!/^\d{7}$/.test(userID)) {
        $('#resultMessage').html('<div style="color: red;"> UserID must be a 7-digit number. </div>');
        return;
    }

    if (!/^\d+$/.test(itemCopyID)) {
        $('#resultMessage').html('<div style="color: red;"> Item Copy ID must be a number. </div>');
        return;
    }

    // If validation passes, send data to checkoutItem.php using GET request
    $.ajax({
        type: 'GET',
        url: 'checkoutItem.php',
        data: {
            transactionType: transactionType,
            userID: userID,
            itemType: itemType,
            itemCopyID: itemCopyID
        },
        success: function (response) {
            // Display success message on the HTML page
            $('#resultMessage').html('<div style="color: green;">' + response + '</div>');
            clearInputs();
        },
        error: function (jqXHR) {
            if (jqXHR.status === 400) {
                // Display error message on the HTML page
                $('#resultMessage').html('<div style="color: red;">' + jqXHR.responseText + '</div>');
            } else {
                // Handle other error cases
                console.error("Unexpected error:", jqXHR);
            }
        }
    });
    
}


function returnSubmit()
{
    $('#resultMessage').html('');

    // Get values from the form inputs
    var transactionType = $('#transactionType').val();
    var userID = $('#userID').val();
    var itemType = $('#itemType').val();
    var itemCopyID = $('#itemCopyID').val();

    // Validate userID and itemCopyID
    if (!/^\d{7}$/.test(userID)) {
        $('#resultMessage').html('<div style="color: red;"> UserID must be a 7-digit number. </div>');
        return;
    }

    // Check if transactionType is null
    if (transactionType === null || transactionType.trim() === '') {
        $('#resultMessage').html('<div style="color: red;">Please choose checkout or return.</div>');
    }

    if (itemType === null || itemType.trim() === '') {
        $('#resultMessage').html('<div style="color: red;">Please choose an item type.</div>');
    }

    if (!/^\d+$/.test(itemCopyID)) {
        $('#resultMessage').html('<div style="color: red;"> Item Copy ID must be a number. </div>');
        return;
    }

    // If validation passes, send data to checkoutItem.php using GET request
    $.ajax({
        type: 'GET',
        url: 'returnItem.php',
        data: {
            transactionType: transactionType,
            userID: userID,
            itemType: itemType,
            itemCopyID: itemCopyID
        },
        success: function (response) {
            // Display success message on the HTML page
            $('#resultMessage').html('<div style="color: green;">' + response + '</div>');
            clearInputs();
        },
        error: function (jqXHR) {
            if (jqXHR.status === 400) {
                // Display error message on the HTML page
                $('#resultMessage').html('<div style="color: red;">' + jqXHR.responseText + '</div>');
            } else {
                // Handle other error cases
                console.error("Unexpected error:", jqXHR);
            }
        }
    });
    
}

$('#transactionType, #userID, #itemType, #itemCopyID').change(function () {
    $('#resultMessage').html('');
});



function clearInputs() {
    // Clear input fields
    var inputs = document.querySelectorAll('input');
    inputs.forEach(function (input) {
        input.value = '';
    });

    // Clear dropdown selects
    var selects = document.querySelectorAll('select');
    selects.forEach(function (select) {
        select.value = '';
    });

    $('#transactionType').val('checkout');
    $('#itemType').val('book');

}
