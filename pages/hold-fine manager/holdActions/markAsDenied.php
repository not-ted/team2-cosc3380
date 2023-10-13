<?php
    include ("../../../connection.php");

    $holdID = $_GET['holdID'];

    // Set hold to requestStatus = 'pickedUp'
    $paidFineQuery = "UPDATE `holds` SET `requestStatus`= 'denied' WHERE `holdID`= $holdID;";
    mysqli_query($conn, $paidFineQuery);
    
?>
