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
    })


});

function abrirModalFactura() {
    $('[name=factura]').val('');
    $('#modalFactura').modal('show');
}


function abrirModalAnulacionFactura(venta_id, com_baja, doc) {
    $('#modalAnularFactura').modal('show');
    $('[name=a_venta_id]').val(venta_id);
    $('#f_serie_correlativo').html(doc);

    if (com_baja == 1) {
        $('#btn_com_baja').attr('disabled', false);
    } else {
        $('#btn_com_baja').attr('disabled', true);
    }
}

function abrirModalAnulacionBoleta(venta_id, doc) {
    $('#modalAnularBoleta').modal('show');
    $('[name=boleta_venta_id]').val(venta_id);
    $('#b_serie_correlativo').html(doc);
}

function abrirModalModificacion(venta_id, doc) {
    $('#modalModificar').modal('show');
    $('[name=b_venta_id]').val(venta_id);
    $('#m_serie_correlativo').html(doc);
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


    eliminar_nota_factura: function () {
        var venta_id = $('[name=a_venta_id]').val();
        location.href = base + "sunat-fe-notas-credito/nota-credito-anular/" + venta_id;
    },

    eliminar_nota_boleta: function () {
        var venta_id = $('[name=boleta_venta_id]').val();
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

    enviarComunicacionBaja    : function(){
        var venta_id = $('[name=a_venta_id]').val();
        var r = prompt("Por favor, ingrese el motivo de la anulación");
        if (r == null){return false;}

        $.ajax({
            url     :   base + "sunat-fe-comunicacion-bajas/enviar/",
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

    enviarAnulacionBoleta    : function(){
        var venta_id = $('[name=boleta_venta_id]').val();

        $.ajax({
            url     :   base + "sunat-fe-facturas/api-anular-boleta/",
            data    :   {
                venta_id : venta_id
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
    consultarCdr    :   function()
    {
        var venta_id = $(element).attr("data-venta-id");

        var link_icono = $(element).find("i")[0];
        MisVentas.setLinkLoad(link_icono,element,"fa-upload");

        var url = `${base}sunat-fe-facturas/consultar-cdr`;
        $.ajax({
            url: url,
            data: {
                venta_id: venta_id
            },
            type: 'POST',
            success: function (data, status, xhr) {
                console.log(data)
                if (data.success) {
                    alert(data.message);

                } else {
                    alert(data.message);
                }

                MisVentas.stopLinkLoad(link_icono,element,"fa-upload");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);

                MisVentas.stopLinkLoad(link_icono,element,"fa-upload");

            }

        });
    },

    generarXML  :   function( data )
    {
        var endpoint = `${API_FE}/sunat-fe-facturas/generar-xml`;
        $.ajax({
            url         :   endpoint,
            data        :   JSON.stringify(data),
            processData : false,
            contentType : "application/json",
            type        :   'POST',
            success:   function(data)
            {
                console.log(data)
                if(data.success){
                    alert(data.message);
                }else{
                    alert(data.message);
                    alert("Ha habido un error al momento de generar el Xml, contactar a soporte")
                }
                // GlobalSpinner.ocultar();
            },
            error:function( xhr , ajaxOptions , thrownError )
            {
                alert(xhr.status);
                alert(thrownError);
                // GlobalSpinner.ocultar();
            }
        })
    },
    getVenta    :   function(venta_id){
        var url = `${base}ventas/get-one/${venta_id}`;

       
        $.ajax({
            url: url,
            type: 'POST',
            success: function (data, status, xhr) {
                console.log(data)
                if (data.success) {
                    var formData = {
                        "establecimiento_id" : venta.almacen_id,
                        "documento_tipo" : venta.documento_tipo,
                        "tipo_operacion" : venta.tipo_operacion,
                        "documento_serie" : venta.documento_serie,
                        "fecha_venta" : venta.fecha_venta,
                        "tipo_moneda" : venta.tipo_moneda,
                        "fecha_vencimiento" : venta.fecha_vencimiento,
                        "forma_pago" : venta.forma_pago,
                        "cod_detraccion" : venta.cod_detraccion,
                        "porcentaje_detraccion" : venta.porcentaje_detraccion,
                        "monto_detraccion" : venta.monto_detraccion,
                        "op_gravadas" : venta.op_gravadas,
                        "op_gratuitas" : venta.op_gratuitas,
                        "op_exoneradas" : venta.op_exoneradas,
                        "op_inafectas" : venta.op_inafectas,
                        "igv_monto" : venta.igv_monto,
                        "total" : venta.total,
                        "monto_credito" : venta.monto_credito,
                        "cuotas" : venta.cuotas,
                        "monto" : venta.monto,
                        "fecha_pago" : venta.fecha_pago,
                        "cliente_doc_tipo" : venta.cliente_doc_tipo,
                        "cliente_doc_numero" : venta.cliente_doc_numero,
                        "cliente_razon_social" : venta.cliente_razon_social,
                        "json_items" : arr_items,
                        "json_pagos" : arr_pagos,
                        "enviar_sunat" : "1"
                    }
                    
                    MisVentas.generarXML(formData)
                } else {
                    alert(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
};
