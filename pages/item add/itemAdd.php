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
            <input type="text" id="bookName" name="bookName" >

            <!-- ISBN -->
            <label for="ISBN">ISBN:</label>
            <input type="text" id="ISBN" name="ISBN" >

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
            <label for="publishedDate">Published Date:</label>
            <input type="date" id="publishedDate" name="publishedDate" >

            <!-- Copies Available -->
            <label for="movieCopiesAvailable">Copies Available:</label>
            <input type="number" id="copiesAvailable" name="copiesAvailable" value="1" min="0" >

            <!-- Copy Value -->
            <label for="movieCopyValue">Copy Value:</label>
            <input type="number" id="copyValue" name="copyValue"value="0"  min="0" step="0.01" >

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
          <input type="number" id="copiesAvailableTech" name="copiesAvailableTech" value="1" value="0">

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
