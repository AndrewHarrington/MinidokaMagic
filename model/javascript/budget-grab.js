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

            var toAppend = "<li class='list-group-item'>" +
                "<a class='col-sm-10' href='budget-view/" + pdfName + "'>" +
                //icon
                "<img src='styles/images/pdf-icon.png' alt='pdf-icon' style='width: 30px; height: 30px;'>" +
                //file name
                pdfName +
                "</a>";

            //check to add delete button
            if($("#isAdmin").val() == 1 ){
                toAppend += "<form method='post' class='float-right' action=''><button class='btn btn-warning center' " +
                    "name='file' value='" + pdfName + "' " +
                    "type='submit'><i class='fas fa-trash-alt'></i></button></form>";
            }

            toAppend += "</li>";

            //write each element as a clickable image/text
            $(".box").append(toAppend);
        })
    });
});