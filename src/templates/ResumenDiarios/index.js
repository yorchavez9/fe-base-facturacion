$(document).ready(function(){
    // $("#btnDescargar").click(function(e){
    //     e.preventDefault()
    //     ModalDescargarEnviar.Abrir(cpe_tipo,cpe_id);
    // })
    Resumenes.getResumenes();

    

    $("#modalResumenDiarioForm [name='fecha_generacion']").datepicker({
        format: 'yyyy-mm-dd',
        locale: 'es-es',
        uiLibrary: 'bootstrap4'
    });
})
var Resumenes = {
    add : function (){
        $("#modalResumenDiario").modal('show')
    },
    getResumenes :   function() {
        var endpoint = `${API_FE}/sunat-fe-resumen-diarios`;
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
                        str += `
                        <tr style="${ e.estado == 'ELIMINADO' ? 'background-color:#F5B7B1' : '' }">
                            <td>
                                ${e.nombre_unico}
                                <small>
                                    <i class="fas fa-id-badge fa-fw"></i> <br>
                                    ${ (e.id).toString().padStart(7,0) }
                                </small>
                            </td>
                            <td>
                                <i class="fas fa-calendar fa-fw"></i> ${e.fecha_generacion}
                            </td>
                            <td>
                                <i class="fas fa-calendar fa-fw"></i> ${e.fecha_resumen}
                            </td>
                            <td> ${e.ticket} </td>
                            <td>
                                ${(e.estado == 'ERROR') ? e.sunat_err_code + " : " + e.sunat_err_message : e.estado}
                                <span id="icon_${e.id}"></span>                            
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a href="javascript:void(0);" class="dropdown-item" onclick="Resumenes.consultarTicket(${e.id},'icon_${e.id}')">
                                            <i class="fas fa-exchange-alt fa-fw"></i> Consultar Ticket
                                        </a>
                                        <a class="dropdown-item" href="javascript:Resumenes.VerDescargar(${e.id})">
                                            <i class="fa fa-fw fa-eye"></i> Ver y Descargar CDR
                                        </a>
                                        <a style="color: black;" class="dropdown-item" href="${API_FE}/sunat-fe-resumen-diarios/descargar-xml/${e.id}" target="_blank" >
                                            <i class="fas fa-download fa-fw"></i> Descargar XML
                                        </a>
                                        <a style="color: black;" class="dropdown-item" href="${base}resumen-diarios/documentos/${e.id}" >
                                            <i class="fas fa-list fa-fw"></i> Detalles y Documentos
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        `
                    });

                    $("#tabla_resumenes").html(str)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    generarResumen      : function(){
        var fecha = $("#modalResumenDiarioForm [name='fecha_generacion']").val();
        var url = `${API_FE}/sunat-fe-resumen-diarios/api-generar-resumen/${fecha}`;
        $.ajax({
            url: url,
            data: {       },
            type: 'get',
            success: function (data, status, xhr) {// success callback function
                console.log(data)
                if(data.success){
                    location.reload();
                }else{
                    alert(data.message)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }

        });

        return false;
    },
    consultarTicket : function(rid, target = ''){
        $(`#${target}`).html('<i class="fa-spin fa fa-spinner"></i>')
        var url = `${API_FE}/sunat-fe-resumen-diarios/consultar-ticket/${rid}`;
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data, status, xhr) {                
                if(data.success){
                    location.reload()
                }
                console.log(data)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: () =>{
                $(`#${target}`).html('')
            }
        });
    },
    VerDescargar    :function (resumen_id){
        var url = `${API_FE}/sunat-fe-resumen-diarios/get-one-cdr/${resumen_id}`;

        $.ajax({
            url: url,
            type: 'GET',
            data: '',
            success: function (r) {
                console.log(r);
                if (r.success) {
                    $('#modalVerDescargar').modal('show');
                    $('[name=res_id]').val(resumen_id);
                    $('#nombre_unico').html(r.data.nombre_unico);
                    $('#codigo_respuesta').html(r.data.sunat_cdr_code);
                    $('#descripcion').html(r.data.sunat_cdr_description);
                    $('#notas').html(r.data.sunat_cdr_notes);
                }else{
                    alert("No existe el CDR de este Comprobante.")
                    $('#modalVerDescargar').modal('hide');
                }
            }
        });
    },
    descargarCDR : function () {
        var id = $('[name=res_id]').val();
        window.open(
            API_FE + "/sunat-fe-resumen-diarios/descargar-cdr/"+id,
            '_blank'
          );
    },
}