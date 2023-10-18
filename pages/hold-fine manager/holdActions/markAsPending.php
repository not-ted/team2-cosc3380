<?php
    include ("../../../connection.php");

    $holdID = $_GET['holdID'];

    // Set hold to requestStatus = 'pending'
    $paidFineQuery = "UPDATE `holds` SET `requestStatus`= 'pending' WHERE `holdID`= $holdID;";
    mysqli_query($conn, $paidFineQuery);
    
?>
