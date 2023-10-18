<?php
    include ("../../../connection.php");

    $fineID = $_GET['fineID'];

    // Set fine to havePaid = 1
    $paidFineQuery = "UPDATE `fines` SET `type`= 'late' WHERE `fineID`= $fineID;";
    mysqli_query($conn, $paidFineQuery);
    
?>
