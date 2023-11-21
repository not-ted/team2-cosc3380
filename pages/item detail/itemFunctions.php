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
	$query = "SELECT brandName FROM brands AS B, tech AS T, manufacturedby AS m WHERE T.techID = '$techID' AND B.brandID = m.brandID AND m.techID = T.techID";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			echo htmlspecialchars($row['brandName']);
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

function updateBook($itemID, $conn){
	$bookName = $_POST['title'];
	$author = $_POST['author'];
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$genre = $_POST['genre'];
    $ISBN = $_POST['ISBN'];
    $publicationCompany = $_POST['publisher'];
    $publishedDate = $_POST['publishDate'];

	$query = "UPDATE books SET bookName = '$bookName', genre = '$genre', ISBN = '$ISBN', publicationCompany = '$publicationCompany' WHERE bookID = '$itemID'";
	$query2 = "UPDATE item_description SET description = '$description' WHERE itemID = '$itemID' AND itemType = 'book'";
	$query3 = "UPDATE bookcopy SET publishedDate = '$publishedDate' WHERE bookID = '$itemID'";
	updateAuthor($itemID, $author, $conn);

	if(mysqli_query($conn, $query) && mysqli_query($conn, $query2) && mysqli_query($conn, $query3)){
		$_SESSION['message'] = "Item updated successfully";
	}
	else{
		$_SESSION['message'] = "Error updating item";
	}
}

function updateMovie($itemID, $conn){
	$movieName = $_POST['title'];
	$director = $_POST['director'];
	$description = $_POST['description'];
	$productionCompany = $_POST['productionCompany'];
	$genre = $_POST['genre'];
	$releaseDate = $_POST['releaseDate'];

	$query = "UPDATE movies SET movieName = '$movieName', productionCompany = '$productionCompany', genre = '$genre', publishedDate = '$releaseDate' WHERE movieID = '$itemID'";
	$query2 = "UPDATE item_description SET description = '$description' WHERE itemID = '$itemID' AND itemType = 'movie'";
	updateDirector($itemID, $director, $conn);

	if(mysqli_query($conn, $query) && mysqli_query($conn, $query2)){
		$_SESSION['message'] = "Item updated successfully";
	}
	else{
		$_SESSION['message'] = "Error updating item";
	}
}

function updateTech($itemID, $conn){
	$techName = $_POST['title'];
	$brand = $_POST['brand'];
	$description = $_POST['description'];
	$model = $_POST['model'];

	$query = "UPDATE tech SET techName = '$techName', modelNumber = '$model' WHERE techID = '$itemID'";
	$query2 = "UPDATE item_description SET description = '$description' WHERE itemID = '$itemID' AND itemType = 'tech'";
	updateBrand($itemID, $brand, $conn);

	if(mysqli_query($conn, $query) && mysqli_query($conn, $query2)){
		$_SESSION['message'] = "Item updated successfully";
	}
	else{
		$_SESSION['message'] = "Error updating item";
	}
}

function updateAuthor($itemID, $author, $conn){
	$authorArray = explode(", ", $author);

	foreach($authorArray as $authorName){
		//check if author exists in database
		$query = "SELECT * FROM authors WHERE authorName = '$authorName'";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			//if Author exists check if author is already associated with book
			$row = mysqli_fetch_assoc($result);
			$authorID = $row['authorID'];
			$query2 = "SELECT * FROM writtenby WHERE authorID = '$authorID' AND bookID = '$itemID'";
			$result2 = mysqli_query($conn, $query2);
			if (mysqli_num_rows($result2) > 0) {
				//if author is already associated with book, do nothing
			}
			else{
				//if author is not associated with book, add to writtenby table
				$query3 = "INSERT INTO writtenby (authorID, bookID) VALUES ('$authorID', '$itemID')";
				mysqli_query($conn, $query3);
			}
		}
		else{
			//if author does not exist, add to database
			$query4 = "INSERT INTO authors (authorName) VALUES ('$authorName')";
			mysqli_query($conn, $query4);
			//get authorID
			$authorID = mysqli_insert_id($conn);
			//add to writtenby table
			$query6 = "INSERT INTO writtenby (authorID, bookID) VALUES ('$authorID', '$itemID')";
			mysqli_query($conn, $query6);
		}
	}
	//delete any authors that are no longer associated with book
	$query5 = "SELECT * FROM authors JOIN writtenby ON authors.authorID = writtenby.authorID WHERE bookID = '$itemID'";
	$result3 = mysqli_query($conn, $query5);
	if (mysqli_num_rows($result3) > 0) {
		while($row = mysqli_fetch_assoc($result3)){
			if(!in_array($row['authorName'], $authorArray)){
				$authorID = $row['authorID'];
				$query7 = "DELETE FROM writtenby WHERE authorID = '$authorID' AND bookID = '$itemID'";
				mysqli_query($conn, $query7);
			}
		}
	}
}

function updateDirector($itemID, $director, $conn){
	$directorArray = explode(", ", $director);

	foreach($directorArray as $directorName){
		//check if director exists in database
		$query = "SELECT * FROM directors WHERE directorName = '$directorName'";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0) {
			//if director exists check if director is already associated with movie
			$row = mysqli_fetch_assoc($result);
			$directorID = $row['directorID'];
			$query2 = "SELECT * FROM directedby WHERE directorID = '$directorID' AND movieID = '$itemID'";
			$result2 = mysqli_query($conn, $query2);
			if (mysqli_num_rows($result2) > 0) {
				//if director is already associated with movie, do nothing
			}
			else{
				//if director is not associated with movie, add to directedby table
				$query3 = "INSERT INTO directedby (directorID, movieID) VALUES ('$directorID', '$itemID')";
				mysqli_query($conn, $query3);
			}
		}
		else{
			//if director does not exist, add to database
			$query4 = "INSERT INTO directors (directorName) VALUES ('$directorName')";
			mysqli_query($conn, $query4);
			//get directorID
			$directorID = mysqli_insert_id($conn);
			//add to directedby table
			$query6 = "INSERT INTO directedby (directorID, movieID) VALUES ('$directorID', '$itemID')";
			mysqli_query($conn, $query6);
		}
	}
	//delete any directors that are no longer associated with movie
	$query5 = "SELECT * FROM directors JOIN directedby ON directors.directorID = directedby.directorID WHERE movieID = '$itemID'";
	$result3 = mysqli_query($conn, $query5);
	if (mysqli_num_rows($result3) > 0) {
		while($row = mysqli_fetch_assoc($result3)){
			if(!in_array($row['directorName'], $directorArray)){
				$directorID = $row['directorID'];
				$query7 = "DELETE FROM directedby WHERE directorID = '$directorID' AND movieID = '$itemID'";
				mysqli_query($conn, $query7);
			}
		}
	}
}

function updateBrand($itemID, $brand, $conn){
	//check if brand exists in database
	$query = "SELECT * FROM brands WHERE brandName = '$brand'";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
		//if brand exists check if brand is already associated with tech
		$row = mysqli_fetch_assoc($result);
		$brandID = $row['brandID'];
		$query2 = "SELECT * FROM manufacturedby WHERE brandID = '$brandID' AND techID = '$itemID'";
		$result2 = mysqli_query($conn, $query2);
		if (mysqli_num_rows($result2) > 0) {
			//if brand is already associated with tech, do nothing
		}
		else{
			//if brand is not associated with tech, add to manufacturedby table
			$query3 = "INSERT INTO manufacturedby (brandID, techID) VALUES ('$brandID', '$itemID')";
			mysqli_query($conn, $query3);
		}
	}
	else{
		//if brand does not exist, add to database
		$query4 = "INSERT INTO brands (brandName) VALUES ('$brand')";
		mysqli_query($conn, $query4);
		//get brandID
		$brandID = mysqli_insert_id($conn);
		//add to manufacturedby table
		$query6 = "INSERT INTO manufacturedby (brandID, techID) VALUES ('$brandID', '$itemID')";
		mysqli_query($conn, $query6);
	}
}
?>