$(document).ready(function () {
    FormPersona.Init("save");
    FormPersona.callBack = function(data){
        location.reload()
    }
    $(".sel_all").click(function () {
        var estado = $(".sel_all").prop('checked');
        $(".sel").prop('checked', estado);
    });

    $('[name=opt_ruc]').mask('00000000000');

    FormPersona.Init();

    $(function () {
        $("[data_toggle='tooltip']").tooltip();
    })

    if([undefined,null].indexOf(_cuentas) < 0){
        indexCuentas.init(_cuentas);
    }
    ModalFusionarEmpresas.init("CUENTAS");
});

function editContacto() {
    var contacto_id = $("[name=contacto_id]").val();
    $("#viewModal").modal('hide');
    FormPersona.Editar(contacto_id);
}

function nuevoContacto(e, cliente_id) {
    $("#contactoModal").modal('show');
    $("[name=dni]").attr("readonly", false).val("")
    $("[name=nombres]").attr("readonly", false).val("")
    $("[name=apellidos]").attr("readonly", false).val("")
    $("[name=cargo_empresa]").attr("readonly", false).val("")
    $("[name=telefono]").attr("readonly", false).val("")
    $("[name=correo]").attr("readonly", false).val("")
    $("[name=cliente_id]").val(cliente_id)
    $("[name=contacto_id]").val(0)
    e.preventDefault()
}

function guardarContacto() {
    var cc = $("#contactoModal form").serializeObject();
    cc.cliente_id = $("[name=cliente_id]").val();
    cc.contacto_id = $("[name=contacto_id]").val();

    $.ajax({
        url: base + "cuentas/contacto-guardar",
        data: cc,
        type: 'POST',
        dataType:   'JSON',
        success: function (data, status, xhr) {// success callback function
            console.log(data)
            alert("Se ha guardado con éxito.")
            window.location.reload(true)
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);

        }
    });
    $("#contactoModal").modal("hide");
}

function getContacto(contacto_id, cliente_id) {
    $("[name=contacto_id]").val(contacto_id);
    $("[name=cliente_id]").val(cliente_id);
    console.log(contacto_id)
    $("#viewModal").modal("show");
    $.ajax({
        url: base + "cuentas/find-contacto-by-id",
        data: {"id": contacto_id},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            $("#dni_").text(data.dni)
            $("#nombre_").text(data.nombres)
            $("#apellido_").text(data.apellidos)
            $("#cargo_").text(data.cargo_empresa)
            $("#tel_").text(data.telefono)
            $("#correo_").text(data.correo)
            $("#domicilio_").text(data.domicilio)
            $("#whatsapp_").text(data.whatsapp)
            $("#cel_").text(data.celular_personal)
            $("#cel2_").text(data.celular_trabajo)
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });

}

function borrarSeleccionados() {
    var ids = new Array()

    $(".sel:checked").each(function(i,c){
        ids.push(this.value);
    });

    console.log(ids);

    var r = confirm("¿Realmente desea eliminar los registros seleccionados?");
    if(!r){return false;}

    $.ajax({
        url: base + "cuentas/delete-all",
        data: {
            'ids':ids,
        },
        type: 'POST',
        //dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            console.log(data);
            //alert(data.data);
            location.href = base + "cuentas";
        }, error: function (xhr, ajaxOptions, thrownError) {
            //alert(xhr);
            return false;
        }
    });
}


var indexCuentas = {
    arrayCuentas : [],
    init : (cuentas) =>    {
        indexCuentas.arrayCuentas = cuentas;  
    },
    fusionarCuentas    :   ()  =>  {
        var ids = new Array()
        const arrayCuentas =[]
        $(".sel:checked").each(function(i,c){
            ids.push(this.value.toString());
        });
        if(ids.length != 2){
            alert('Debe seleccionar 2 clientes');
            return;
        }
        console.log(indexCuentas.arrayCuentas);
        console.log(ids);
        indexCuentas.arrayCuentas.forEach((c)   =>  {
            if(ids.indexOf(c.id.toString()) > -1){
                arrayCuentas.push(c);
            }
        })

        ModalFusionarEmpresas.open(arrayCuentas);
    
    }
}
