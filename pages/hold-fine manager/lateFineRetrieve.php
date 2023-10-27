<?php
    include ("../../connection.php");

    $currentDate = date('Y-m-d');
    $sql = "SELECT borrowed.borrowID, borrowed.userID FROM borrowed WHERE dueDate < '$currentDate' AND returnedDate IS NULL";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $borrowID = $row['borrowID'];
            $userID = $row['userID'];

            // Check if the borrowID already exists in the fines table
            $checkExistenceSQL = "SELECT COUNT(*) AS count FROM fines WHERE borrowID = '$borrowID'";
            $checkExistenceResult = mysqli_query($conn, $checkExistenceSQL);
            $count = mysqli_fetch_assoc($checkExistenceResult)['count'];

            if ($count == 0) {
                $fineAmount = 5.00;
                $insertFineSQL = "INSERT INTO fines (borrowID, userID, fineAmount, havePaid, type) VALUES ('$borrowID', '$userID', '$fineAmount', 'No', 'lost')";
                $insertFineResult = mysqli_query($conn, $insertFineSQL);

                if (!$insertFineResult) {
                    echo "Error adding fine: " . mysqli_error($conn);
                } else {
                    // Update canBorrow attribute in users table
                    $updateCanBorrowSQL = "UPDATE users SET canBorrow = 0 WHERE userID = '$userID'";
                    $updateCanBorrowResult = mysqli_query($conn, $updateCanBorrowSQL);

                    if (!$updateCanBorrowResult) {
                        echo "Error updating canBorrow attribute: " . mysqli_error($conn);
                    }
                }
            }
        }
    } else {
        echo "Error retrieving overdue items: " . mysqli_error($conn);
    }
?>
