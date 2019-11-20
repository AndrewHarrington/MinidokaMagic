<include href="view/header.html"/>
<div class="ml-5">
    <br>
    <h3>Budget Documents</h3>
</div>

<div class="jumbotron" id="capsule">
    <div class="col">
        <check if="@SESSION.admin == 1">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-group col-sm-12">
                    <button class="input-group-text" type="submit" id="submit" name="submit">Upload File</button>
                    <div class="custom-file">
                        <input class="custom-file-input" type="file" name="file" id="file">
                        <label class="custom-file-label" for="file">Select File:</label>
                    </div>

                </div>
            </form>
        </check>

        <input type="hidden" id="isAdmin" value="{{@SESSION.admin}}">
        <ul class="container box" id="boxBudget"></ul>

    </div>
</div>

<footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="model/javascript/budget-grab.js"></script>
</footer>
</body>
</html>