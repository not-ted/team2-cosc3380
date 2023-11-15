<?php
    include ("../../../connection.php");

    $fineID = $_GET['fineID'];

    // Set fine to havePaid = 0
    $paidFineQuery = "UPDATE `fines` SET `havePaid`= 'No' WHERE `fineID`= $fineID;";
    mysqli_query($conn, $paidFineQuery);

    // Update user's canBorrow to 0 because they have at least one fine present
    $searchUser = "SELECT * FROM fines, users WHERE fineID = $fineID AND fines.userID = users.userID;";
    $result = mysqli_query($conn, $searchUser);
    $row = mysqli_fetch_assoc($result);

    $userID = $row['userID'];
    $updateUser = "UPDATE `users` SET `canBorrow` = 0 WHERE userID = $userID;";
    mysqli_query($conn, $updateUser);

    // Update borrowStatus to 'returned' in the borrowed table
    $updateBorrowedStatus = "UPDATE `borrowed` b
                             INNER JOIN fines f ON b.borrowID = f.borrowID
                             SET b.borrowStatus = 'checked out'
                             WHERE f.fineID = $fineID;";
    mysqli_query($conn, $updateBorrowedStatus);
?>
