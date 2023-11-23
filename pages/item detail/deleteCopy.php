<?php

include("../../connection.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$itemType = $_POST['itemType'];
	$itemID = $_POST['itemID'];
	$selectedItems = $_POST['toBeRemoved'];
    foreach ($selectedItems as $copyID) {
		if($itemType == "book"){
			$query2 = "DELETE FROM bookcopy WHERE bookCopyID = $copyID LIMIT 1";
		}
		if($itemType == "movie"){
			$query2 = "DELETE FROM moviecopy WHERE movieCopyID = $copyID LIMIT 1";
		}
		if($itemType == "tech"){
			$query2 = "DELETE FROM techcopy WHERE techCopyID = $copyID LIMIT 1";
		}
		mysqli_query($conn, $query2);
    }

	//check if any copies are left
	if($itemType == "book"){
		$query3 = "SELECT COUNT(*) FROM bookcopy WHERE bookID = $itemID";
	}
	if($itemType == "movie"){
		$query3 = "SELECT COUNT(*) FROM moviecopy WHERE movieID = $itemID";
	}
	if($itemType == "tech"){
		$query3 = "SELECT COUNT(*) FROM techcopy WHERE techID = $itemID";
	}
	$result3 = mysqli_query($conn, $query3);
	$row = mysqli_fetch_row($result3);
	
	//if there are no copies left, mark the item as deleted
	if($row[0] == 0){
		if($itemType == "book"){
			$query4 = "UPDATE books SET deleted = 1 WHERE bookID = $itemID";
		}
		if($itemType == "movie"){
			$query4 = "UPDATE movies SET deleted = 1 WHERE movieID = $itemID";
		}
		if($itemType == "tech"){
			$query4 = "UPDATE tech SET deleted = 1 WHERE techID = $itemID";
		}
		mysqli_query($conn, $query4);
	}

	header("Location: itemEdit.php?type=$itemType&id=$itemID");
    exit;
}

?>