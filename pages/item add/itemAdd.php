<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Add</title>

    <!-- Import External Stylesheet -->
    <link rel="stylesheet" href="itemAdd.css">

    <!-- Import jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>
<body onload = 'itemAddOnload()'>
  <div class = 'horContainer'>
      <div class = 'itemAddEntireContainer'> 
        <h1> Add an Item </h1>
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
            <input type="text" id="bookName" name="bookName" required>

            <!-- ISBN -->
            <label for="ISBN">ISBN:</label>
            <input type="text" id="ISBN" name="ISBN" required>

            <!-- Author -->
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>

            <!-- Publication Company -->
            <label for="publicationCompany">Publication Company:</label>
            <input type="text" id="publicationCompany" name="publicationCompany" required>

            <!-- Published Date -->
            <label for="publishedDate">Published Date:</label>
            <input type="date" id="publishedDate" name="publishedDate" required>

            <!-- Paperback Copies Available -->
            <label for="paperbackCopiesAvailable">Paperback Copies Available:</label>
            <input type="number" id="paperbackCopiesAvailable" name="paperbackCopiesAvailiable" value="0" required>

            <!-- Paperback Copy Value -->
            <label for="paperbackCopyValue">Paperback Copy Value:</label>
            <input type="number" id="paperbackCopyValue" name="paperbackCopyValue" value="0" step="0.01" required>

            <!-- Hardback Copies Available -->
            <label for="hardbackCopiesAvailable">Hardback Copies Available:</label>
            <input type="number" id="hardbackCopiesAvailable" name="hardbackCopiesAvailiable" value="0" required>

            <!-- Hardback Copy Value -->
            <label for="hardbackCopyValue">Hardback Copy Value:</label>
            <input type="number" id="hardbackCopyValue" name="hardbackCopyValue" value="0" step="0.01" required>

            <!-- Cover Image -->
            <label for="coverImage">Upload Cover Image:</label>
            <input type="file" id="coverImage" name="coverImage" accept="image/*" required>

            <button type="submit" onclick = 'addBook()' >Add book(s) to inventory</button>
        </div>

        <!-- Movie Details Container -->
        <div class = 'verContainer movieDetailContainer'>
          <form action="addMovie.php" method="get">

            <!-- Movie Name -->
            <label for="movieName">Movie Name:</label>
            <input type="text" id="movieName" name="movieName" required>

            <!-- Distributed By -->
            <label for="distributedBy">Distributed By:</label>
            <input type="text" id="distributedBy" name="distributedBy" required>

            <!-- Director -->
            <label for="director">Director:</label>
            <input type="text" id="director" name="director" required>

            <!-- Publication Company -->
            <label for="publicationCompany">Publication Company:</label>
            <input type="text" id="publicationCompany" name="publicationCompany" required>

            <!-- Published Date -->
            <label for="publishedDate">Published Date:</label>
            <input type="date" id="publishedDate" name="publishedDate" required>

            <!-- Copies Available -->
            <label for="copiesAvailable">Copies Available:</label>
            <input type="number" id="copiesAvailable" name="copiesAvailable" value="0" min="0" required>

            <!-- Copy Value -->
            <label for="copyValue">Copy Value:</label>
            <input type="number" id="copyValue" name="copyValue"value="0"  min="0" step="0.01" required>

            <!-- Cover Image -->
            <label for="coverImage">Cover Image:</label>
            <input type="file" id="coverImage" name="coverImage" accept="image/*">

            <button type="submit" >Add movie(s) to inventory</button>
          </form>
        </div>

      <div class = 'verContainer techDetailContainer'>
        <form action="addTech.php" method="post" enctype="multipart/form-data">
          <!-- Tech Name -->
          <label for="techName">Tech Name:</label>
          <input type="text" id="techName" name="techName">

          <!-- Model Number -->
          <label for="modelNumber">Model Number:</label>
          <input type="text" id="modelNumber" name="modelNumber">

          <!-- Published Date -->
          <label for="publishedDate">Published Date:</label>
          <input type="date" id="publishedDate" name="publishedDate">

          <!-- Brand Name -->
          <label for="brandName">Brand Name:</label>
          <input type="text" id="brandName" name="brandName">

          <!-- Serial Number -->
          <label for="serialNumber">Serial Number:</label>
          <input type="text" id="serialNumber" name="serialNumber">

          <!-- Copies Available -->
          <label for="copiesAvailable">Copies Available:</label>
          <input type="number" id="copiesAvailable" name="copiesAvailable" value="0" value="0">

          <!-- Copy Value -->
          <label for="copyValue">Copy Value:</label>
          <input type="number" step="0.01" id="copyValue" name="copyValue" value="0" value="0.00">

          <!-- Cover Image -->
          <label for="coverImage">Cover Image:</label>
          <input type="file" id="coverImage" name="coverImage">

          <input type="submit" value="Submit">
        </form>

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
