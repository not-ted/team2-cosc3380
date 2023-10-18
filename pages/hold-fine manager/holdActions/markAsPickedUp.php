<?php
    include ("../../../connection.php");

    $holdID = $_GET['holdID'];

    // Set hold to requestStatus = 'pickedUp'
    $paidFineQuery = "UPDATE `holds` SET `requestStatus`= 'pickedUp' WHERE `holdID`= $holdID;";
    mysqli_query($conn, $paidFineQuery);
    
?>
