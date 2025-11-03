$(document).ready(() => {
    $("#resultado").richText();

    $("#checkAll").click(function () {
        var estado = $("#checkAll").prop("checked");
        $(".sel").prop("checked", estado)
    })

    $('[name=opt_dni]').mask('00000000');
    $('[name=dni]').mask('00000000');

    $("#globalFormPersonaBtnGuardar").click(function () {

        cc = GlobalFormPersona.getData();
        console.log(cc);
        guardarPersona(cc);
        $("#contactoModal").modal("hide");

    });

    $("#btnNuevo").on("click", function(e){
        e.preventDefault()
        FormPersona.Nuevo();
    })

    $("#claveGuardar").click(function (){
        var cc = $("#modalClave form").serializeObject();
        claveGuardar(cc);
        $("#modalClave").modal("hide");

    });

    FormPersona.Init("save");
    FormPersona.callBack = function(data){
        FormPersona.Cerrar();
        location.reload();
    }

});

function editarPersona(id){
    FormPersona.Editar(id);
}

function limpiar() {
    $("[name=opt_dni]").val("");
    $("[name=opt_nombre]").val("");
    $("[name=opt_apellido]").val("");

    $(`#formFiltroPersonas`).submit();
}


function deleteAll(){
    var ids = new Array()

    $(".sel:checked").each(function(i,c){
        ids.push(this.value);
    });

    console.log(ids);

    var r = confirm("¿Realmente desea eliminar los registros seleccionados?");
    if(!r){return false;}

    $.ajax({
        url: base + "personas/delete-all",
        data: {
            'ids':ids,
        },
        type: 'POST',
        //dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            location.href = base + "personas";
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr);
            return false;
        }
    });

}


function eliminar (persona_id){
    var r = confirm("¿Realmente desea eliminar los registros seleccionados?");
    if(!r){return false;}

    $.ajax({
        url: base + "personas/delete-all",
        data: {
            'ids':[persona_id],
        },
        type: 'POST',
        //dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            location.href = base + "personas";
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr);
            return false;
        }
    });
}

function generarClave(){
    var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789@$";
    var pwd = "";
    for (i=0; i<8; i++) pwd += caracteres.charAt(Math.floor(Math.random()*caracteres.length));
    $("#modalClave [name=clave]").val(pwd);
}