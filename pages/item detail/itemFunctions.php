<?php
include("../../connection.php");

function getAuthors($itemInfo, $conn){
	$bookID = $itemInfo['bookID'];
	$query = "SELECT authorName FROM authors AS A, writtenby AS W WHERE bookID = $bookID AND A.authorID = W.authorID";
	$result = mysqli_query($conn, $query);
	$authors = array();
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            $authors[] = htmlspecialchars($row['authorName']);
        }
        echo implode(", ", $authors);
    }
    else{
        echo "No authors found";
    }
}

function getDirector($itemInfo, $conn){
	$movieID = $itemInfo['movieID'];
	$query = "SELECT directorName FROM directors AS D, directedby AS B WHERE movieID = $movieID AND D.directorID = B.directorID";
	$result = mysqli_query($conn, $query);
	$directors = array();
	if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            $directors[] = htmlspecialchars($row['directorName']);
        }
        echo implode(", ", $directors);
    }
    else{
        echo "No directors found";
    }
}

function getBrand($itemInfo, $conn){
	$techID = $itemInfo['techID'];
	$query = "SELECT brandName FROM brands AS B, manufacturedby AS M WHERE m.techID = '$techID' AND B.brandID = m.brandID";
	$result = mysqli_query($conn, $query);
	$brands = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			$brands[] = htmlspecialchars($row['brandName']);
		}
		echo implode(", ", $brands);
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
			echo htmlspecialchars("No year found");
		}
}

function getDescription($itemInfo, $itemType, $conn){
	if($itemType == "book")
		$itemID = $itemInfo['bookID'];
	if($itemType == "movie")
		$itemID = $itemInfo['movieID'];
	if($itemType == "tech")
		$itemID = $itemInfo['techID'];
	$query = "SELECT description FROM item_description WHERE itemID = '$itemID' AND itemType = '$itemType'";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		echo htmlspecialchars($row['description']);
	}
	else{
		echo "No description found";
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
			echo "This item is currently unavailable";
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

function getCopies($itemID, $itemType, $conn){
	if($itemType == "book"){
		$query = "SELECT DISTINCT bookID, bookCopyID, coverType, borrowStatus, requestStatus
					FROM bookcopy 
					LEFT OUTER JOIN borrowed ON borrowed.itemCopyID = bookCopyID AND borrowed.itemType = 'book' AND borrowStatus LIKE '%checked%'
					LEFT OUTER JOIN holds ON holds.itemCopyID = bookCopyID AND holds.itemType = 'book'
					WHERE bookID = '$itemID'";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>";
				echo "<td>" . $row['bookCopyID'] . "</td>";
				echo "<td>" . $row['coverType'] . "</td>";
				if($row['borrowStatus'] == NULL && $row['requestStatus'] != "readyForPickUp"){
					echo "<td>AVAILABLE</td>";
					echo "<td><input type='checkbox' name='toBeRemoved[]' value='" . $row['bookCopyID'] . "'></td>";
				}
				else if($row['borrowStatus'] != NULL){
					echo "<td>CHECKED OUT</td>";
					echo "<td>Item cannot be removed at this time.</td>";
				}
				else if($row['requestStatus'] == "readyForPickUp"){
					echo "<td>PENDING PICKUP</td>";
					echo "<td>Item cannot be removed at this time.</td>";
				}
			}
		}
	}
	if($itemType == "movie"){
		$query = "SELECT DISTINCT movieID, movieCopyID, borrowStatus, requestStatus
					FROM moviecopy 
					LEFT OUTER JOIN borrowed ON itemCopyID = movieCopyID AND itemType = 'movie' AND borrowStatus LIKE '%checked%' 
					LEFT OUTER JOIN holds ON holds.itemCopyID = movieCopyID AND holds.itemType = 'movie'
					WHERE movieID = '$itemID'";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>";
				echo "<td>" . $row['movieCopyID'] . "</td>";
				if($row['borrowStatus'] == NULL && $row['requestStatus'] != "readyForPickUp"){
					echo "<td>AVAILABLE</td>";
					echo "<td><input type='checkbox' name='toBeRemoved[]' value='" . $row['movieCopyID'] . "'></td>";
				}
				else if($row['borrowStatus'] != NULL){
					echo "<td>CHECKED OUT</td>";
					echo "<td>Item cannot be removed at this time.</td>";
				}
				else if($row['requestStatus'] == "readyForPickUp"){
					echo "<td>PENDING PICKUP</td>";
					echo "<td>Item cannot be removed at this time.</td>";
				}
			}
		}
	}
	if($itemType == "tech"){
		$query = "SELECT DISTINCT techID, techCopyID, borrowStatus, requestStatus
					FROM techcopy 
					LEFT OUTER JOIN borrowed ON itemCopyID = techCopyID AND itemType = 'tech' AND borrowStatus LIKE '%checked%' 
					LEFT OUTER JOIN holds ON holds.itemCopyID = techCopyID AND holds.itemType = 'tech'
					WHERE techID = '$itemID'";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>";
				echo "<td>" . $row['techCopyID'] . "</td>";
				if($row['borrowStatus'] == NULL && $row['requestStatus'] != "readyForPickUp"){
					echo "<td>AVAILABLE</td>";
					echo "<td><input type='checkbox' name='toBeRemoved[]' value='" . $row['techCopyID'] . "'></td>";
				}
				else if($row['borrowStatus'] != NULL){
					echo "<td>CHECKED OUT</td>";
					echo "<td>Item cannot be removed at this time.</td>";
				}
				else if($row['requestStatus'] == "readyForPickUp"){
					echo "<td>PENDING PICKUP</td>";
					echo "<td>Item cannot be removed at this time.</td>";
				}
			}
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