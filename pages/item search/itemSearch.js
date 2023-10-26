document.getElementById("search-button").addEventListener("click", function () {
    const searchInput = document.getElementById("search").value;
    const category = document.getElementById("category").value;
    const resultsContainer = document.getElementById("results");

    // Clear previous search results
    resultsContainer.innerHTML = "";

    // Prepare the data to be sent to the server for searching
    const searchData = {
        search: searchInput,
        category: category,
    };

    // Send a POST request to a PHP script for searching
    fetch("search.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(searchData),
    })
        .then((response) => response.json())
        .then((data) => {
            // Display the search results
            for (const item of data) {
                const resultItem = document.createElement("div");
                resultItem.textContent = `${item.title} (${item.category})`;
                resultsContainer.appendChild(resultItem);
            }
        });
});
