$(document).ready(function(){
    ModalDescargarEnviar.Init();
    ModalImprimirVenta.Init();

    $("[name=fecha_ini]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
    $("[name=fecha_fin]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });

    $('[name=factura]').mask('AAAA-00000000');

    $(function () {
        $('[data-toggle="popover"]').popover()
    });


});

function abrirModalFactura() {
    $('[name=factura]').val('');
    $('#modalFactura').modal('show');
}

function abrirModalTiposDoc() {
    $('#modalTiposDoc').modal('show');
}

function abrirModalUsuarios() {
    $('#modalUsuarios').modal('show');
}

function abrirModalAnulacion(venta_id, com_baja) {
    $('#modalAnular').modal('show');
    $('[name=a_venta_id]').val(venta_id);

    if (com_baja == 1) {
        $('#btn_com_baja').attr('disabled', false);
    } else {
        $('#btn_com_baja').attr('disabled', true);
    }
}

function abrirModalModificacion(venta_id) {
    $('#modalModificar').modal('show');
    $('[name=b_venta_id]').val(venta_id);
}

var MisVentas = {

    anularNotaVenta         : function (venta_id){
        var r = prompt("Por favor, ingrese el motivo de la anulación");
        if (r == null){return false;}

        $.ajax({
            url     :   base + "ventas/api-anular-nota-venta",
            data    :   {
                venta_id : venta_id,
                motivo : r
            },
            type    :   'post',

            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                if (data.success){
                    location.reload()
                }else{
                    alert(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },



    eliminar    : function(domobj){
        var venta_id = $(domobj).attr("data-venta-id");
        var cliente = $(domobj).attr("data-cliente");
        var r = confirm("¿Seguro que desea eliminar la venta para " +cliente+ "?")
        if(!r){return;}

        var obj = {};
        obj.venta_id = venta_id;
        $.ajax({
            url     :   base + "ventas/estadoPorAnular",
            data    :   obj,
            type    :   'POST',
            dataType:   'JSON',
            success :   function (data, status, xhr) {// success callback functiond
                console.log(data);
                if(data.success){
                    location.reload();
                }
            },error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }

        });
    },

    eliminar_btn    : function(){
        var venta_id = $('[name=a_venta_id]').val();
        var r = confirm("¿Seguro que desea eliminar la venta?")
        if(!r){return;}

        var obj = {};
        obj.venta_id = venta_id;
        $.ajax({
            url     :   base + "ventas/estadoPorAnular",
            data    :   obj,
            type    :   'POST',
            dataType:   'JSON',
            success :   function (data, status, xhr) {// success callback functiond
                console.log(data);
                if(data.success){
                    location.reload();
                }
            },error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }

        });
    },

    eliminar_nota: function () {
        var venta_id = $('[name=a_venta_id]').val();
        location.href = base + "sunat-fe-notas-credito/nota-credito-anular/" + venta_id;
    },

    modificar: function (tipo) {
        var venta_id = $('[name=b_venta_id]').val();
        if (tipo == "credito") {
            location.href = base + "sunat-fe-notas-credito/nota-credito/" + venta_id;
        } else {
            location.href = base + "sunat-fe-notas/nota-debito/" + venta_id;
        }

    },

    verificarCpe    : function(venta_id){
        $.ajax({
            url     :   base + "sunat-fe/comprobar-validez/" + venta_id,
            type    :   'GET',
            success :   function (data, status, xhr) {// success callback function
                alert(data);


            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }

        });
    },

    enviarCorreoCpe   : function (venta_id){
        // abrimos el modal
        $("#modalEnviarCorreo").modal("show");
        $("#modalEnviarCorreo [name=venta_id]").val(venta_id);

        var url = base + "ventas/ajax-get-cliente/" + venta_id;
        $.ajax({
            url     :  url,
            type    :  'GET',
            dataType: 'JSON',
            success :  function (data, status, xhr) {// success callback function
                $("#modalEnviarCorreo [name=cliente_id]").val(data.id);
                $("#modalEnviarCorreo [name=razon_social]").val(data.razon_social);
                $("#modalEnviarCorreo [name=correo]").val(data.correo);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }

        });
    },

    enviarCorreoCpeBtn(){
        var correo = $("#modalEnviarCorreo [name=correo]").val();
        var cliente_id = $("#modalEnviarCorreo [name=cliente_id]").val();
        var venta_id = $("#modalEnviarCorreo [name=venta_id]").val();

        $("#modalEnviarCorreo").modal("hide");
        var url = base + "ventas/ajax-enviar-correo-cpe/" + venta_id;
        $.ajax({
            url     :  url,
            data    :   {
                venta_id : venta_id,
                cliente_id: cliente_id,
                correo   : correo,
            },
            type    :  'GET',
            success :  function (data, status, xhr) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                alert(thrownError);
            }
        });

    }




};
