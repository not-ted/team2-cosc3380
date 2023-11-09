<?php
include("../../connection.php");

function getAuthors($itemInfo, $conn){
	$bookID = $itemInfo['bookID'];
	$query = "SELECT authorName FROM authors AS A, writtenby AS W WHERE bookID = $bookID AND A.authorID = W.authorID";
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
	$query = "SELECT directorName FROM directors AS D, directedby AS B WHERE movieID = $movieID AND D.directorID = B.directorID";
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
	$query = "SELECT brandName FROM brands AS B, tech AS T, manufacturedby AS m WHERE T.techID = '$techID' AND B.brandID = m.brandID AND m.techID = T.techID";
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
		$query = "SELECT publishedDate FROM bookcopy WHERE bookID = '$itemID' ORDER BY publishedDate ASC LIMIT 1";
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
		$query = "SELECT * FROM bookcopy WHERE bookID = '$itemID' AND available = 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			echo mysqli_num_rows($result) . " copies available";
		}
		else{
			echo "This item is currentyly unavailable";
		}
	}
	if($itemType == "movie"){
		$itemID = $itemInfo['movieID'];
		$query = "SELECT * FROM moviecopy WHERE movieID = '$itemID' AND available = 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			echo mysqli_num_rows($result) . " copies available";
		}
		else{
			echo "This item is currently unavailable";
		}
	}
	if($itemType == "tech"){
		$itemID = $itemInfo['techID'];
		$query = "SELECT * FROM techcopy WHERE techID = '$itemID' AND available = 1";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			echo mysqli_num_rows($result) . " copies available";
		}
		else{
			echo "This item is currently unavailable";
		}
	}
}

function getWaitlist($itemInfo, $conn, $itemType){
	$num = 1;

	if($itemType == "book"){
		$itemID = $itemInfo['bookID'];
		$query = "SELECT requestDate, uhID FROM holds, users WHERE itemID = '$itemID' AND requestStatus = 'pending' AND itemType = 'book' AND holds.userID = users.userID ORDER BY requestDate ASC";
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
			echo "<tr><td colspan='3'>Waitlist is empty</td></tr>";
		}
	}
	if($itemType == "movie"){
		$itemID = $itemInfo['movieID'];
		$query = "SELECT requestDate, uhID FROM holds, users WHERE itemID = '$itemID' AND requestStatus = 'pending' AND itemType = 'movie' AND holds.userID = users.userID ORDER BY requestDate ASC";
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
			echo "<tr><td colspan='3'>Waitlist is empty</td></tr>";
		}
	}
	if($itemType == "tech"){
		$itemID = $itemInfo['techID'];
		$query = "SELECT requestDate, uhID FROM holds, users WHERE itemID = '$itemID' AND requestStatus = 'pending' AND itemType = 'tech' AND holds.userID = users.userID ORDER BY requestDate ASC";
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
			echo "<tr><td colspan='3'>Waitlist is empty</td></tr>";
		}
	}
}
?>