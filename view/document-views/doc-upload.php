<include href="view/header.html"/>
<check if="@SESSION.admin == 1">
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
</check>
<input type="hidden" id="isAdmin" value="{{@SESSION.admin}}">
<ul class="container" id="box"></ul>

<script src="model/javascript/reference-grab.js"></script>
</body>
</html>