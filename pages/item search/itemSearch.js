document.getElementById("search").addEventListener("input", function() {
    // Get the values from the input and select elements
    var searchValue = this.value;
    var itemType = document.querySelector("select[name='itemType']").value;
    var resultsDiv = document.getElementById("search-results");

    // Send an AJAX request using the fetch API
    fetch("itemsearch.php", {
        method: "POST",
        headers: {
            "Content-type": "application/x-www-form-urlencoded",
        },
        body: "search=" + encodeURIComponent(searchValue) + "&itemType=" + encodeURIComponent(itemType),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(responseText => {
        // Update the search results div with the response
        resultsDiv.innerHTML = responseText;
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
});
