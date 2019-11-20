$(document).ready(function(){
    $("#hasHotel").click(function(){
        if($("#hasHotel").is(":checked")){
            $("#res").attr("hidden", false);
        }
        else{
            $("#res").attr("hidden", true);
        }
    });
});

function validateUserForm() {
    let x = document.forms["user"]["fname"].value;
    if (x === "") {
        alert("First name must be filled out.");
        return false;
    }
    x = document.forms["user"]["lname"].value;
    if(x === ""){
        alert("Last name must be filled out.");
    }
    x = document.forms["user"]["email"].value;
    if(x === ""){
        alert("Email must be entered.");
    }
    x = document.forms["user"]["phone"].value;
    if(x === ""){
        alert("Phone number must be filled out.");
    }
}

function validateForm() {
    let x = document.forms["participant"]["fname"].value;
    if (x === "") {
        alert("First name must be filled out.");
    }
    x = document.forms["participant"]["lname"].value;
    if(x === ""){
        alert("Last name must be filled out.");
    }
    x = document.forms["participant"]["email"].value;
    if(x === ""){
        alert("Email must be entered.");
    }
    x = document.forms["participant"]["phone"].value;
    if(x === ""){
        alert("Phone number must be filled out.");
    }
    x = document.forms["participant"]["emergency"].value;
    if(x === ""){
        alert("Emergency phone number must be filled out.");
    }
    alert("User successfully added!")
}