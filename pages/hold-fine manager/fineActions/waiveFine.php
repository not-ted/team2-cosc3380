<?php
    include ("../../../connection.php");

    $fineID = $_GET['fineID'];

    // Set fine to havePaid = 1
    $paidFineQuery = "UPDATE `fines` SET `havePaid`= 'Waived' WHERE `fineID`= $fineID;";
    mysqli_query($conn, $paidFineQuery);

    // Update user's canBorrow to 0 if no more fines can be found under their userID
    $searchUser = "SELECT * FROM fines, users WHERE fines.fineID = $fineID AND fines.userID = users.userID;";
    $result = mysqli_query($conn, $searchUser);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $searchUserFines = "SELECT * FROM fines WHERE userID = {$row['userID']} AND havePaid = 'No';";
        $result2 = mysqli_query($conn, $searchUserFines);
        if(mysqli_num_rows($result2) == 0)
        {
            $userID = $row['userID'];
            $update = "UPDATE `users` SET `canBorrow` = 1 WHERE userID = $userID;";
            mysqli_query($conn, $update);
        }
    }
    
?>
