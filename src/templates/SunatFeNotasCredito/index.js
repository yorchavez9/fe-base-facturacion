$(document).ready(function () {
    NotasCredito.getData();   
});
var ListadoNotasCredito = {
    enviarSunat : function(e, element) {
        e.preventDefault();
        var nota_id = $(element).attr("data-id");

        var endpoint = `${base}sunat-fe-notas-credito/api-enviar-sunat/${nota_id}`;

        $.ajax({
            url: endpoint,
            data: {},
            type: 'POST',
            //dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data, status, xhr) {
                console.log(data);
                if(data.success){
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
    }
    ,Imprimir: function (formato , venta_id) {
        var endpoint = `${base}sunat-fe-notas-credito/api-imprimir-pdf/${venta_id}/${formato}`;
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
}
var NotasCredito = {
    getData     : function(){
        var endpoint = `${API_FE}/sunat-fe-notas-credito/`;
        $.ajax({
            url     : endpoint,
            data    : { },
            type    : 'GET',
            //dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data, status, xhr) {
                console.log(data);
                if(data.success){
                    NotasCredito.createTable(data.data)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    createTable     :function (data){
        var str = '';
        
        data.forEach((e, index)=>{
            str += `
                <tr>
                    <td style="width: 120px">
                        <i class="fas fa-calendar-alt fa-fw"></i>
                        <?= ${e.fecha_emision} <br/>
                        <small>
                            <i class="fas fa-id-badge fa-fw"></i>
                            ${ (e.id).toString().padStart(7, "0") }
                        </small>
                    </td>
                    <td> ${e.serie}-${e.correlativo} </td>
                    <td>
                        ${e.tipo_doc_afectado == '01' ? 'FACTURA' : 'BOLETA'}
                    </td>
                    <td>
                        ${e.descripcion_motivo }<br/>

                        `;
                    switch (e.sunat_estado) {
                        case "":
                            
                            str += `                            
                                <a href="javascript:void(0)" data-id="${e.id}" onclick="ListadoNotasCredito.enviarSunat(event,this)">
                                    <i class='fas fa-upload fa-fw '></i> Enviar a Sunat
                                </a>
                            `;
                            break;
                        case "ERROR":
                            str += `
                            <a tabindex='0' style='color: red;text-decoration: none!important;cursor: pointer'
                            data-placement='left'
                            data-toggle='popover'
                            data-trigger='click' title='Error ${e.sunat_err_code} '
                            data-content='${e.sunat_err_message}'>
                            Ver error
                            </a><br>

                            <a href="javascript:void(0)" data-id="${e.id}" onclick="ListadoNotasCredito.enviarSunat(event,this)">
                                <i class='fas fa-upload fa-fw '></i> Enviar a Sunat
                            </a>
                            `
                            break;
                        case "ACEPTADO":
                            str += 'Comprobante Aceptado';
                            break;
                        case "CORREGIR":
                            str += 'Corregir y Volver a Enviar';
                            break;
                        case "RECHAZADO":
                            str += `<b>Comprobante Rechazado</b>:<br/> ${e.sunat_cdr_description}`
                            break;
                    
                        default:
                            break;
                    }
                    
                    str += `</td>
                    <td style="width: 50px" class="text-left"> ${e.tipo_moneda} </td>
                    <td style="width: 100px" class="text-right"> ${e.mto_imp_venta} </td>
                    <td class="actions">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                

                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-credito/descargar-pdf/${e.id}/a4" target="_blank"> <i class='fa fa-file-pdf fa-fw'></i> Imprimir A4 </a>
                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-credito/descargar-pdf/${e.id}/ticket" target="_blank"> <i class='fa fa-envelope fa-fw'></i> Imprimir Ticket </a>
                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-credito/descargar-cdr/${e.id}" target="_blank"> <i class='fas fa-file-archive fa-fw'></i> Descargar CDR </a>
                                <a class='dropdown-item' href="${API_FE}/sunat-fe-notas-credito/descargar-xml/${e.id}" target="_blank"> <i class='fas fa-code fa-fw'></i> Descargar XML </a>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        })

        $('#table_notas').html(str);
    }
}
