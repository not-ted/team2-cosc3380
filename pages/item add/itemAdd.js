function itemAddOnload()
{
    $('.movieDetailContainer').hide();
    $('.techDetailContainer').hide();
}

//Change Item Type
document.getElementById('itemType').addEventListener('change', function() {
    $('#successMessage').hide();
    $('#errorMessage').hide();
    var selectedValue = this.value;

    if (selectedValue === 'tech') {
        $('.bookDetailContainer').hide();
        $('.movieDetailContainer').hide();
        $('.techDetailContainer').show();
    } else if (selectedValue === 'movie') {
        
        $('.bookDetailContainer').hide();
        $('.movieDetailContainer').show();
        $('.techDetailContainer').hide();
    } else {
        $('.bookDetailContainer').show();
        $('.movieDetailContainer').hide();
        $('.techDetailContainer').hide();
    }
});

function addBook() {
    $('#successMessage').hide();
    $('#errorMessage').hide();

    var bookName = $('#bookName').val();
    var ISBN = $('#ISBN').val();
    var author = $('#author').val();
    var publicationCompany = $('#publicationCompany').val();
    var publishedDate = $('#publishedDate').val();
    var paperbackCopiesAvailable = $('#paperbackCopiesAvailable').val() || 0;
    var paperbackCopyValue = $('#paperbackCopyValue').val() || 0;
    var hardbackCopiesAvailable = $('#hardbackCopiesAvailable').val() || 0;
    var hardbackCopyValue = $('#hardbackCopyValue').val() || 0;
    var coverImage = $('#coverImage')[0].files[0]; // Get the file object

    if (bookName === "" || ISBN === "" || author === "" || publicationCompany === "" || publishedDate === "") {
        $('#errorMessage').text("Make sure all inputs are filled in");
        $('#errorMessage').show();
        return; // Exit the function if any value is null
    }

    var formData = new FormData();
    formData.append('bookName', bookName);
    formData.append('ISBN', ISBN);
    formData.append('author', author);
    formData.append('publicationCompany', publicationCompany);
    formData.append('publishedDate', publishedDate);
    formData.append('paperbackCopiesAvailable', paperbackCopiesAvailable);
    formData.append('paperbackCopyValue', paperbackCopyValue);
    formData.append('hardbackCopiesAvailable', hardbackCopiesAvailable);
    formData.append('hardbackCopyValue', hardbackCopyValue);
    formData.append('coverImage', coverImage);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addBook.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === "Book(s) added successfully") {
                    $('#successMessage').text(response);
                    $('#successMessage').show();
                    clearInputs();
                } else {
                    console.error('Error:', response);
                    $('#errorMessage').text(response);
                    $('#errorMessage').show();
                }
            } else {
                // Request failed, handle the error
                $('#errorMessage').text('Error: Failed to add item');
                $('#errorMessage').show();
            }
        }
    };

    xhr.send(formData);
}

function addMovie() {
    $('#successMessage').hide();
    $('#errorMessage').hide();

    var movieName = $('#movieName').val();
    var distributedBy = $('#distributedBy').val();
    var director = $('#director').val();
    var productionCompany = $('#productionCompany').val();
    var publishedDate = $('#publishedDate').val();
    var movieCopiesAvailable = $('#movieCopiesAvailable').val() || 0;
    var movieCopyValue = $('#movieCopyValue').val() || 0;
    var coverImageMovie = $('#coverImageMovie')[0].files[0]; // Get the file object
    if (
        movieName === "" || 
        distributedBy === "" || 
        director === "" || 
        productionCompany === "" ||
        coverImage === null
    ) {
        $('#errorMessage').text("Make sure all inputs are filled in");
        $('#errorMessage').show();
        return;
    }


    var formData = new FormData();
    formData.append('movieName', movieName);
    formData.append('distributedBy', distributedBy);
    formData.append('director', director);
    formData.append('productionCompany', productionCompany);
    formData.append('publishedDate', publishedDate);
    formData.append('movieCopiesAvailable', movieCopiesAvailable);
    formData.append('movieCopyValue', movieCopyValue);
    formData.append('coverImageMovie', coverImageMovie);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addMovie.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === "Movie(s) added successfully") {
                    $('#successMessage').text(response);
                    $('#successMessage').show();
                    clearInputs();
                } else {
                    console.error('Error:', response);
                    $('#errorMessage').text(response);
                    $('#errorMessage').show();
                }
            } else {
                // Request failed, handle the error
                $('#errorMessage').text('Error: Failed to add item');
                $('#errorMessage').show();
            }
        }
    };

    xhr.send(formData);
}

function addTech() {
    $('#successMessage').hide();
    $('#errorMessage').hide();

    var techName = $('#techName').val();
    var modelNumber = $('#modelNumber').val();
    var publishedDateTech = $('#publishedDateTech').val();
    var brandName = $('#brandName').val();
    var serialNumber = $('#serialNumber').val() || 0;
    var copiesAvailableTech = 1;
    var copyValueTech = $('#copyValueTech').val() || 0;
    var coverImageTech = $('#coverImageTech')[0].files[0]; // Get the file object

    if (techName === "" || modelNumber === "" || brandName === "" || serialNumber === "" || publishedDateTech === "") {
        $('#errorMessage').text("Make sure all inputs are filled in");
        $('#errorMessage').show();
        return; // Exit the function if any value is null
    }

    var formData = new FormData();
    formData.append('techName', techName);
    formData.append('modelNumber', modelNumber);
    formData.append('brandName', brandName);
    formData.append('serialNumber', serialNumber);
    formData.append('publishedDateTech', publishedDateTech);
    formData.append('copiesAvailableTech', copiesAvailableTech);
    formData.append('copyValueTech', copyValueTech);
    formData.append('coverImageTech', coverImageTech);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addTech.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === "Tech(s) added successfully") {
                    $('#successMessage').text(response);
                    $('#successMessage').show();
                    clearInputs();
                } else {
                    console.error('Error:', response);
                    $('#errorMessage').text(response);
                    $('#errorMessage').show();
                }
            } else {
                // Request failed, handle the error
                $('#errorMessage').text('Error: Failed to add item');
                $('#errorMessage').show();
            }
        }
    };

    xhr.send(formData);
}

function clearInputs() {
    var inputs = document.querySelectorAll('input');
    inputs.forEach(function(input) {
        input.value = '';
    });
}
