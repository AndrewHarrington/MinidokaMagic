<include href='view/sub-dir-header.html'/>

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