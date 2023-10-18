<?php
    include ("../../connection.php");

    $itemType = $_GET['itemType'];
    if ($itemType == "book")
    {
        $bookName = $_GET['bookName'];
        $ISBN = $_GET['ISBN'];
        $author = $_GET['author'];     
        $publicationCompany = $_GET['publicationCompany'];     
        $publishedDate = $_GET['publishedDate'];     
        $paperbackCopiesAvailiable = $_GET['paperbackCopiesAvailiable'];      
        $paperbackCopyValue = $_GET['paperbackCopyValue'];      
        $hardbackCopiesAvailiable = $_GET['hardbackCopiesAvailiable'];     
        $hardbackCopyValue = $_GET['hardbackCopyValue'];      
    }
    else if ($itemType == "movie")
    {
        $movieName = $_GET['movieName'];
        $distributedBy = $_GET['distributedBy'];
        $director = $_GET['director'];     
        $publicationCompany = $_GET['publicationCompany'];     
        $publishedDate = $_GET['publishedDate'];     
        $copiesAvailiable = $_GET['copiesAvailiable'];      
        $copyValue = $_GET['copyValue'];      
    }
    else //If itemType is tech
    {
        $techName = $_GET['techName'];
        $modelNumber = $_GET['modelNumber'];
        $publishedDate = $_GET['publishedDate'];     
        $brandName = $_GET['brandName'];     
        $serialNumber = $_GET['serialNumber'];     
        $copiesAvailiable = $_GET['copiesAvailiable'];      
        $copyValue = $_GET['copyValue'];      
    }
?>
