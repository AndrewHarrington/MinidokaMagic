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
        alert("Please enter a valid email");
    }
    x = document.forms["user"]["phone"].value;
    if(x === ""){
        alert("Phone number must be filled out.");
    }
}

function validateParticipantForm() {
    let fname = document.forms["participant"]["fname"].value;
    if (fname === "") {
        alert("First name must be filled out.");
    }
    let lname = document.forms["participant"]["lname"].value;
    if(lname === ""){
        alert("Last name must be filled out.");
    }
    let email = document.forms["participant"]["email"].value;
    if(email === ""){
        alert("Please enter a valid email address");
    }
    let phone = document.forms["participant"]["phone"].value;
    if(phone === ""){
        alert("Phone number must be filled out.");
    }
    let emergency = document.forms["participant"]["emergency"].value;
    if(emergency === ""){
        alert("Emergency phone number must be filled out.");
    }
    if(fname !== "" && lname !== "" && email!=="" && phone!=="" && emergency!=="")
    {
        alert("User successfully added!")
    }

}