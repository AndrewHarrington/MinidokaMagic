$(document).ready(function(){
    $("#login").click(function(){
        validateFields();
    });
});

function validateFields(){
    //check username

    if(!validString($("#username").text)){
        $("#username-invalid").attr("hidden", false);
    }
    //check password

}

//returns false if the string is invalid
function validString(string){
    const check = /[.a-z0-9]/i;

    alert(check.exec(string));
    //check empty
    if(string === ""){
        return false;
    }
    if(check.exec(string)){
        alert("true");
        return false;
    }
    return true;
}