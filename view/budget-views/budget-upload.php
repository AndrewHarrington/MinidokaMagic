<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $file_type=$_FILES['file']['type'];

    //validate that the file is a pdf
    if(isset($_POST["submit"])) {

        if($file_type=="application/pdf") {
            //echo "File is a PDF.";
            $uploadOk = 1;
        } else {
            echo "File is not a PDF.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        //save the pdf to an "uploads" folder
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            //reroute to a master page to select a pdf to view
            header("Location: budget-master-view.html");
            //echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Budget</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="file">Select File:</label>
        <input type="file" name="file" id="file">
        <button type="submit" id="submit" name="submit">Upload File</button>
    </form>
</body>
</html>