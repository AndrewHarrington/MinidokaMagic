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