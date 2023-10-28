<?php
include("../../connection.php");

function getAuthors($itemInfo, $conn){
	$bookID = $itemInfo['bookID'];
	$query = "SELECT authorName FROM authors AS A, writtenBy AS W WHERE bookID = $bookID AND A.authorID = W.authorID";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo htmlspecialchars($row['authorName']) . ", ";
		}
	}
	else{
		echo "No authors found";
	}
}

function getDirector($itemInfo, $conn){
	$movieID = $itemInfo['movieID'];
	$query = "SELECT directorName FROM directors AS D, directedBy AS B WHERE movieID = $movieID AND D.directorID = B.directorID";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo htmlspecialchars($row['directorName']) . ", ";
		}
	}
	else{
		echo "No directors found";
	}
}

function getBrand($itemInfo, $conn){
	$techID = $itemInfo['techID'];
	$query = "SELECT brandName FROM brands AS B, tech AS T WHERE techID = '$techID' AND B.brandID = T.brandID";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo htmlspecialchars($row['brandName']) . ", ";
		}
	}
	else{
		echo "No brands found";
	}
}

function getYear($itemInfo, $conn){
		$itemID = $itemInfo['bookID'];
		$query = "SELECT publishedDate FROM bookCopy WHERE bookID = '$itemID' ORDER BY publishedDate ASC LIMIT 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			echo htmlspecialchars($row['publishedDate']);
		}
		else{
			echo "No year found";
		}
}

function checkAvailable($itemInfo, $conn, $itemType){
	if($itemType == "book"){
		$itemID = $itemInfo['bookID'];
		$query = "SELECT * FROM bookCopy WHERE bookID = '$itemID' AND available = 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			echo mysqli_num_rows($result) . " copies available";
		}
		else{
			echo "No copies available";
		}
	}
	if($itemType == "movie"){
		$itemID = $itemInfo['movieID'];
		$query = "SELECT * FROM movieCopy WHERE movieID = '$itemID' AND available = 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			echo mysqli_num_rows($result) . " copies available";
		}
		else{
			echo "No copies available";
		}
	}
	if($itemType == "tech"){
		$itemID = $itemInfo['techID'];
		$query = "SELECT * FROM techCopy WHERE techID = '$itemID' AND available = 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			echo mysqli_num_rows($result) . " copies available";
		}
		else{
			echo "No copies available";
		}
	}
}

function getWaitlist($itemInfo, $conn, $itemType){
	if($itemType == "book"){
		$itemID = $itemInfo['bookID'];
		$query = "SELECT * FROM holds WHERE bookID = '$itemID' AND requestStatus = 'pending' ORDER BY requestDate ASC";
		$result = mysqli_query($conn, $query);
		$num = 1;
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>";
				echo "<td>" . $num . "</td>";
				echo "<td>" . $row['uhID'] . "</td>";
				echo "<td>" . $row['requestDate'] . "</td>";
				echo "</tr>";
				$num++;
			}
		}
		else{
			echo "No people on waitlist";
		}
	}
	if($itemType == "movie"){
		$itemID = $itemInfo['movieID'];
		$query = "SELECT * FROM holds WHERE movieID = '$itemID' AND requestStatus = 'pending' ORDER BY requestDate ASC";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>";
				echo "<td>" . $num . "</td>";
				echo "<td>" . $row['uhID'] . "</td>";
				echo "<td>" . $row['requestDate'] . "</td>";
				echo "</tr>";
				$num++;
			}
		}
		else{
			echo "No people on waitlist";
		}
	}
	if($itemType == "tech"){
		$itemID = $itemInfo['techID'];
		$query = "SELECT * FROM holds WHERE techID = '$itemID' AND requestStatus = 'pending' ORDER BY requestDate ASC";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>";
				echo "<td>" . $num . "</td>";
				echo "<td>" . $row['uhID'] . "</td>";
				echo "<td>" . $row['requestDate'] . "</td>";
				echo "</tr>";
				$num++;
			}
		}
		else{
			echo "<tr><td colspan='3'>You don't have any currently borrowed items.</td></tr>";
		}
	}
}
?>