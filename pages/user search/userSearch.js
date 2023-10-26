document.addEventListener('DOMContentLoaded', function () {
    // Handle the search button click
    document.getElementById('search-button').addEventListener('click', function () {
        searchUsers();
    });

    // Handle form submission on Enter key press
    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        searchUsers();
    });

    // Function to make an asynchronous request to the server
    function searchUsers() {
        const searchInput = document.getElementById('search-input').value;
        const userType = document.getElementById('user-type').value;
        const resultsContainer = document.getElementById('results');

        // Make an AJAX request to the server for search results
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                resultsContainer.innerHTML = xhr.responseText; // Update the results container
            }
        };

        // Construct the URL for the PHP script
        const url = `search.php?search=${searchInput}&userType=${userType}`;
        xhr.open('GET', url, true);
        xhr.send();
    }
});
