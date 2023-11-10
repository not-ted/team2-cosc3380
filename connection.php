<?php
    //This is the code that connects our application to the database

    //The servername, dbusername, and password is set up for local hosting while we build our app. 
    //We will need to change it to our web host's credentials when we actually host it.

    $servername = "localhost"; 
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "id21484403_librarydatabase";
    $port = 3307; // Port is set to 3307

    try {
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname, $port);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
?>
