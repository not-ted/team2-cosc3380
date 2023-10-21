<?php
    include ("../../connection.php");

    // Retrieve form data
    $techName = $_POST['techName'];
    $modelNumber = $_POST['modelNumber'];
    $brandName = $_POST['brandName'];
    $serialNumber = $_POST['serialNumber'];
    $publishedDateTech = $_POST['publishedDateTech'];
    $copiesAvailableTech = $_POST['copiesAvailableTech'];
    $copyValueTech = $_POST['copyValueTech'];
    $coverImageTech = $_FILES['coverImageTech']['name']; // For file uploads
    $response = [];

     // Verify copies availiable
     if ($copiesAvailableTech == 0) { 
        echo "At least one tech copy need to be added.";
    } else if (strtotime($publishedDateTech) > strtotime(date('Y-m-d'))) {
        echo "Published date needs to have been in the past";
    } else {
                // First, check if the brand already exists
                $brandQuery = "SELECT * FROM brands WHERE brandName = '$brandName'";
                $brandResult = mysqli_query($conn, $brandQuery);
                if (!$brandResult) {
                    die("Query failed: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($brandResult) == 0) {
                    // brand doesn't exist, so add them to the brands table
                    $addbrandQuery = "INSERT INTO brands (brandName) VALUES ('$brandName')";
                    $addbrandResult = mysqli_query($conn, $addbrandQuery);
                    if (!$addbrandResult) {
                        die("Query failed: " . mysqli_error($conn));
                    }
                    // Get the auto-generated brandID
                    $brandID = mysqli_insert_id($conn);
                } else {
                    // brand already exists, retrieve their brandID
                    $brandRow = mysqli_fetch_assoc($brandResult);
                    $brandID = $brandRow['brandID'];
                }

                // Check if a file was uploaded
                if(isset($_FILES['coverImageTech']) && $_FILES['coverImageTech']['error'] == 0) {
                    $targetDir = "../../main resources/item covers/techCovers/";
                    $targetFile = $targetDir . basename($_FILES['coverImageTech']['name']);
                    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

                    // Generate a unique image ID (you can use any method you prefer)
                    $uniqueImageID = uniqid();

                    // Construct a new file path with the unique image ID
                    $newFilePath = $targetDir . $uniqueImageID . '.' . $imageFileType;

                    // Move the uploaded file to the new location
                    if(move_uploaded_file($_FILES['coverImageTech']['tmp_name'], $newFilePath)) {
                        // File was uploaded successfully, now insert the tech record
                        $coverImagetechPath = '/main resources/item covers/techCovers/' . $uniqueImageID . '.' . $imageFileType;
                        $inserttechQuery = "INSERT INTO  tech (techName, publishedDate, modelNumber, coverFilePath) 
                                            VALUES ('$techName', '$publishedDateTech',  '$modelNumber',  '$coverImagetechPath')";
                        //Run insert tech query
                        $inserttechResult = mysqli_query($conn, $inserttechQuery);
                                        
                        if ($inserttechResult) {
                            // Get the auto-generated techID
                            $techID = mysqli_insert_id($conn);
                            
                            //Add directed by brand-tech relationship
                            $adddirectedByRelationship = "INSERT INTO manufacturedBy (brandID, techID) VALUES ('$brandID ', '$techID ')";
                            $directedByRelationshipResult = mysqli_query($conn, $adddirectedByRelationship);

                            // Add copies
                            for ($i = 0; $i < $copiesAvailableTech; $i++) {
                                $addCopyQuery = "INSERT INTO techCopy (techID, addDate,serialNumber, available, value) 
                                VALUES ('$techID', NOW(), '$serialNumber', 1, '$copyValueTech')";
                                $addCopyResult = mysqli_query($conn, $addCopyQuery);
                                if (!$addCopyResult) {
                                    die("Query failed: " . mysqli_error($conn));
                                }
                            }
                            echo "Tech(s) added successfully";
                        } else {
                            $response['error'] = true;
                            echo "Error adding tech: " . mysqli_error($conn);
                        }
                    } else {
                        // Error moving the uploaded file
                        echo "Error uploading the file.";
                    }
                } else {
                    // No file uploaded or an error occurred
                    echo "Error: No file uploaded or a file error occured." . $_FILES['coverImageTech']['error'];
                }

                
    }

    

?>
