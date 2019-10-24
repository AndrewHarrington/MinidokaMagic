<?php

?>
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>Upload Budget</title>-->
<!--</head>-->
<!--<body>-->
<include href="view/header.html"/>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <button class="input-group-text" type="submit" id="submit" name="submit">Upload File</button>
                <div class="custom-file">
                    <input class="custom-file-input" type="file" name="file" id="file">
                    <label class="custom-file-label" for="file">Select File:</label>
                </div>

            </div>
        </form>
    </div>
<ul class="container" id="box"></ul>

<script src="model/javascript/budget-grab.js"></script>
</body>
</html>