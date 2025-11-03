$(document).ready(function(){

    $("#filtroPermisos").on("keyup", function () {
        var ttt = $("#filtroPermisos").val();
        if (ttt == '') {
            $(".filtrar").css({"display": "block"})
        } else {
            $(".filtrar").css({"display": "none"})
            $(`.filtrar:containsNC(${ttt})`).css("display", "block");
        }
    })
    $("#check_gral").change(function () {
        if ($(this).prop("checked")) {
            $("#list_perfiles").hide();
        }else{
            $("#list_perfiles").show();
        }
    })

});
