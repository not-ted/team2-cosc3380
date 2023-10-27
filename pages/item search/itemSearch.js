document.getElementById("search").addEventListener("input", function() {
    var searchValue = this.value;
    var itemType = document.querySelector("select[name='itemType']").value;
    var resultsDiv = document.getElementById("search-results");

    // Send an AJAX request to search.php with the search query
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            resultsDiv.innerHTML = xhr.responseText;
        }
    };
    xhr.send("search=" + searchValue + "&itemType=" + itemType);
});
