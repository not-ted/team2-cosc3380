<?php
session_start();
include("../../connection.php");
include("itemFunctions.php");

if(!isset($_SESSION['user_id'])){
	header("Location: ../../login.php");
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

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($itemType == "book"){
		updateBook($itemID, $conn);
	}
	if($itemType == "movie"){
		updateMovie($itemID, $conn);
	}
	if($itemType == "tech"){
		updateTech($itemID, $conn);
	}
	header("Location: itemDetail.php?id=$itemID&type=$itemType");
}
?>

<!DOCTYPE html>
<html lang = "en">

<head>
	<link rel = "stylesheet" href = "itemDetail.css">
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title><?php echo $itemInfo['title']; ?></title>
</head>

<body>

<div class="header">
		<h1>University Library</h1>
</div>
	<div class="navbar">
		<ul>
			<li><a class="button" href="../home/home.php">Home</a></li>
			<li><a class="button" href="../item search/itemSearch.php">Search</a></li>
            <?php if(isset ($_SESSION['user_id'])) { ?>
                <li><a class="button" href="../account dash/accountDash.php">My Account</a></li>
            <?php } ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'management'){ ?>
                <li><a class="button" href="../item add/itemAdd.php">Add Items</a></li>
                <li><a class="button" href="../user search/userSearch.php">User Search</a></li>
                <li><a class="button" href="../report/report.php">Reports</a></li>
                <li><a class="button" href="../hold-fine manager/holdFineManager.php">Holds & Fines</a></li>
                <li><a class="button" href="../checkout-return/checkout-return.php">Checkout & Returns</a></li>
            <?php } ?>
            <?php if(isset ($_SESSION['user_id'])) { ?>
			    <li class="button" style="float:right; margin-right:20px"><a class="logout" href="../account dash/logout.php">Sign Out</a></li>
            <?php } else { ?>
                <li class="button" style="float:right; margin-right:20px"><a class="Sign In" href="../login/login.php">Sign In</a></li>
            <?php } ?>
		</ul>
	</div>


	<script>
		var buttons = document.getElementsByClassName('button');
		for (var i = 0; i < buttons.length; i++) {
    		buttons[i].addEventListener('click', function(event) {
				if (!confirm("Any changes will not be submitted. Are you sure you want to leave?")) {
					event.preventDefault();
				}
    		});
		}
	</script>

	<div class="list">
		<img id = "coverimage" src ="<?php echo htmlspecialchars($coverPath); ?>">
		<form id="editForm" method="POST">
			<?php if($itemType == "book"){ ?>
				<label for="title">Title</label>
				<input type="text" name="title" value="<?php echo $itemInfo['bookName']; ?>" required>

				<label for="author">Author(s) - Please use ',' if there are multiple authors</label>
				<input type="text" name="author" value="<?php getAuthors($itemInfo, $conn); ?>" required>

				<label for="description">Description</label>
				<textarea id="description" name="description" required><?php getDescription($itemInfo, 'book', $conn) ?></textarea>

				<label for="genre">Genre</label>
				<input type="text" name="genre" value="<?php echo $itemInfo['genre']; ?>" required>

				<label for="publisher">Publisher</label>
				<input type="text" name="publisher" value="<?php echo $itemInfo['publicationCompany']; ?>" required>

				<label for="publishDate">Publish Date</label>
				<input type="text" name="publishDate" value="<?php echo getYear($itemInfo, $conn) ?>" required>
				

			<?php } else if($itemType == "movie"){ ?>	
				<label for="title">Title</label>
				<input type="text" name="title" value="<?php echo $itemInfo['movieName']; ?>" required>

				<label for="director">Director(s)</label>
				<input type="text" name="director" value="<?php getDirector($itemInfo, $conn); ?>" required>

				<label for="description">Description</label>
				<textarea id="description" name="description" required><?php getDescription($itemInfo, 'movie', $conn) ?></textarea>

				<label for="productionCompany">Production Company</label>
				<input type="text" name="productionCompany" value="<?php echo $itemInfo['productionCompany']; ?>" required>

				<label for="genre">Genre</label>
				<input type="text" name="genre" value="<?php echo $itemInfo['genre']; ?>" required>

				<label for="releaseDate">Release Date</label>
				<input type="text" name="releaseDate" value="<?php echo $itemInfo['publishedDate'] ?>" required>

			<?php } else if($itemType == "tech") {?>
				<label for="title">Name</label>
				<input type="text" name="title" value="<?php echo $itemInfo['techName']; ?>" required>

				<label for="brand">Brand</label>
				<input type="text" name="brand" value="<?php getBrand($itemInfo, $conn); ?>" required>

				<label for="description">Description</label>
				<textarea id="description" name="description" required><?php getDescription($itemInfo, 'tech', $conn) ?></textarea>

				<label for="model">Model</label>
				<input type="text" name="model" value="<?php echo $itemInfo['modelNumber']; ?>" required>
			<?php } ?>
				<input type="submit" value="Submit Changes">	
		</form>
	</div>

</body>