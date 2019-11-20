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

function displayModal(){
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("submit");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        modal.style.display = "block";
    };
    span.onclick = function() {
        modal.style.display = "none";
    };
    // window.onclick = function(event) {
    //     if (event.target == modal) {
    //         modal.style.display = "none";
    //     }
    // }
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