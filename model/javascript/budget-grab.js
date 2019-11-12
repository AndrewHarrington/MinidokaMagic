$(document).ready(function(){
    var pdfFilesDirectory = 'uploads/';

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
            $("#box").append("<li class='row'>" +
                "<a class='col-sm-10' href='budget-view/" + pdfName + "'>" +
                //icon
                "<img src='styles/images/pdf-icon.png' alt='pdf-icon' style='width: 30px; height: 30px;'>" +
                //file name
                pdfName +
                "</a>" +
                //delete button
                "<form method='post' action=''><button class='btn btn-warning' name='file' value='" + pdfName + "' type='submit'>Remove</button></form>" +
                "</li>");
        })
    });
});