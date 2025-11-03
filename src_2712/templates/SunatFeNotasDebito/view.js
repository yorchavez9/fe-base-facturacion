$(document).ready(function(){
    NotaDebitoDetalle.getData(nota_id)
})


var NotaDebitoDetalle = {
    getData     : function(id){
        var endpoint = `${API_FE}/sunat-fe-notas-debito/single/${id}`;
        $.ajax({
            url     :   endpoint,
            data    :   '',
            type    :   'GET',

            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                if(data.success){
                    NotaDebitoDetalle.insertData(data.data)
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
    insertData     : function(data){
        var str = `
            <tr>
                <th colspan="100%" class="text-center"> Nota de Debito: ${data.serie}-${data.correlativo}  </th>
            </tr>
            <tr>
                <th> Tipo de Documento </th>
                <td colspan="2">  ${data.tipo_doc} </td>
                <th> Fecha emision </th>
                <td colspan="4"> ${data.fecha_emision} </td>
            </tr>
            <tr>
                <th colspan="2"> Doc. Afectado </th>
                <td colspan="4" class="text-center"> ${data.num_doc_afectado} </td>
            </tr>
            <tr>
                <th> Mto Oper Grav </th>
                <td> ${data.mto_oper_gravadas} </td>
                <th> Mto Igv </th>
                <td> ${data.mto_igv} </td>
                <th> Total </th>
                <td> ${data.mto_imp_venta} </td>
            </tr>
            <tr>
                <th colspan="100%" class="text-center"> Datos del Emisor </th>
            </tr>                            
            <tr>
                <th> RUC </th>
                <td> ${data.emisor_ruc} </td>
                <th> Raz. Social </th>
                <td> ${data.emisor_razon_social} </td>
                <th> Estado del documento </th>
                <td> ${data.sunat_estado} </td>
            </tr>
            <tr>
                <th colspan="100%" class="text-center">Datos del Cliente </td>
            </tr>                            
            <tr>
                <th> RUC </th>
                <td> ${data.cliente_doc_numero} </td>
                <th> Raz. Social </th>
                <td> ${data.cliente_razon_social} </td>
                <th> Motivo del Doc. </th>
                <td> ${data.descripcion_motivo} </td>
            </tr>
        `;
        $("#tabla_notas").html(str);

        var arr_items = JSON.parse(data.items)
        var str_items = '';
        arr_items.forEach(e => {
            str_items += `
                <tr>
                    <td> ${e.item_nombre} </td>
                    <td> ${e.item_unidad} </td>
                    <td> ${e.cantidad} </td>
                </tr>
            `;
        });

        $("#tabla_items").html(str_items);
        var str_links = `
        Opciones :
        <a class='' href="${API_FE}/sunat-fe-notas-credito/descargar-pdf/${data.id}/a4" target="_blank"> <i class='fa fa-file-pdf fa-fw'></i> Imprimir A4 </a> | 
        <a class='' href="${API_FE}/sunat-fe-notas-credito/descargar-pdf/${data.id}/ticket" target="_blank"> <i class='fa fa-envelope fa-fw'></i> Imprimir Ticket </a> | 
        <a class='' href="${API_FE}/sunat-fe-notas-credito/descargar-cdr/${data.id}" target="_blank"> <i class='fas fa-file-archive fa-fw'></i> Descargar CDR </a> | 
        <a class='' href="${API_FE}/sunat-fe-notas-credito/descargar-xml/${data.id}" target="_blank"> <i class='fas fa-code fa-fw'></i> Descargar XML </a> 
        `
        $("#div_links").html(str_links);

    }
}
