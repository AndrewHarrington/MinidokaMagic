<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Minidoka</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
<header>

    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <a class="navbar-brand" href="../{{'registrations'}}">
            <img src="../styles/images/MinidokaLogoWhite.png" alt="Minidoka-Pilgramage">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <div class="bg-dark p-4">

                <ul id="navlist" class="navbar-nav justify-content-center">
                    <li class="nav-item border border-dark" >
                        <a class="nav-link" href="{{'registrations'}}">Home</a>
                    </li>
                    <li class="nav-item border border-dark">
                        <a class="nav-link" href="../{{'budget-pdf'}}">Budget</a>
                    </li>
                    <li class="nav-item border border-dark">
                        <a class="nav-link" href="../{{'new-participant'}}">Add Participant</a>
                    </li>
                    <li class="nav-item border border-dark">
                        <a class="nav-link" href="../{{'volunteers'}}">Volunteers</a>
                    </li>

                    <li class="nav-item border border-dark">
                        <a class="nav-link" href="../{{'add-volunteer'}}">Add Volunteer</a>
                    </li>

<!--                    <li class="nav-item border border-dark">-->
<!--                        <a class="nav-link" href="#">Scholarships</a>-->
<!--                    </li>-->
                    <li class="nav-item border border-dark">
                        <a class="nav-link" href="../{{'reference-pdf'}}">Reference Documents</a>
                    </li>
                    <li class="nav-item border border-dark">
                        <a class="nav-link" href="../{{logout}}">Logout</a>
                    </li>
                </ul>
            </div>
        </div>

    </nav>

</header>

    <div id="pdfFile">
        <!--Content appears here-->
    </div>

<footer>

    <script>

        function getWindowSize(x) {
            //cross browser support for varying size of pdf viewer
            let width = (window.innerWidth
                || document.documentElement.clientWidth
                || document.body.clientWidth) - 20;
            let height = (window.innerHeight
                || document.documentElement.clientHeight
                || document.body.clientHeight);

            //grab our div to print
            let pdfView = document.getElementById(x);

            //print window to given size
            pdfView.innerHTML = '<embed src="../uploads/{{@PARAMS.fileName}}" width= "' + width + '" height= "' + height + '">';
        }

        getWindowSize('pdfFile');

    </script>
</footer>
</body>
</html>