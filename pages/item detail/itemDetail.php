<?php
session_start();
include("../../connection.php");
include("itemFunctions.php");

if(!isset($_SESSION['user_id'])){
	header("Location: ../../index.php");
}

// check if a user ID has been passed in the URL
//if (isset($_GET["itemID"])) {
  // get the user ID from the URL
  //$itemID = $_GET["id"];
  //$itemType = $_GET["type"];
  $itemID = 2;
  $itemType = "book";	

  // query the database for the user with the specified ID
  if($itemType == "book"){
  	$query = "SELECT * FROM books WHERE bookID = $itemID LIMIT 1";
  }
  if($itemType == "movie"){
  	$query = "SELECT * FROM movies WHERE movieID = $itemID LIMIT 1";
  }
  if($itemType == "tech"){
  	$query = "SELECT * FROM tech WHERE techID = $itemID LIMIT 1";
  }
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $itemInfo = mysqli_fetch_assoc($result);
  } 
  else {
    echo "Item not found";
  }


  //get cover image filepath
  if($itemInfo['coverFilePath'] != NULL){
  	$coverPath = $itemInfo['coverFilePath'];
  }
  else{
  	$coverPath = "../main resources/placeholder.png";
  }

//}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  	<link rel="stylesheet" href="itemDetail.css">
  	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">		
  	<title>Item Detail</title>
</head>

<body>
  	<h1>Item Detail</h1>	

	<div class="logout-container">
		<button class="back-button" onclick="location.href='../../itemSearch.php'">Back to Search</button>
		<button class="logout-button" onclick="location.href='../../logout.php'">Logout</button>
	</div>

	<div class = "list" id = "bookList" <?php if($itemType != 'book'){?> style="display:none"<?php } ?> >
		<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
		<ul>
			<li>Ttile: <?php echo htmlspecialchars($itemInfo['bookName']) ?></li>
			<li>Author(s): <?php getAuthors($itemInfo, $conn) ?></li>
			<li>Publisher: <?php echo htmlspecialchars($itemInfo['publicationCompany']) ?> </li>
			<li>ISBN: <?php echo htmlspecialchars($itemInfo['ISBN']) ?></li>
			<li>Publish Date: <?php getYear($itemInfo, $conn) ?></li>
			<li><?php checkAvailable($itemInfo, $conn, 'book') ?></li>
		</ul>

	</div>

	<div class = "list" id = "movieList" <?php if($itemType != 'movie'){?> style="display:none"<?php } ?>>
		<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
		<ul>
			<li>Ttile:  <?php echo htmlspecialchars($itemInfo['movieName']) ?></li>
			<li>Director:  <?php getDirector($itemInfo, $conn) ?></li>
			<li>Producer: <?php echo htmlspecialchars($itemInfo['productionCompany']) ?></li>
			<li>Released: <?php echo htmlspecialchars($itemInfo['publishedDate']) ?></li>
			<li><?php checkAvailable($itemInfo, $conn, 'movie') ?></li>
		</ul>
		<button class="request-button"">Request This Item</button>
	</div>

	<div class = "list" id = "techList" <?php if($itemType != 'tech'){?> style="display:none"<?php } ?>>
		<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
		<ul>
			<li>Brand: <?php getBrand($itemInfo, $conn) ?></li>
			<li>Model: <?php echo htmlspecialchars($itemInfo['modelNumber']) ?></li>
			<li><?php checkAvailable($itemInfo, $conn, 'tech') ?></li>
		</ul>
	</div>

	<div class="waitlist-container" id="waitlist" >
		<h2>Waitlist</h2>
		<table class="generic-table">
			<thead>
				<tr>
					<th>Waitlist Position</th>
					<th>User ID</th>
					<th>Request Date</th>
				</tr>
			</thead>
			<tbody>
				<?php getWaitlist($itemID, $conn, $itemType); ?>
			</tbody>
		</table>
	</div>
</body>