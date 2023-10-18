<?php
include 'conncection.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF=8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Item Search</title>
        <link rel="stylesheet" href="">
    </head>
    <body>
        <div class="container my-5">
            <form method="post">
                <input type="text" placeholder="Search items" name="search">
                <button class="btn btn-dark btn-sm" name="submit">Search</button>
            </form>
            <div class="container my-5">
                <table class="table">
                    <?php
                    if(isset($_POST['submit'])){
                        $search=$_POST['search'];

                        $sql="Select * from 'books', 'tech', 'movies' where  id='$search'";
                        $result=mysqli_query($con,$sql)
                        if($result){
                            if(mysqli_num_rows($result) > 0) {
                                echo '<thead>
                                <tr>
                                <th>Sl no</th>
                                <th>Item Name</th>
                                <th>Company Name</th>
                                </tr>
                                </thead>';
                                $row=mysqli_fetch_assoc($result);
                                echo '<tbody>
                                <tr>
                                <td>'.$row['bookID'].'</td>
                                <td>'.$row['bookname'].'</td>
                                <td>'.$row['ISBN'].'</td>
                                <td>'.$row['bookID'].'</td>
                                <td>'.$row['bookname'].'</td>
                                <td>'.$row['ISBN'].'</td>
                                </tr>
                                </tbody>';

                            }
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>