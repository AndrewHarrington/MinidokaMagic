function validateForm() {
    let x = document.forms["participant"]["fname"].value;
    if (x === "") {
        alert("First name must be filled out.");
        return false;
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
}