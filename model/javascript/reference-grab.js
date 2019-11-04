$(document).ready(function(){
    var pdfFilesDirectory = 'reference-docs/';

    // get auto-generated page
    $.ajax({url: pdfFilesDirectory}).then(function(html) {
        // create temporary DOM element
        var document = $(html);
        console.log($(html));
        // find all links ending with .pdf
        //[href$=.pdf]
        document.find('a[href$=pdf]').each(function() {
            var pdfName = $(this).text();


            //ISSUE: PDF File names can not be longer than 20 characters


            //write each element as a clickable image/text
            $("#box").append("<li>" +
                "<a href='doc-view/" + pdfName + "'>" +
                //icon
                "<img src='styles/images/pdf-icon.png' alt='pdf-icon' style='width: 30px; height: 30px;'>" +
                //file name
                pdfName +
                "</a>" +
                //delete button
                "<form method='post' action=''><button name='file' value='" + pdfName + "' type='submit'>X</button></form>" +
                "</li>");
        })
    });
});