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
    <title>Item Add</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="itemAdd.css">
    <link rel="stylesheet" href="../../main resources/main.css">
    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body onload = 'itemAddOnload()'>
  <div class="header">
		<h1>University Library</h1>
	</div>
	<div class="navbar">
		<ul>
			<li><a href="../home/home.php">Home</a></li>
			<li><a href="../item search/itemSearch.php">Search</a></li>
            <?php if(isset ($_SESSION['user_id'])) { ?>
                <li><a href="../account dash/accountDash.php">My Account</a></li>
            <?php } ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'management'){ ?>
                <li><a class="active" href="../item add/itemAdd.php">Add Items</a></li>
                <li><a href="../user search/userSearch.php">User Search</a></li>
                <li><a href="../report/report.php">Reports</a></li>
                <li><a href="../hold-fine manager/holdFineManager.php">Holds & Fines</a></li>
                <li><a href="../checkout-return/checkout-return.php">Checkout & Returns</a></li>
            <?php } ?>
            <?php if(isset ($_SESSION['user_id'])) { ?>
			    <li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
            <?php } else { ?>
                <li style="float:right; margin-right:20px"><a class="Sign In" href="../login/login.php">Sign In</a></li>
            <?php } ?>
		</ul>
	</div>

  <div class = 'horContainer'>
      <div class = 'itemAddEntireContainer'> 
        <h2> Add an Item </h2>
        <hr>

        <div class = 'horContainer'>
          <label for="itemType">Select Item Type:</label>
        </div>
        <div class = 'horContainer'>
          <select id="itemType" name="itemType">
              <option value="book">Book</option>
              <option value="movie">Movie</option>
              <option value="tech">Tech</option>
          </select>
        </div>

        <!-- Book Details Container -->
        <div class = 'verContainer bookDetailContainer'>
            <!-- Book Name -->
            <label for="bookName">Book Name:</label>
            <input type="text" id="bookName" name="bookName" >

            <!-- ISBN -->
            <label for="ISBN">ISBN:</label>
            <input type="text" id="ISBN" name="ISBN" >

            <!-- Description -->
            <label for="bookDescription">Book Description:</label>
            <textarea id="bookDescription" name="bookDescription" style="resize: none;" rows="10" cols="100" maxlength='1000' placeholder="Enter the description of the book..."></textarea>
    
            <!-- Genre -->
            <label for="bookGenre">Genre:</label>
            <select id="bookGenre" name="bookGenre">
            <option value="" selected>Select Genre</option>
              <option value="fiction">Fiction</option>
              <option value="nonfiction">Non-Fiction</option>
              <option value="mystery">Mystery</option>
              <option value="scienceFiction">Science Fiction</option>
              <option value="fantasy">Fantasy</option>
              <option value="romance">Romance</option>
              <option value="horror">Horror</option>
              <option value="action">Action</option>
            </select>

            <!-- Author -->
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" >

            <!-- Publication Company -->
            <label for="publicationCompany">Publication Company:</label>
            <input type="text" id="publicationCompany" name="publicationCompany" >

            <!-- Published Date -->
            <label for="publishedDate">Published Date:</label>
            <input type="date" id="publishedDate" name="publishedDate" >

            <!-- Paperback Copies Available -->
            <label for="paperbackCopiesAvailable">Paperback Copies Available:</label>
            <input type="number" id="paperbackCopiesAvailable" name="paperbackCopiesAvailiable" value="1" >

            <!-- Paperback Copy Value -->
            <label for="paperbackCopyValue">Paperback Copy Value:</label>
            <input type="number" id="paperbackCopyValue" name="paperbackCopyValue" value="0" step="0.01" >

            <!-- Hardback Copies Available -->
            <label for="hardbackCopiesAvailable">Hardback Copies Available:</label>
            <input type="number" id="hardbackCopiesAvailable" name="hardbackCopiesAvailiable" value="0" >

            <!-- Hardback Copy Value -->
            <label for="hardbackCopyValue">Hardback Copy Value:</label>
            <input type="number" id="hardbackCopyValue" name="hardbackCopyValue" value="0" step="0.01" >

            <!-- Cover Image -->
            <label for="coverImage">Upload Cover Image:</label>
            <input type="file" id="coverImage" name="coverImage" accept="image/*" >

            <button type="submit" onclick = 'addBook()' >Add book(s) to inventory</button>
        </div>

        <!-- Movie Details Container -->
        <div class = 'verContainer movieDetailContainer'>
            <!-- Movie Name -->
            <label for="movieName">Movie Name:</label>
            <input type="text" id="movieName" name="movieName" >

            <!-- Description -->
            <label for="movieDescription">Movie Description:</label>
            <textarea id="movieDescription" name="movieDescription" style="resize: none;" rows="10" cols="100" maxlength='1000' placeholder="Enter the description of the movie..."></textarea>

            <br>

            <!-- Genre -->
            <label for="movieGenre">Genre:</label>
            <select id="movieGenre" name="movieGenre">
              <option value="" selected>Select Genre</option>
              <option value="action">Action</option>
              <option value="comedy">Comedy</option>
              <option value="drama">Drama</option>
              <option value="sciFi">Science Fiction</option>
              <option value="animation">Animation</option>
              <option value="thriller">Thriller</option>
              <option value="horror">Horror</option>
              <option value="documentary">Documentary</option>
              <!-- Add more genre options as needed -->
            </select>


            <!-- Distributed By -->
            <label for="distributedBy">Distributed By:</label>
            <input type="text" id="distributedBy" name="distributedBy" >

            <!-- Director -->
            <label for="director">Director:</label>
            <input type="text" id="director" name="director" >

            <!-- Publication Company -->
            <label for="productionCompany">Production Company:</label>
            <input type="text" id="productionCompany" name="productionCompany" >

            <!-- Published Date -->
            <label for="moviepublishedDate">Published Date:</label>
            <input type="date" id="moviepublishedDate" name="moviepublishedDate" >

            <!-- Copies Available -->
            <label for="movieCopiesAvailable">Copies Available:</label>
            <input type="number" id="movieCopiesAvailable" name="movieCopiesAvailable" value="1" min="0" >

            <!-- Copy Value -->
            <label for="movieCopyValue">Copy Value:</label>
            <input type="number" id="movieCopyValue" name="movieCopyValue"value="0"  min="0" step="0.01" >

            <!-- Cover Image -->
            <label for="coverImageMovie">Cover Image:</label>
            <input type="file" id="coverImageMovie" name="coverImageMovie" accept="image/*">

            <button type="submit" onclick = 'addMovie()' >Add movie(s) to inventory</button>
        </div>

      <div class = 'verContainer techDetailContainer'>
          <!-- Tech Name -->
          <label for="techName">Tech Name:</label>
          <input type="text" id="techName" name="techName">

          <!-- Model Number -->
          <label for="modelNumber">Model Number:</label>
          <input type="text" id="modelNumber" name="modelNumber">

          <!-- Published Date -->
          <label for="publishedDateTech">Published Date:</label>
          <input type="date" id="publishedDateTech" name="publishedDateTech">

          <!-- Brand Name -->
          <label for="brandName">Brand Name:</label>
          <input type="text" id="brandName" name="brandName">

          <!-- Serial Number -->
          <label for="serialNumber">Serial Number:</label>
          <input type="text" id="serialNumber" name="serialNumber">

          <!-- Copies Available -->
          <label for="copiesAvailableTech">Copies Available:</label>
          <input type="number" id="copiesAvailableTech" name="copiesAvailableTech" value="1" readonly>

          <!-- Copy Value -->
          <label for="copyValueTech">Copy Value:</label>
          <input type="number" step="0.01" id="copyValueTech" name="copyValueTech" value="0" value="0.00">

          <!-- Cover Image -->
          <label for="coverImageTech">Cover Image:</label>
          <input type="file" id="coverImageTech" name="coverImageTech">

          <input type="submit" onclick = 'addTech()' value="Submit">
      </div>
        <!-- Display Errors -->
        <p id="successMessage"  class = 'successMessage' style = 'display: none;'> Item added successfully. </p>
        <p id="errorMessage"  class = 'errorMessage' style = 'display: none;'> Error: Failed to add item </p>
      </div>
  </div>

  <!-- Import External Javsacript -->
  <script src="itemAdd.js"></script>
</body>
</html>
