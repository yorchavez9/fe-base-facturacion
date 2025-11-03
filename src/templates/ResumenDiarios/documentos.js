$(document).ready(function(){
    // $("#btnDescargar").click(function(e){
    //     e.preventDefault()
    //     ModalDescargarEnviar.Abrir(cpe_tipo,cpe_id);
    // })
    $("#modalResumenDiarioForm [name='fecha_generacion']").datepicker({
        format: 'yyyy-mm-dd',
        locale: 'es-es',
        uiLibrary: 'bootstrap4'
    });
    DetallesRes.getResumen();

})

var DetallesRes = {
    getResumen: function () {
        var endpoint = `${API_FE}/sunat-fe-resumen-diarios/get-one/${resumen_id}`;
        $.ajax({
            url     :   endpoint,
            data    :   '',
            type    :   'GET',

            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                var str = '';
                if(data.success) {
                    str += `
                    <tr>
                        <td colspan="4" class="text-center font-weight-bold">
                            <h5>RESUMEN DIARIO NRO ${ (data.data.id).toString().padStart(7,"0") }</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>EMISOR</td>
                        <td> ${data.data.emisor_razon_social} </td>
                        <td>RUC</td>
                        <td>${data.data.emisor_ruc} </td>
                    </tr>
                    <tr>
                        <td>NOMBRE COMERCIAL</td>
                        <td> ${data.data.emisor_nombre_comercial} </td>
                        <td>ESTADO</td>
                        <td> ${data.data.estado} </td>
                    </tr>
                    <tr>
                        <td>F. GENERACIÓN</td>
                        <td> ${data.data.fecha_generacion} </td>
                        <td>F. RESUMEN</td>
                        <td> ${data.data.fecha_resumen} </td>
                    </tr>
                    `

                    var strBoletas = '';
                    var strNotas = '';
                    data.data.sunat_fe_facturas.forEach(e => {
                        strBoletas += `
                        <tr>
                            <td> ${ (e.id).toString().padStart(7,"0") } </td>
                            <td>
                                <i class="fa fa-fw fa-building"></i> ${e.emisor_nombre_comercial} <br>
                                <i class="fa fa-fw fa-user-check"></i> ${e.emisor_razon_social} <br>
                                <i class="fa fa-fw fa-id-card"></i> ${e.emisor_ruc} 
                            </td>
                            <td>
                                <i class="fa fa-fw fa-user-check"></i> ${e.cliente_razon_social} <br>
                                <i class="fa fa-fw fa-id-card"></i> ${e.cliente_num_doc}
                            </td>
                            <td>
                                <i class="fa fa-fw fa-file"></i> ${e.serie}-${e.correlativo} <br>
                                <i class="fa fa-fw fa-calendar"></i> ${e.fecha_emision}
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-5 text-right">
                                        IGV:
                                    </div>
                                    <div class="col-7 text-right">
                                        ${e.tipo_moneda == "PEN" ? 'S/.' : '$'} ${e.mto_igv}
                                    </div>
                                    <div class="col-5 text-right">
                                        Subtotal:
                                    </div>
                                    <div class="col-7 text-right">
                                        ${e.tipo_moneda == "PEN" ? 'S/.' : '$'} ${e.mto_oper_gravadas}
                                    </div>
                                    <div class="col-5 text-right">
                                        Total:
                                    </div>
                                    <div class="col-7 text-right">
                                        ${e.tipo_moneda == "PEN" ? 'S/.' : '$'} ${e.mto_imp_venta}
                                    </div>
                                </div>
                            </td>
                            <td class="actions">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class='dropdown-item' href="${API_FE}/ventas/detalles/${e.venta_id}">
                                            <i class='fa fa-search fa-fw'></i> Detalle Venta
                                        </a>
                                        <a class='dropdown-item' href="${API_FE}/sunat-fe-facturas/descargarPdf/${e.id}/a4" target="_blank">
                                            <i class='fas fa-print fa-fw'></i> Imprimir A4
                                        </a>
                                        <a class='dropdown-item' href="${API_FE}/sunat-fe-facturas/descargarPdf/${e.id}/ticket" target="_blank">
                                            <i class='fas fa-print fa-fw'></i> Imprimir Ticket
                                        </a>
                                        <a class='dropdown-item' href="${API_FE}/sunat-fe-facturas/descargar-cdr/${e.id}" target="_blank">
                                            <i class='fas fa-code fa-fw'></i> Descargar XML
                                        </a>
                                        <a class='dropdown-item' href="${API_FE}/sunat-fe-facturas/descargar-xml/${e.id}" target="_blank">
                                            <i class='fas fa-code fa-fw'></i> Descargar CDR
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        `
                    });
                    data.data.sunat_fe_notas.forEach(e => {

                        var str_ncredito ='';
                        var str_ndebito ='';

                        
                        strNotas += `
                        <tr>
                            <td>
                                ${ (e.id).toString().padStart(7,"0") }
                            </td>
                            <td>
                                <i class="fa fa-fw fa-user-check"></i> ${e.emisor_razon_social} <br>
                                <i class="fa fa-fw fa-id-card"></i> ${e.emisor_ruc}
                            </td>
                            <td>
                                <i class="fa fa-fw fa-user-check"></i> ${e.cliente_razon_social} <br>
                                <i class="fa fa-fw fa-id-card"></i> ${e.cliente_doc_numero}
                            </td>
                            <td>
                                <i class="fa fa-fw fa-file"></i> ${e.serie}-${e.correlativo} <br>
                                <i class="fa fa-fw fa-calendar"></i>  ${e.fecha_emision}
                                <br> Doc. Afectado:
                                ${e.tipo_doc_afectado == '01' ? 'Factura': 'Boleta'}
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-5 text-right">
                                        IGV:
                                    </div>
                                    <div class="col-7 text-right">
                                        ${e.tipo_moneda == "PEN" ? 'S/.' : '$'} ${e.mto_igv}
                                    </div>
                                    <div class="col-5 text-right">
                                        Subtotal:
                                    </div>
                                    <div class="col-7 text-right">
                                        ${e.tipo_moneda == "PEN" ? 'S/.' : '$'} ${e.mto_oper_gravadas}

                                    </div>
                                    <div class="col-5 text-right">
                                        Total:
                                    </div>
                                    <div class="col-7 text-right">
                                        ${e.tipo_moneda == "PEN" ? 'S/.' : '$'} ${e.mto_imp_venta}
                                    </div>
                                </div>
                            </td>
                            <td class="actions">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        `;
                                        if(e.tipo_doc == '07'){
                                            str += `
                                                <a class='dropdown-item' href="#">
                                                    <i class='fa fa-search fa-fw'></i> Ver
                                                </a>
                
                                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-credito/descargar-xml/${e.id}">
                                                    <i class='fa fa-search fa-fw'></i> Descargar XML
                                                </a>
                
                                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-credito/descargar-cdr/${e.id}">
                                                    <i class='fa fa-search fa-fw'></i> Descargar CDR
                                                </a>
                                            `;
                                        }else if(e.tipo_doc == '08'){
                                            str += `
                                                <a class='dropdown-item' href="#">
                                                    <i class='fa fa-search fa-fw'></i> Ver
                                                </a>
                                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-debito/descargar-xml/${e.id}">
                                                    <i class='fa fa-search fa-fw'></i> Descargar XML
                                                </a>
                                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-debito/descargar-cdr/${e.id}">
                                                    <i class='fa fa-search fa-fw'></i> Descargar CDR
                                                </a>
                                            `;
                                        }
                                        str +=`
                                    </div>
                                </div>
                            </td>
                        </tr>
                        `
                    });
                   
                    
                }
                $("#tabla_resumen").html(str)
                $("#tabla_boletas").html(strBoletas)
                $("#tabla_notas").html(strNotas)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
}
function limpiarFiltroBoletas(){
    $("[name=opt_num_boleta]").val('')
    $("#form_boletas").submit()

}
function limpiarFiltroNotas(){
    $("[name=opt_num_nota]").val('')
    $("#form_notas").submit()

}