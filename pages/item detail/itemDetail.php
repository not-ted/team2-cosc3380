<?php
session_start();
include("../../connection.php");
include("itemFunctions.php");

if(!isset($_SESSION['user_id'])){
	header("Location: ../../index.php");
}
else{
	$userID = $_SESSION['user_id'];
    $userType = $_SESSION['user_type'];
}

if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // unset the message after displaying it
}
else{
    $message = "";
}

// check if an itemID has been passed in the URL
if (isset($_GET["id"])) {

  $itemID = $_GET["id"];
  $itemType = $_GET["type"];	

  // query the database for the item with the specified ID
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
		$requestDate = date("Y-m-d H:i:s");
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
	<div class="navbar">
		<ul>
			<li><a href="../home/home.php">Home</a></li>
			<li><a href="../account dash/accountDash.php">Dashboard</a></li>
			<li><a href="../item search/itemSearch.php">Search</a></li>
			<li style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
		</ul>
	</div>

	<div class = "list">
		<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
		<ul>
			<?php if($itemType == "book"){ ?>
				<li><h1><?php echo htmlspecialchars($itemInfo['bookName']) ?></h1></li>
				<li><h2> <?php getAuthors($itemInfo, $conn) ?> </h2></li>
				<li> <div class="description"><?php getDescription($itemInfo, 'book', $conn) ?></div> </li><br><br>
				<li><?php echo htmlspecialchars($itemInfo['genre'])?></li>
				<li>Publisher: <?php echo htmlspecialchars($itemInfo['publicationCompany']) ?> </li>
				<li>ISBN: <?php echo htmlspecialchars($itemInfo['ISBN']) ?></li>
				<li>Publish Date: <?php getYear($itemInfo, $conn) ?></li><br><br>
				<li><?php checkAvailable($itemInfo, $conn, 'book') ?></li>
			<?php } else if($itemType == "movie"){ ?>	
				<li><h1> <?php echo htmlspecialchars($itemInfo['movieName']) ?> </h1></li>
				<li><h2> <?php getDirector($itemInfo, $conn) ?> </h2></li>
				<li> <div class="description"><?php getDescription($itemInfo, 'movie', $conn) ?></div> </li><br><br>
				<li><?php echo htmlspecialchars($itemInfo['genre'])?></li>
				<li>Producer: <?php echo htmlspecialchars($itemInfo['productionCompany']) ?></li>
				<li>Released: <?php echo htmlspecialchars($itemInfo['publishedDate']) ?></li><br><br>
				<li><?php checkAvailable($itemInfo, $conn, 'movie') ?></li>
			<?php } else if($itemType == "tech") {?>	
				<li><h1><?php echo htmlspecialchars($itemInfo['techName']) ?></h1></li>
				<li><h2><?php getBrand($itemInfo, $conn) ?></h2></li>
				<li> <div class="description"><?php getDescription($itemInfo, 'tech', $conn) ?></div> </li><br><br>
				<li>Model: <?php echo htmlspecialchars($itemInfo['modelNumber']) ?></li><br><br>
				<li><?php checkAvailable($itemInfo, $conn, 'tech') ?></li>
			<?php } ?>
			<?php if($userType == "management"){ ?>
			<li>
				<a href="itemEdit.php?id=<?php echo $itemID;?>&type=<?php echo $itemType; ?>">
					<button class="edit-button">Edit Item Info</button>
				</a>
			</li>
			<?php }  else {?>
			<li>
				<form method = "POST">
					<input type = "submit" name = "submit" value = "Request this item">
				</form>
			</li>
			<?php } ?>
			<li>
				<?php if(isset($message)) { ?>
					<p class="message"><?php echo $message; ?></p>	
				<?php } ?>
			</li>
		</ul>
	</div>

	<?php if($userType == "management"){ ?>
		<div class="waitlist-container" id="waitlist">
			<h1>Waitlist</h1>
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
	<?php } ?>
</body>