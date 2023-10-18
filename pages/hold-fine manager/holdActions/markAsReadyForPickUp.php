<?php
    include ("../../../connection.php");

    $holdID = $_GET['holdID'];

    // Set hold to requestStatus = 'pickedUp'
    $paidFineQuery = "UPDATE `holds` SET `requestStatus`= 'readyForPickUp' WHERE `holdID`= $holdID;";
    mysqli_query($conn, $paidFineQuery);
    
?>
