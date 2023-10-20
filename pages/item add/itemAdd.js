function itemAddOnload()
{
    $('.movieDetailContainer').hide();
    $('.techDetailContainer').hide();
}

//Change Item Type
document.getElementById('itemType').addEventListener('change', function() {
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
