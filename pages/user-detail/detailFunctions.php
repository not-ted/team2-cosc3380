<?php
include("../../connection.php");

function getFines($userID, $conn){
	$query = "SELECT * FROM fines WHERE userID = '$userID'";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo "<tr>";
			echo "<td>" . $row["fineID"] . "</td>";
			echo "<td>" . $row["fineAmount"] . "</td>";
			echo "<td>" . $row["type"] . "</td>";
			if($row['havePaid'] == 'Yes'){
				echo "<td style='color:green'>PAID</td>";
			}
			else if($row['havePaid'] == 'Waived'){
				echo "<td style='color:blue'>WAIVED</td>";
			}
			else{
				$_SESSION['hasFines'] = true;
				echo "<td style='color:red'>UNPAID</td>";
			}
		}
	}
}

function isLate($row){
	if($row["dueDate"] < date("Y-m-d")){
		return true;
	}
	else{
		return false;
	}
}

function isAvailable($row, $conn){
	$itemID = $row['itemID'];
	if($row['itemType'] == "book"){
		$query = "SELECT * FROM bookcopy WHERE bookID = '$itemID' && available = 1";
		$result = mysqli_query($conn, $query);
	}
	else if($row['itemType'] == "movie"){
		$query = "SELECT * FROM moviecopy WHERE movieID = '$itemID' && available = 1";
		$result = mysqli_query($conn, $query);
	}
	else if($row['itemType'] == "tech"){
		$query = "SELECT * FROM techcopy WHERE techID = '$itemID' && available = 1";
		$result = mysqli_query($conn, $query);
	}
	if (mysqli_num_rows($result) > 0) {
		return true;
	} 
	else {
		return false;
	}
}	

function displayCheckouts($result, $conn, $itemType){
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo "<tr>";
			echo "<td>" . $row["itemName"] . "</td>";
			echo "<td>" . $itemType . "</td>";
			echo "<td>" . $row["checkoutDate"] . "</td>";
			if($row['returnedDate'] == NULL){
				echo "<td>" . $row["dueDate"] . "</td>";
			}
			else{
				echo "<td>" . $row["returnedDate"] . "</td>";
			}
			if(isLate($row) && $row['returnedDate'] == NULL){
				echo "<td style='color:red'>OVERDUE</td>";
			}
		}
	}
}

function displayHolds($result, $conn, $itemType){
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo "<tr>";
			echo "<td>" . $row["itemName"] . "</td>";
			echo "<td>" . $itemType . "</td>";
			echo "<td>" . $row["requestDate"] . "</td>";
			if(isAvailable($row, $conn)){
				echo "<td style='color:green'>AVAILABLE</td>";
			}
			else{
				echo "<td style='color:red'>UNAVAILABLE</td>";
			}
		}
	}
}

function getCheckouts($userID, $conn){
	// create prepared statements
	$bookQ = 	"SELECT B.bookName AS itemName, D.checkoutDate, D.dueDate, D.returnedDate
    				FROM borrowed AS D
    				JOIN bookcopy AS C ON D.itemCopyID = C.bookcopyID
    				JOIN books AS B ON C.bookID = B.bookID
    				WHERE D.userID = '$userID' AND D.itemType = 'book' AND D.returnedDate IS NULL";

	$getBooks = mysqli_query($conn, $bookQ);

	$movieQ = 	"SELECT M.movieName AS itemName, D.checkoutDate, D.dueDate, D.returnedDate
					FROM borrowed AS D
					JOIN moviecopy AS C ON D.itemCopyID = C.moviecopyID
					JOIN movies AS M ON C.movieID = M.movieID
					WHERE D.userID = '$userID' AND D.itemType = 'movie' AND D.returnedDate IS NULL";
				
	$getMovies = mysqli_query($conn, $movieQ);

	$techQ = 	"SELECT T.techName AS itemName, D.checkoutDate, D.dueDate, D.returnedDate
					FROM borrowed AS D
					JOIN techcopy AS C ON D.itemCopyID = C.techcopyID
					JOIN tech AS T ON C.techID = T.techID
					WHERE D.userID = '$userID' AND D.itemType = 'tech' AND D.returnedDate IS NULL";

	$getTech = mysqli_query($conn, $techQ);

	displayCheckouts($getBooks, $conn, "book");
	displayCheckouts($getMovies, $conn, "movie");
	displayCheckouts($getTech, $conn, "tech");

}

function getHolds($userID, $conn){
	// create prepared statements
	$bookQ = 	"SELECT bookName AS itemName, bookID AS itemID, requestDate, itemType 
					FROM holds
					JOIN books ON itemID = bookID
					WHERE userID = '$userID' AND itemType = 'book' AND requestStatus = 'pending'";

	$getBooks = mysqli_query($conn, $bookQ);

	$movieQ = 	"SELECT movieName AS itemName, movieID AS itemID, requestDate, itemType 
					FROM holds
					JOIN movies ON itemID = movieID
					WHERE userID = '$userID' AND itemType = 'movie' AND requestStatus = 'pending'";

	$getMovies = mysqli_query($conn, $movieQ);

	$techQ = 	"SELECT techName AS itemName, techID AS itemID, requestDate, itemType 
					FROM holds
					JOIN tech ON itemID = techID
					WHERE userID = '$userID' AND itemType = 'tech' AND requestStatus = 'pending'";

	$getTech = mysqli_query($conn, $techQ);

	displayHolds($getBooks, $conn, "book");
	displayHolds($getMovies, $conn, "movie");
	displayHolds($getTech, $conn, "tech");

}

function getHistory($userID, $conn){
	// create prepared statements
	$bookQ = 	"SELECT B.bookName AS itemName, D.checkoutDate, D.dueDate, D.returnedDate
    				FROM borrowed AS D
 					JOIN bookcopy AS C ON D.itemCopyID = C.bookcopyID
    				JOIN books AS B ON C.bookID = B.bookID
    				WHERE D.userID = '$userID' AND D.itemType = 'book' AND D.returnedDate IS NOT NULL";

	$getBooks = mysqli_query($conn, $bookQ);

	$movieQ = 	"SELECT M.movieName AS itemName, D.checkoutDate, D.dueDate, D.returnedDate
					FROM borrowed AS D
					JOIN moviecopy AS C ON D.itemCopyID = C.moviecopyID
					JOIN movies AS M ON C.movieID = M.movieID
					WHERE D.userID = '$userID' AND D.itemType = 'movie' AND D.returnedDate IS NOT NULL";

	$getMovies = mysqli_query($conn, $movieQ);

	$techQ =	"SELECT T.techName AS itemName, D.checkoutDate, D.dueDate, D.returnedDate
					FROM borrowed AS D
					JOIN techcopy AS C ON D.itemCopyID = C.techcopyID
					JOIN tech AS T ON C.techID = T.techID
					WHERE D.userID = '$userID' AND D.itemType = 'tech' AND D.returnedDate IS NOT NULL";

	$getTech = mysqli_query($conn, $techQ);

	displayCheckouts($getBooks, $conn, "book");
	displayCheckouts($getMovies, $conn, "movie");
	displayCheckouts($getTech, $conn, "tech");

}
?>