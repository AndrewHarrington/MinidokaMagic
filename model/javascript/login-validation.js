$(document).ready(function(){
    $("#login").click(function(){
        validateFields();
    });
});

function validateFields(){
    //check username
    //
    // if(!validString($("#username").text)){
    //     $("#username-invalid").attr("hidden", false);
    // }
    //check password

}

//returns false if the string is invalid
// function validString(string){
//     // if(string.trim() === ""){
//     //     return false;
//     // }
//     //var usernameRegex = /^[a-zA-Z0-9]+$/;
//     var usernameRegex = /^[a-z]+/;
//
//     alert(usernameRegex.test(string));
//     if(usernameRegex.test(string)){
//         alert("true");
//         return false;
//     }
//     else{
//         alert("false");
//     }
//     return true;
// }