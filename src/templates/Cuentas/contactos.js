$(document).ready(() => {
    FormPersona.Init("save");
    FormPersona.callBack = function(data){
        location.reload()
    }

    $("#resultado").richText();

    $("#checkAll").click(function () {
        var estado = $("#checkAll").prop("checked");
        $(".sel").prop("checked", estado)
    })


    $("#btnNuevo").on("click", function(e){
        e.preventDefault()
        FormPersona.NuevoParaCuenta(cuenta_id);
    })

    $("#claveGuardar").click(function (){
        var cc = $("#modalClave form").serializeObject();
        claveGuardar(cc);
        $("#modalClave").modal("hide");

    });



});

function editarPersona(id){
    FormPersona.Editar(id);
}

function limpiar() {
    $("#dni").val("");
    $("#nombre").val("");
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
            console.log(data);
            alert(data.data);
            location.reload()
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr);
            return false;
        }
    });

}

// clave

function claveSetDataInForm(json){
    $("#modalClave [name=clave]").val(json.clave);
    $("#modalClave [name=id]").val(json.id);
}

function editarClave(clave_id){
    var url = base + "Personas/clave-get";
    var obj = $("#modalClave form").serializeObject();
    obj.id = clave_id;
    console.log(obj);
    $.ajax({
        url     :   url,
        data    :   obj,
        //processData: false,
        type    :   'POST',
        dataType:   'JSON',
        success :   function (data, status, xhr) {// success callback function
            $("#modalClave").modal("show");
            claveSetDataInForm(data);

        },error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}



function claveGuardar(cc){
    $.ajax({
        url     :   base + "personas/clave-guardar",
        data    :   cc,
        type    :   'POST',
        // dataType:   'JSON',
        success :   function (data, status, xhr) {// success callback function
            location.reload();
        },error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function eliminar (persona_id){
    var r = confirm("¿Realmente desea eliminar el registro?");
    if(!r){return false;}

    $.ajax({
        url: base + "personas/delete-all",
        data: {
            'ids':[persona_id],
        },
        type: 'POST',
        //dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            console.log(data);
            location.reload()
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

