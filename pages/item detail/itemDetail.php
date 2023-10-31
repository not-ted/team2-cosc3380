<?php
session_start();
include("../../connection.php");
include("itemFunctions.php");

if(!isset($_SESSION['user_id'])){
	header("Location: ../../index.php");
}

if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // unset the message after displaying it
}
else{
    $message = "";
}

// check if a user ID has been passed in the URL
if (isset($_GET["itemID"])) {
  // get the user ID from the URL
  $itemID = $_GET["id"];
  $itemType = $_GET["type"];	

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
  if($itemInfo['coverFilepath'] != NULL){
  	$coverPath = $itemInfo['coverFilepath'];
  }
  else{
  	$coverPath = "../main resources/placeholder.png";
  }

}

if(isset($_POST['submit'])){
	submitHold($itemID, $conn, $itemType, $_SESSION['user_id']);
}

function submitHold($itemID, $conn, $itemType, $userID){
	$query2 = "SELECT * FROM holds WHERE itemID = '$itemID' AND userID = '$userID' AND requestStatus = 'pending' AND itemType = '$itemType'";
	$result = mysqli_query($conn, $query2);
	if(mysqli_num_rows($result) > 0){
		$_SESSION['message'] = "You already have a pending request for this item";
	}
	else{
		$requestDate = date("Y-m-d");
		$query2 = "INSERT INTO holds (userID, itemType, itemID, requestDate, requestStatus) VALUES ('$userID', '$itemType', '$itemID', '$requestDate', 'pending')";
		$conn->query($query2);
		$_SESSION['message'] =  "Request submitted";
	}
	header("Refresh:0");
}

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
		<button class="back-button" onclick="location.href='../item search/itemSearch.php'">Back to Search</button>
		<button class="logout-button" onclick="location.href='../../logout.php'">Logout</button>
	</div>

	<?php if($itemType == "book"){ ?>

		<div class = "list" id = "bookList" >
			<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
			<ul>
				<li>Ttile: <?php echo htmlspecialchars($itemInfo['bookName']) ?></li>
				<li>Author(s): <?php getAuthors($itemInfo, $conn) ?></li>
				<li>Publisher: <?php echo htmlspecialchars($itemInfo['publicationCompany']) ?> </li>
				<li>ISBN: <?php echo htmlspecialchars($itemInfo['ISBN']) ?></li>
				<li>Publish Date: <?php getYear($itemInfo, $conn) ?></li>
				<li><?php checkAvailable($itemInfo, $conn, 'book') ?></li>
				<li>
					<form method = "POST">
						<input type = "submit" name = "submit" value = "Request this item">
					</form>
				</li>
				<li>
					<?php if(isset($message)) { ?>
						<p class="message"><?php echo $message; ?></p>	
					<?php } ?>
				</li>
			</ul>
		</div>

	<?php } else if($itemType == "movie"){ ?>

		<div class = "list" id = "movieList">
			<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
			<ul>
				<li>Ttile:  <?php echo htmlspecialchars($itemInfo['movieName']) ?></li>
				<li>Director:  <?php getDirector($itemInfo, $conn) ?></li>
				<li>Producer: <?php echo htmlspecialchars($itemInfo['productionCompany']) ?></li>
				<li>Released: <?php echo htmlspecialchars($itemInfo['publishedDate']) ?></li>
				<li><?php checkAvailable($itemInfo, $conn, 'movie') ?></li>
				<li>
					<form method = "POST">
						<input type = "submit" name = "submit" value = "Request this item">
					</form>
				</li>
				<li>
					<?php if(isset($message)) { ?>
						<p class="message"><?php echo $message; ?></p>	
					<?php } ?>
				</li>
			</ul>
		</div>

	<?php } else if($itemType == "tech") {?>

		<div class = "list" id = "techList">
			<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
			<ul>
				<li>Brand: <?php getBrand($itemInfo, $conn) ?></li>
				<li>Model: <?php echo htmlspecialchars($itemInfo['modelNumber']) ?></li>
				<li><?php checkAvailable($itemInfo, $conn, 'tech') ?></li>
				<li>
					<form method = "POST">
						<input type = "submit" name = "submit" value = "Request this item">
					</form>
				</li>
				<li>
					<?php if(isset($message)) { ?>
						<p class="message"><?php echo $message; ?></p>	
					<?php } ?>
				</li>
			</ul>
		</div>

	<?php } ?>

	<div class="waitlist-container" id="waitlist" >
		<h2>Waitlist</h2>
		<table class="generic-table">
			<thead>
				<tr>
					<th>Waitlist Position</th>
					<th>UH ID</th>
					<th>Request Date</th>
				</tr>
			</thead>
			<tbody>
				<?php getWaitlist($itemInfo, $conn, $itemType); ?>
			</tbody>
		</table>
	</div>
</body>