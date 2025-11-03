$(document).ready(function(){
    $("#btnDescargar").click(function(e){
        e.preventDefault()
        ModalDescargarEnviar.Abrir(cpe_tipo,cpe_id);
    })
    VentaDetalle.getFacturas();
})


var VentaDetalle = {

    consultarValidezCpe  : function (element){

        // TODO poner animacion de "enviando"
        var factura_id = $(element).attr("data-comprobante-id");

        var link_icono = $(element).find("i")[0];
        VentaDetalle.setLinkLoad(link_icono,element,"fa-exchange-alt");


        var url = `${base}sunat-fe-facturas/api-consulta-validez-cpe/${factura_id}`;
        $.ajax({
            url     :   url,
            data    :   '',
            type    :   'POST',
            success :   function (data, status, xhr) {

                alert(data.message);
                VentaDetalle.stopLinkLoad(link_icono,element,"fa-exchange-alt");

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);

                VentaDetalle.stopLinkLoad(link_icono,element,"fa-exchange-alt");

            }

        });
    },
    setLinkLoad   :   function(element,element_parent,class_to_replace)
    {
        $(element).removeClass(`${class_to_replace}`);
        $(element).addClass('spinner-border');
        $(element).addClass('spinner-border-sm');
        $(element_parent).css("pointer-events","none");
    },
    stopLinkLoad      :   function(element,element_parent,class_to_set)
    {
        $(element).removeClass('spinner-border');
        $(element).removeClass('spinner-border-sm');
        $(element).addClass(`${class_to_set}`);
        $(element_parent).css("pointer-events","auto");
    },
    Imprimir: function (formato , venta_id) {
        var endpoint = `${base}sunat-fe-facturas/api-imprimir-pdf/${venta_id}/${formato}`;
        $.ajax({
            url     :   endpoint,
            data    :   '',
            type    :   'GET',

            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                if(data.success)
                {
                    printJS({printable: data.data, type: 'pdf', base64: true, modalMessage: 'Cargando Documento'});

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
    EnviarSunat: function (id) {
        var endpoint = `${API_FE}/sunat-fe-facturas/api-enviar-sunat`;
        $.ajax({
            url     :   endpoint,
            data    :   { factura_id : id },
            type    :   'POST',

            success :   function (data, status, xhr) {// success callback function
                console.log(data)
                if(data.success)
                {
                    alert(data.message);
                    VentaDetalle.getFacturas();
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
    GenerarXmlEnviar: function (id) {
        var endpoint = `${API_FE}/sunat-fe-facturas/api-enviar-sunat`;
        $.ajax({
            url     :   endpoint,
            data    :   { factura_id : id },
            type    :   'POST',

            success :   function (data, status, xhr) {// success callback function
                console.log(data)
                if(data.success)
                {
                    alert(data.message);
                    VentaDetalle.getFacturas();
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
    getFacturas :   function()
    {
        var endpoint = `${API_FE}/sunat-fe-facturas/get-by-venta/${cpe_id}`;
        $.ajax({
            url     :   endpoint,
            data    :   '',
            type    :   'GET',

            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                if(data.success)
                {
                    var str = '';
                    data.data.forEach(e => {
                        let enviar =``;
                        if(e.sunat_estado == 'ACTIVO'){
                            enviar += `
                            <a style="color: black;" class="dropdown-item" href="javascript:void(0)" onclick="VentaDetalle.GenerarXmlEnviar(${e.id})" >
                                <i class="fas fa-send fa-fw"></i> Volver a generar XML y enviar a sunat
                            </a>
                            <a style="color: black;" class="dropdown-item" href="javascript:void(0)" onclick="VentaDetalle.EnviarSunat(${e.id})" >
                                <i class="fas fa-send fa-fw"></i> Volver a enviar a sunat
                            </a>
                            `;
                        }
                        let cdr =``;
                        if(e.tipo_doc == '01'){
                            cdr =`
                            <a style="color: black;" class="dropdown-item" href="${API_FE}/sunat-fe-facturas/descargar-cdr/${e.id}" target="_blank" >
                                <i class="fas fa-download fa-fw"></i> Descargar CDR
                            </a>
                        `;
                        }else if(e.tipo_doc == '03' && e.resumen_id != 0 ){
                            cdr =`
                                <a style="color: black;" class="dropdown-item" href="${base}resumen-diarios/detalles/${e.resumen_id}" target="_blank" >
                                    <i class="fas fa-download fa-fw"></i> Ir al resumen diario
                                </a>
                            `;
                        }
                        let notas = '';
                        let estado_factura = '';
                        if( e.sunat_estado == 'ACEPTADO' && e.estado == 'ACTIVO' && e.tipo_doc == '01'){
                            notas += `<a class='dropdown-item' href='${base}sunat-fe-notas-credito/nueva-nota-credito/${e.id}'><i class='fa fa-edit fa-fw'></i> Nota credito - Factura </a>`;
                            notas += `<a class='dropdown-item' href='${base}sunat-fe-notas-debito/nueva-nota-debito/${e.id}'><i class='fa fa-edit fa-fw'></i> Nota Debito - Factura </a>`;
                            notas += `<a class='dropdown-item' href='javascript:void(0)' onclick="VentaDetalle.enviarComunicacionBaja(${e.id})"><i class='fa fa-edit fa-fw'></i> Comunicado Baja - Factura </a>`;
                        }else if ( e.sunat_estado == 'ACEPTADO' && e.estado == 'ACTIVO' && e.tipo_doc == '03' && e.resumen_id != 0) {
                            notas += `<a class='dropdown-item' href='${base}sunat-fe-notas-credito/nueva-nota-credito/${e.id}'><i class='fa fa-edit fa-fw'></i> Nota credito - Boleta </a>`;
                            notas += `<a class='dropdown-item' href='${base}sunat-fe-notas-debito/nueva-nota-debito/${e.id}'><i class='fa fa-edit fa-fw'></i> Nota Debito - Boleta </a>`;
                            notas += `<a class='dropdown-item' href='javascript:void(0)' onclick="VentaDetalle.enviarAnulacionBoleta(${e.id})"><i class='fa fa-edit fa-fw'></i> Anular y Enviar Resumen - Boleta </a>`;
                        }else if (  e.estado == 'ANULADO_RD' ){
                            let link = `${base}resumen-diarios/documentos/${e.sunat_fe_resumen_diario ? e.sunat_fe_resumen_diario.id : 0 }`
                            notas += `<a class='dropdown-item' href='${link}' onclick=""><i class='fa fa-eye fa-fw'></i> Anulado, Ver Resumen diario </a>`;
                            estado_factura += `ANULADO - Resumen Diario`;
                        }else if (  e.estado == 'ANULADO_CB' ){
                            let link = `${base}sunat-fe-comunicacion-bajas/view/${e.sunat_fe_comunicacion_baja ? e.sunat_fe_comunicacion_baja.id : 0 }`
                            notas += `<a class='dropdown-item' href='${link}' onclick=""><i class='fa fa-eye fa-fw'></i> Ver Comunicado de Baja </a>`;
                            estado_factura += `ANULADO - Comunicado de Baja`;
                        }else if (  e.estado == 'MODIFICADO_NC'  ||  e.estado == 'ANULADO_NC'   ){
                            let link = `${base}sunat-fe-notas-credito/view/${e.sunat_fe_notas_credito ? e.sunat_fe_notas_credito.id : 0 }`
                            notas += `<a class='dropdown-item' href='${link}' onclick=""><i class='fa fa-eye fa-fw'></i> Ver Nota de Crédito </a>`;
                            estado_factura += `${e.estado == 'MODIFICADO_NC' ? 'MODIFICADO' : 'ANULADO'} - Nota de Crédito`;
                        }else if (  e.estado == 'MODIFICADO_ND'){
                            let link = `${base}sunat-fe-notas-debito/view/${e.sunat_fe_notas_debito ? e.sunat_fe_notas_debito.id : 0 }`
                            notas += `<a class='dropdown-item' href='${link}' onclick=""><i class='fa fa-eye fa-fw'></i> Ver Nota de Débito </a>`;
                            estado_factura += `${e.estado == 'MODIFICADO_ND' ? 'MODIFICADO' : 'ANULADO'} - Nota de Débito`;
                        }
                        str += `
                        <tr>
                            <td> ${e.fecha_emision} </td>
                            <td>
                                DNI/RUC: ${e.cliente_num_doc} <br>
                                ${e.cliente_razon_social} 
                            </td>
                            <td> 
                                ${e.serie}-${e.correlativo}
                                ${estado_factura}
                            </td>
                            <td>
                                TOTAL: ${e.mto_imp_venta} <br>
                                SUBTOTAL: ${e.valor_venta} <br>
                                IGV: ${e.total_impuestos}
                            </td>
                            <td> 
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a style="color: black;" class="dropdown-item" href="javascript:void(0)" onclick="VentaDetalle.enviarCorreoFactura(${e.id})" >
                                            <i class="fas fa-envelope fa-fw"></i> Enviar por correo
                                        </a>
                                        ${enviar}
                                        ${cdr}                                 
                                        <a style="color: black;" class="dropdown-item" href="${API_FE}/sunat-fe-facturas/descargar-xml/${e.id}" target="_blank" >
                                            <i class="fas fa-print fa-fw"></i> Descargar XML
                                        </a>
                                        <a style="color: black;" class="dropdown-item" href="${API_FE}/sunat-fe-facturas/descargar-pdf/${e.id}/a4" target="_blank" >
                                            <i class="fas fa-print fa-fw"></i> PDF A4
                                        </a>
                                        <a style="color: black;" class="dropdown-item" href="${API_FE}/sunat-fe-facturas/descargar-pdf/${e.id}/ticket"  target="_blank" >
                                            <i class="fas fa-print fa-fw"></i> PDF Ticket
                                        </a>
                                        ${notas}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        `;
                    });

                    $("#table_facturas").html(str)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    enviarCorreoFactura:    function(factura_id) {
        var r = prompt("Por favor, ingrese el motivo de la anulación");
        if (r == null) {
            return false;
        }
        var endpoint = `${API_FE}/sunat-fe-facturas/enviar-correo`;
        $.ajax({
            url:endpoint,
            data: {
                factura_id: factura_id,
                correo: r
            },
            type: 'post',

            success: function(data, status, xhr) { // success callback function
                console.log(data);
                alert(data.message);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    enviarAnulacionBoleta    : function( factura_id ){
        var r = confirm("¿Seguro que desea anular esta boleta y enviarla en un resumen diario?");
        if (r == null){return false;}
        $.ajax({
            url     :   API_FE + "/sunat-fe-facturas/api-anular-boleta/",
            data    :   {
                factura_id : factura_id,
            },
            type    :   'post',

            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                if (data.success){
                    location.reload();
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
    enviarComunicacionBaja    : function( factura_id){
        var r = prompt("Por favor, ingrese el motivo de la anulación");
        if (r == null){return false;}
        $.ajax({
            url     :   API_FE + "/sunat-fe-comunicacion-bajas/enviar/",
            data    :   {
                factura_id : factura_id,
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
}
