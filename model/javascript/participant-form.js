$(document).ready(function(){
    $("#resCheck").click(function(){
        if($("#resCheck").is(":checked")){
            $("#res").attr("hidden", false);
        }
        else{
            $("#res").attr("hidden", true);
        }
    });
});

// $(document).ready(function(){
//     $("#modal").click(function(){
//         if($("#submit").is(":clicked")){
//             $("#modal").attr("hidden", false);
//         }
//         else{
//             $("#res").attr("hidden", true);
//         }
//     });
// });

function validateForm() {
    var x = document.forms["participant"]["fname"].value;
    if (x == "") {
        alert("First name must be filled out.");
        return false;
    }
    x = document.forms["participant"]["lname"].value;
    if(x == ""){
        alert("Last name must be filled out.");
    }
    x = document.forms["participant"]["email"].value;
    if(x == ""){
        alert("Last name must be filled out.");
    }
    x = document.forms["participant"]["phone"].value;
    if(x == ""){
        alert("Last name must be filled out.");
    }
    x = document.forms["participant"]["ephone"].value;
    if(x == ""){
        alert("Last name must be filled out.");
    }
}