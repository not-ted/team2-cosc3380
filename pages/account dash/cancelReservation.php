<?php
// Include the database connection file
include("../../connection.php");

// Check if the holdID is set in the POST request
if (isset($_POST['holdID'])) {
    // Sanitize the holdID
    $holdID = $conn->real_escape_string($_POST['holdID']);

    // Update the holds.requestStatus in the database
    $updateSql = "UPDATE holds SET requestStatus = 'canceled' WHERE holdID = $holdID";
    if ($conn->query($updateSql)) {
        // Send a success response
        echo "Reservation canceled successfully!";
    } else {
        // Send an error response
        echo "Error canceling reservation: " . $conn->error;
    }
} else {
    // Send an error response if holdID is not set
    echo "Error: holdID not provided.";
}

// Close the database connection
$conn->close();
?>
