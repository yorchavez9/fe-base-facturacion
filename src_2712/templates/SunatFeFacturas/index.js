$(document).ready(function(){   
    Facturas.getData(0);
})
var Facturas = {
    getData :   function( page = 0) {
        var endpoint = `${API_FE}/sunat-fe-facturas?page=${page == 0 ? '' : page}}`;

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

                    $("#tabla_facturas").html(str)

                    Facturas.generarPaginacion(data.paginate)
                   
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    generarPaginacion : function ( data ) {
        var str = `<ul class="pagination">
            <li class="page-item ${data.prev_page ? '': 'disabled' }">
                <a class="page-link" href="javascript:void(0)" onclick="Facturas.getData(1)">
                    
                    <i class="fas fa-angle-double-left"></i> Primero
                </a>
            </li>
            <li class="page-item  ${data.prev_page ? '': 'disabled' }">
                <a class="page-link" href="javascript:void(0)" ${data.prev_page ? "onclick='Facturas.getData(" + (Number.parseInt(data.page) - 1) +")'" : "" }">
                    <i class="fas fa-angle-double-left"></i> Anterior
                </a>
            </li>
            <li class="page-item  ${data.next_page ? '': 'disabled' } ">
                <a class="page-link" href="javascript:void(0)" ${data.next_page ? "onclick='Facturas.getData(" + (Number.parseInt(data.page) + 1) +")'" : "" }">
                    Siguiente <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <li class="page-item ${data.next_page ? '': 'disabled' } ">
                <a class="page-link" href="javascript:void(0)" onclick="Facturas.getData(${data.total_page})">
                    Último <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        </ul>`;
        $("#me_pagination").html(str)
    }
}