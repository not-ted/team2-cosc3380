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
	header("Location: itemEdit.php?type=$itemType&id=$itemID");
    exit;
}

?>