<?php
//Check if user is manager
session_start();

if ($_SESSION['user_type'] !== 'management') {
    header("Location: ../../index.php"); // Redirect to index.php
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Return</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="checkout-return.css">

    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body>
     <!-- Header -->
     <div id = 'header'>
        <h1> Checkout - Return </h1> 
    </div>

        <div class = 'horContainer'>
            <div class = 'verContainer'>
                <label for="transactionType">Transaction Type:</label>
                <select id="transactionType" name="transactionType">
                    <option value="checkout" selected>Checkout</option>
                    <option value="return">Return</option>
                </select>
                <label for="userID">UH ID:</label>
                <input type="text" id="userID" name="userID" required>
                <label for="itemType">Item Type:</label>
                <select id="itemType" name="itemType">
                    <option value="book">Book</option>
                    <option value="movie">Movie</option>
                    <option value="tech">Tech</option>
                </select>
                <label for="itemCopyID">Item Copy ID:</label>
                <input type="number" id="itemCopyID" name="itemCopyID" required>
                <br>

                <!-- Result message  -->
                <div id="resultMessage"></div>

                 <!-- Check out button -->
                    <button id="checkoutButton" onclick="checkoutSubmit()">Check out</button>
                    
                    <!-- Return button -->
                    <button id="returnButton" onclick="returnSubmit()">Return</button>
            </div>
        </div>


  <!-- Import External Javsacript -->
  <script src="checkout-return.js"></script>
</body>
</html>