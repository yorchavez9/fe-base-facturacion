$(document).ready(function(){
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    indexGuiasRemRemitente.getData()
})

var indexGuiasRemRemitente = {
    getData     :   function(){
        var url = `${API_FE}/sunat-fe-guiarem-remitentes`;
        $.ajax({
            url: url,
            type: 'POST',
            success: function (data, status, xhr) {
                console.log(data)
                if (data.success) {
                    indexGuiasRemRemitente.createTable(data.data);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    createTable     :   function(data){
        var str = '';
        data.forEach(e => {
            let estado = ''

            if(e.estado == "ACEPTADO"  && e.sunat_cdr_code == '0'){
                estado += `<i>ACEPTADO</i>`;
            }else if(e.estado == "ANULADO"){
                estado += '<i class="text-danger">ANULADO</i>'
            }else if(e.estado == "ACTIVO"){
                if(e.sunat_num_ticket == '' || e.sunat_num_ticket == null ){
                    estado += `<a href="javascript:void(0)" data-id="${e.id}" onclick="indexGuiasRemRemitente.enviarSunat(this)">
                        <i class="fa fa-upload fa-fw"></i> Enviar a Sunat
                    </a>`
                }
            }else{
                estado += `
                    <a tabindex="0" style="color: red;
                            text-decoration: none!important;
                            cursor: pointer"  data-placement="left" data-toggle="popover" data-trigger="focus" title="Error ${e.sunat_err_code}" data-content="${e.sunat_err_message}">
                        Ver error sunat
                    </a>
                    <div class="mb-2"></div>
                    <a tabindex="0" class="text-danger" style="
                            text-decoration: none!important;
                            cursor: pointer"  data-placement="left" data-toggle="popover" data-trigger="focus" title="Error ${e.sunat_cdr_code}" data-content="${e.sunat_cdr_description}">
                        Ver error CDR
                    </a>
                `
            }
            let acc = '';
            if(e.sunat_num_ticket == '' || e.sunat_num_ticket == null || e.estado != 'ACEPTADO'){
                acc += `
                <a class='dropdown-item' href='javascript:void(0)' onclick='indexGuiasRemRemitente.generarXml("${e.id}")'>
                    <i class='fas fa-file fa-fw'></i> Volver a generar XML
                </a>
                <a class='dropdown-item' href='javascript:void(0)' onclick='indexGuiasRemRemitente.enviarSunat2("${e.id}")'>
                    <i class='fas fa-paper-plane fa-fw'></i> Enviar a Sunat
                </a>
                `;
            }else{
                acc += `
                <a class='dropdown-item' href='javascript:void(0)' onclick='indexGuiasRemRemitente.obtenerCDR("${e.id}")'> <i class='fas fa-download fa-fw'></i> Obtener CDR </a>
                `;
            }

            if(e.sunat_cdr_description != '' && e.estado == "ACEPTADO" && e.sunat_cdr_code == '0'){
                acc += `
                <a class='dropdown-item' href='${API_FE}/sunat-fe-guiarem-remitentes/descargar-cdr/${e.id}' target="_blank">
                    <i class='fas fa-download fa-fw'></i> Descargar CDR
                </a>
                <a class='dropdown-item' href='${API_FE}/sunat-fe-guiarem-remitentes/descargar-pdf-sunat/${e.id}' target="_blank">
                    <i class='fas fa-download fa-fw'></i> Descargar PDF
                </a>
                `;
            }

            str += `
            <tr>
                <td>
                    <i class="fa fa-fw fa-id-card"></i> ${e.serie}-${ (e.correlativo).toString().padStart(8, "0") } <BR />
                    <i class="fa fa-fw fa-user"></i> ${e.cliente_razon_social} <br>
                    <i class="fa fa-fw fa-calendar  "></i> F. Emision: ${e.fecha_emision}
                </td>
                <td>
                    <i class="fa fa-fw fa-bus"></i> ${ e.modo_traslado == "01" ? "Transporte Público" : "Transporte Privado"} <br>
                    <i class="fa fa-fw fa-calendar-check"></i> Fecha: ${e.fecha_traslado} 
                    <br>
                    <i class="fa fa-fw fa-building"></i> Desde: ${e.partida_direccion}<br>
                    <i class="fa fa-fw fa-building"></i> Hasta: ${e.llegada_direccion}
                </td>
                <td>
                    ${estado}
                </td>
                <td class="actions">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class='dropdown-item' href='${base}/guia-rem-remitentes/view/${e.id}'> <i class='fas fa-eye fa-fw'></i> Detalles </a>
                            <a class='dropdown-item' href='javascript:void(0)' onclick='indexGuiasRemRemitente.abrirModalImprimir("${e.id}")'><i class='fas fa-print fa-fw'></i>  Imprimir PDF - SUNAT </a>
                            <a class='dropdown-item' href='${API_FE}/sunat-fe-guiarem-remitentes/generarPdfA4/${e.id}' ><i class='fas fa-print fa-fw'></i>  Imprimir PDF A4 </a>
                            
                            <a class='dropdown-item' href='${API_FE}/sunat-fe-guiarem-remitentes/descargarXml/${e.id}' target="_blank"> <i class='fas fa-download fa-fw'></i> Descargar XML </a>
                            ${acc}
                        </div>
                    </div>
                </td>
            </tr>
            `
        });
        $("#tabla_guias").html(str)

    },
    enviarSunat     :   function(element){
        var guia_id = $(element).attr("data-id");

        var link_icono = $(element).find("i")[0];
        indexGuiasRemRemitente.setLinkLoad(link_icono,element,"fa-upload");

        var url = `${API_FE}sunat-fe-guiarem-remitentes/enviar-sunat/${guia_id}`;
        $.ajax({
            url: url,
            type: 'POST',
            success: function (data, status, xhr) {
                if (data.success) {
                    location.reload();

                } else {
                    alert(data.data.code);
                    alert(data.data.message);
                }
                indexGuiasRemRemitente.stopLinkLoad(link_icono,element,"fa-upload");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);

                indexGuiasRemRemitente.stopLinkLoad(link_icono,element,"fa-upload");
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
    generarXml  : function(id){
        let r = confirm("Generar un nuevo XML invalidará el XML anterior, utilice esta función solo si el XML anterior se generó con algun error y si aun está dentro del plazo de envío\n¿Desea continuar?");
        if (r){
            $.ajax({
                url: `${API_FE}/sunat-fe-guiarem-remitentes/api-generar-xml/${id}`,
                data: '',
                type: 'POST',
                success: function (r) {
                    console.log(r);
                    location.reload();
                }
            });
        }
    },
    enviarSunat2: function(id, load_modal = 0){
        $.ajax({
            url: `${API_FE}/sunat-fe-guiarem-remitentes/api-enviar-sunat/${id}`,
            data: '',
            type: 'POST',
            success: function (r) {
                console.log(r);
                location.reload();
            }
        });
    },
    obtenerCDR: function(id, load_modal = 0){
        $.ajax({
            url: `${API_FE}/sunat-fe-guiarem-remitentes/get-cdr/${id}`,
            data: '',
            type: 'POST',
            success: function (r) {
                console.log(r);
                location.reload();
            }
        });
    },
    abrirModalImprimir : function (id) {
        $.ajax({
            url: `${API_FE}/sunat-fe-guiarem-remitentes/single/${id}`,
            data: '',
            type: 'POST',
            success: function (r) {
                console.log(r)
                if(r.success){
                    var str = "";
                    str += `<li class="list-group-item"> 
                        1ro. Enviar guia a sunat `;
                    if(r.data.sunat_num_ticket != '' && r.data.sunat_num_ticket != null){
                        str += `<span class="badge badge-primary">${r.data.estado}</span>`;
                    }else{
                        str += `
                            <button class="btn btn-primary btn-sm" type="button" onclick="indexGuiasRemRemitente.enviarSunat2('${r.data.id}', 1)">
                                <i class='fas fa-paper-plane fa-fw'></i> Enviar
                            </button>
                        `;
                    }
                    str += `</li>`;

                    str += `<li class="list-group-item">
                        2ro. Obtener el CDR`;
                    if(r.data.sunat_cdr_description != '' && r.data.sunat_cdr_description != null){
                        str += `
                            <button class="btn btn-primary btn-sm" type="button" onclick="indexGuiasRemRemitente.obtenerCDR('${r.data.id}',1)">
                                <i class='fas fa-download fa-fw'></i> Solicitar
                            </button>
                        `;
                    }else{
                        str += ` <span class="badge badge-primary">Envie a Sunat para poder descargar el CDR</span>`;

                    }
                    str += `</li>`;

                    str += `<li class="list-group-item">
                        3ro. Descargar PDF `;
                    if(r.data.estado == 'ACEPTADO' && r.data.sunat_cdr_code == '0'){
                        str += `
                            <a class="btn btn-primary btn-sm" type="button" href="${base}/sunat-fe-guiarem-remitentes/descargar-pdf-sunat/${r.data.id}">
                                <i class='fas fa-link fa-fw'></i> PDF
                            </a>
                        `;
                    }else{
                        str += ` <span class="badge badge-primary"> Necesita solicitar el CDR primero </span>`;
                    }
                    str += `</li>`;

                    $("#list_modal_imprimir").html(str)
                    $("#modalImprimir").modal('show');
                }
            }
        });
    },
}