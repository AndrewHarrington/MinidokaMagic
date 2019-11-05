function validateForm() {
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