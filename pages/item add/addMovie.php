<?php
    include ("../../connection.php");

    // Retrieve form data
    $movieName = $_POST['movieName'];
    $distributedBy = $_POST['distributedBy'];
    $director = $_POST['director'];
    $productionCompany = $_POST['productionCompany'];
    $moviepublishedDate = $_POST['moviepublishedDate'];
    $movieCopiesAvailable = $_POST['movieCopiesAvailable'];
    $movieCopyValue = $_POST['movieCopyValue'];
    $coverImageMovie = $_FILES['coverImageMovie']['name']; // For file uploads
    $response = [];

     // Verify copies availiable
     if ($movieCopiesAvailable == 0) { 
        echo "At least one movie copy need to be added.";
    } else if (strtotime($publishedDate) > strtotime(date('Y-m-d'))) {
        echo "Published date needs to have been in the past";
    } else {
                // First, check if the director already exists
                $directorQuery = "SELECT * FROM directors WHERE directorName = '$director'";
                $directorResult = mysqli_query($conn, $directorQuery);
                if (!$directorResult) {
                    die("Query failed: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($directorResult) == 0) {
                    // director doesn't exist, so add them to the directors table
                    $adddirectorQuery = "INSERT INTO directors (directorName) VALUES ('$director')";
                    $adddirectorResult = mysqli_query($conn, $adddirectorQuery);
                    if (!$adddirectorResult) {
                        die("Query failed: " . mysqli_error($conn));
                    }
                    // Get the auto-generated directorID
                    $directorID = mysqli_insert_id($conn);
                } else {
                    // director already exists, retrieve their directorID
                    $directorRow = mysqli_fetch_assoc($directorResult);
                    $directorID = $directorRow['directorID'];
                }

                // Check if a file was uploaded
                if(isset($_FILES['coverImageMovie']) && $_FILES['coverImageMovie']['error'] == 0) {
                    $targetDir = "../../main resources/item covers/movieCovers/";
                    $targetFile = $targetDir . basename($_FILES['coverImageMovie']['name']);
                    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

                    // Generate a unique image ID (you can use any method you prefer)
                    $uniqueImageID = uniqid();

                    // Construct a new file path with the unique image ID
                    $newFilePath = $targetDir . $uniqueImageID . '.' . $imageFileType;

                    // Move the uploaded file to the new location
                    if(move_uploaded_file($_FILES['coverImageMovie']['tmp_name'], $newFilePath)) {
                        // File was uploaded successfully, now insert the movie record
                        $coverImageMoviePath = '/main resources/item covers/movieCovers/' . $uniqueImageID . '.' . $imageFileType;
                        $insertmovieQuery = "INSERT INTO movies (movieName, publishedDate,distributedBy,  productionCompany, coverFilePath) 
                                            VALUES ('$movieName', '$publishedDate',  '$distributedBy', '$productionCompany', '$coverImageMoviePath')";
                        //Run insert movie query
                        $insertmovieResult = mysqli_query($conn, $insertmovieQuery);
                                        
                        if ($insertmovieResult) {
                            // Get the auto-generated movieID
                            $movieID = mysqli_insert_id($conn);
                            
                            //Add directed by director-movie relationship
                            $adddirectedByRelationship = "INSERT INTO directedBy (directorID, movieID) VALUES ('$directorID ', '$movieID ')";
                            $directedByRelationshipResult = mysqli_query($conn, $adddirectedByRelationship);

                            // Add copies
                            for ($i = 0; $i < $movieCopiesAvailable; $i++) {
                                $addCopyQuery = "INSERT INTO movieCopy (movieID, addDate, available, value) 
                                VALUES ('$movieID', NOW(), 1, '$movieCopyValue')";
                                $addCopyResult = mysqli_query($conn, $addCopyQuery);
                                if (!$addCopyResult) {
                                    die("Query failed: " . mysqli_error($conn));
                                }
                            }
                            echo "Movie(s) added successfully";
                        } else {
                            $response['error'] = true;
                            echo "Error adding movie: " . mysqli_error($conn);
                        }
                    } else {
                        // Error moving the uploaded file
                        echo "Error uploading the file.";
                    }
                } else {
                    // No file uploaded or an error occurred
                    echo "Error: No file uploaded or an error occurred.";
                }

                
    }

    

?>
