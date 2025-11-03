<div class="modal fade" id="modalDescargarEnviar" tabindex="-1"
     role="dialog" aria-labelledby="modalDescargarEnviarLabel" aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalDescargarEnviarLabel"> <i class="fa fa-mail-bulk fa-fw"></i> Descargar y Enviar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
                <h6 id="titulo1" class="font-weight-bold"></h6>
                <h6 id="titulo2"></h6>
                <h6 id="titulo3"></h6>
                <label>Descarga tu Comprobante</label>

                <div class="d-flex flex-row pb-4 justify-content-between " style="width: 100% !important;"   >
                    <div class="col-md-3 py-0 py-sm-0 text-center" id="descargarComprobanteA4">
                        <figure class="figure click" onclick="ModalDescargarEnviar.DescargarA4()">
                            <img class="figure-img img-fluid" src="<?=$this->Url->Build("/media/iconos/documento_a4.png")?>" width="64px" alt="">
                            <figcaption class="figure-caption">Descargar <br> A4</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-3 py-0 py-sm-0 text-center" id="descargarComprobanteTicket">
                        <figure class="figure click" onclick="ModalDescargarEnviar.DescargarTicket()">
                            <img class="figure-img img-fluid" src="<?=$this->Url->Build("/media/iconos/ticket.png")?>" width="64px" alt="">
                            <figcaption class="figure-caption">Descargar <br> Ticket</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-3 py-0 py-sm-0 text-center" id="descargarComprobanteXml">
                        <figure class="figure click" onclick="ModalDescargarEnviar.DescargarXML()">
                            <img class="figure-img img-fluid" src="<?=$this->Url->Build("/media/iconos/venta_xml.png")?>" width="64px" alt="">
                            <figcaption class="figure-caption ">Descargar <br> XML</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-3 py-0 py-sm-0 text-center" id="descargarComprobanteCdr">
                        <figure class="figure click" onclick="ModalDescargarEnviar.DescargarCDR()">
                            <img class="figure-img img-fluid" src="<?=$this->Url->Build("/media/iconos/venta_cdr.png")?>" width="56px" alt="">
                            <figcaption class="figure-caption ">Descargar CDR</figcaption>
                        </figure>
                    </div>
                </div>



                <!--                <div class="row justify-content-center">-->

                <!--                </div>-->

                <form onsubmit="ModalDescargarEnviar.enviarCorreo(event, this)">
                    <label  >Envía tu Comprobante por <strong>Correo</strong></label>
                    <div class="row pb-4">
                        <div class="col-md-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend text-center">
                                    <span class="input-group-text " style="display: block; width: 48px;" >
                                        <i class="fas fa-envelope fa-fw"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control form-control-sm" name="correo" placeholder="Ejem : prueba@gmail.com" autocomplete="off" placeholder="" required>
                                <div class="input-group-append">
                                    <input type="hidden" name="cpe_id" />
                                    <input type="hidden" name="cpe_tipo" value="venta" />
                                    <button type="submit" id="btnEnviarComprobantePorCorreo" class="btn btn-sm btn-outline-primary" style="width: 120px;">
                                        <i class="fa fa-paper-plane fa-fw"></i> Correo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var ModalDescargarEnviar = {
        IdModal : 'modalDescargarEnviar'  ,
        Init : function (){
            $('[name=whatsapp]').mask('00000000000000');
        },

        Abrir: function(cpe_tipo, cpe_id){
            ModalDescargarEnviar.Limpiar();
            $('#modalDescargarEnviar').modal('show');


            ModalDescargarEnviar.setCampo('cpe_id', cpe_id);
            ModalDescargarEnviar.setCampo('cpe_tipo', cpe_tipo);

            ModalDescargarEnviar.ocultarXml(cpe_tipo);


            ModalDescargarEnviar.recuperarInfoDocumento();

            this.recuperarInfoCliente(cpe_id);

        },


        AbrirDesdeLink: function (e, el) {
            e.preventDefault();

            var cpe_id = $(el).attr("data-cpe-id");
            var cpe_tipo = $(el).attr("data-cpe-tipo");

            ModalDescargarEnviar.Limpiar();
            $('#modalDescargarEnviar').modal('show');

            ModalDescargarEnviar.setCampo('cpe_id', cpe_id);
            ModalDescargarEnviar.setCampo('cpe_tipo', cpe_tipo);

            ModalDescargarEnviar.ocultarXml(cpe_tipo);

            ModalDescargarEnviar.recuperarInfoDocumento();


            this.recuperarInfoCliente(cpe_id);

        },
        recuperarInfoDocumento  :   function() {
            var cpe_tipo = ModalDescargarEnviar.getCampo('cpe_tipo'),
                cpe_id = ModalDescargarEnviar.getCampo('cpe_id'),
                endpoint = "";
            $(`#${ModalDescargarEnviar.IdModal} #titulo1`).html(`<i class="spinner-grow text-secondary"></i>`);

            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" || cpe_tipo == "NOTAVENTA") {
                endpoint = `${base}ventas/get-one/${cpe_id}`;
                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function (r) {
                        //console.log(r)
                        if (r.success) {
                            $(`#${ModalDescargarEnviar.IdModal} #titulo1`).html(`<i class="fa fa-building fa-fw"></i>` + r.data.cliente_razon_social);
                            $(`#${ModalDescargarEnviar.IdModal} #titulo2`).html(`<i class="fa fa-user-tie fa-fw"></i>` + r.data.cliente_doc_numero);
                            $(`#${ModalDescargarEnviar.IdModal} #titulo3`).html(`<i class="fa fa-file-upload fa-fw"></i>`+r.data.documento_serie + "-"+r.data.documento_correlativo);
                        }else{
                            $(`#${ModalDescargarEnviar.IdModal} #titulo1`).html(`<i class="fas fa-frown fa-fw"></i> Ha habido un error para extraer los datos`);
                        }

                    }
                });

            }else if(cpe_tipo == "NC" || cpe_tipo == "ND"){
                endpoint = `${base}sunat-fe-notas-credito/ajax-get-one/${cpe_id}`;
                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function (r) {
                        //console.log(r)
                        $(`#${ModalDescargarEnviar.IdModal} #titulo1`).html(`<i class="fa fa-building fa-fw"></i>` +r.data.cliente_razon_social);
                        $(`#${ModalDescargarEnviar.IdModal} #titulo2`).html(`<i class="fa fa-user-tie fa-fw"></i>` + r.data.cliente_doc_numero);
                        $(`#${ModalDescargarEnviar.IdModal} #titulo3`).html(`<i class="fa fa-file-upload fa-fw"></i>`+r.data.serie + "-"+r.data.correlativo);

                    },
                    error :    function(xhr,ajaxOptions,thrownError)
                    {
                        console.log(xhr);
                        console.log(xhr.status);
                        console.log(ajaxOptions);
                        alert("Ha habido un error para cargar los datos del comprobante");
                    }
                });

            }else if(cpe_tipo == "COTIZACION"){
                endpoint = `${base}cotizaciones/ajax-get-coti-by-id`;
                $.ajax({
                    url: endpoint,
                    data: {id: parseInt(cpe_id)},
                    type: 'GET',
                    success: function (r) {
                        //console.log(r)
                        $(`#${ModalDescargarEnviar.IdModal} #titulo1`).html(`<i class="fa fa-building fa-fw"></i>` +r.data.cliente_razon_social);
                        $(`#${ModalDescargarEnviar.IdModal} #titulo2`).html(`<i class="fa fa-user-tie fa-fw"></i>` + r.data.cliente_doc_numero);
                        $(`#${ModalDescargarEnviar.IdModal} #titulo3`).html(`<i class="fa fa-file-upload fa-fw"></i>`+ r.data.id.toString().padStart(8,'0'));

                    }
                });

            }

        },
        recuperarInfoCliente : function(venta_id){
            var cpe_tipo = ModalDescargarEnviar.getCampo('cpe_tipo');
            var endpoint = ``;

            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" || cpe_tipo == "NOTAVENTA") {
                endpoint = `${base}ventas/get-cliente-por-venta/${venta_id}`;

                $.ajax({
                    url : endpoint,
                    data: '',
                    type: 'GET',
                    success: function(data){
                        if(data.success){
                            if($(`#${ModalDescargarEnviar.IdModal} [name=whatsapp]`).length > 0){
                                $(`#${ModalDescargarEnviar.IdModal} [name=whatsapp]`).val(data.data.whatsapp);
                            }
                            $(`#${ModalDescargarEnviar.IdModal} [name=correo]`).val(data.data.correo);
                        }else{
                            alert(data.message);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });


            }else if(cpe_tipo == "NC" || cpe_tipo == "ND"){
                endpoint = `${base}sunat-fe-notas-credito/ajax-get-cliente-by-nota/${venta_id}`;
                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function (r) {
                        //console.log(r)
                        if(data.success){
                            if($(`#${ModalDescargarEnviar.IdModal} [name=whatsapp]`).length > 0){
                                $(`#${ModalDescargarEnviar.IdModal} [name=whatsapp]`).val(data.data.whatsapp);
                            }
                            $(`#${ModalDescargarEnviar.IdModal} [name=correo]`).val(data.data.correo);
                        }else{
                            alert(data.message);
                        }
                    },
                    error :    function(xhr,ajaxOptions,thrownError)
                    {
                        console.log(xhr.status);
                        console.log(ajaxOptions);
                    }
                });

            }

        },
        ocultarXml: function(cpe_tipo){
            if(cpe_tipo == "NOTAVENTA" || cpe_tipo == "COTIZACION"){
                $('#descargarComprobanteA4').removeClass('col-md-3');
                $('#descargarComprobanteA4').addClass('col-md-6');
                $('#descargarComprobanteTicket').removeClass('col-md-3');
                $('#descargarComprobanteTicket').addClass('col-md-6');
                $('#descargarComprobanteXml').hide();
                $('#descargarComprobanteCdr').hide();
            }else{
                $('#descargarComprobanteA4').removeClass('col-md-6');
                $('#descargarComprobanteA4').addClass('col-md-3');
                $('#descargarComprobanteTicket').removeClass('col-md-6');
                $('#descargarComprobanteTicket').addClass('col-md-3');
                $('#descargarComprobanteXml').show();
                if(cpe_tipo == "BOLETA"){
                    $('#descargarComprobanteCdr').hide();
                }else{
                    $('#descargarComprobanteCdr').show();
                }
            }
        }
        ,
        Cerrar: function(){
            $('#formEnviarCorreoModal').modal('hide');
        },

        enviarCorreo: function(e, fdata){
            e.preventDefault();


            if(fdata.elements['cpe_tipo'].value === 'COTIZACION'){
                ModalDescargarEnviar.enviarCotizacion(e,fdata,'email');
                return;
            }else if(fdata.elements['cpe_tipo'].value === 'NC' || fdata.elements['cpe_tipo'].value === 'ND')
            {
                ModalDescargarEnviar.enviarNota(e,fdata,'email');
                return;
            }

            var endpoint = `${base}email/enviar-comprobante`;

            var datos = new FormData();
            datos.append("correo",fdata.elements['correo'].value )
            datos.append("cpe_id",fdata.elements['cpe_id'].value )
            datos.append("cpe_tipo",fdata.elements['cpe_tipo'].value )

            $("#btnEnviarComprobantePorCorreo").html(`<i class="spinner-border spinner-border-sm" ></i> Enviando ...`);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $("#btnEnviarComprobantePorCorreo").html(`<i class="fa fa-check fa-fw"></i> Enviado!`);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

        },

        enviarWhatsapp(e, fdata ) {
            e.preventDefault();

            if(fdata.elements['cpe_tipo'].value === 'COTIZACION'){
                ModalDescargarEnviar.enviarCotizacion(e,fdata,'whatsapp');
                return;
            }

            var wasap = fdata.elements['whatsapp'].value;

            var datos = new FormData();
            datos.append("whatsapp", wasap)
            datos.append("cpe_id",fdata.elements['cpe_id'].value )
            datos.append("cpe_tipo",fdata.elements['cpe_tipo'].value )

            var endpoint = 'whatsapp/enviar-comprobante/';

            $(`#btnEnviarComprobantePorWhatsapp`).html("<i class='spinner-border spinner-border-sm'></i> Enviando ...");

            $.ajax({
                url : base + endpoint,
                type: 'POST',
                data: datos,
                processData: false,
                contentType: false,
                success:function (data){
                    console.log(data);
                    $(`#btnEnviarComprobantePorWhatsapp`).html("<i class='fas fa-check fa-fw'></i> Enviando!");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });



        },
        enviarCotizacion : function(e,fdata,canal_envio){

            var endpoint =`${base}${canal_envio}/enviar-cotizacion`;


            var datos = new FormData();
            if(canal_envio === 'whatsapp'){
                datos.append("number", fdata.elements['whatsapp'].value);
            }
            else{
                datos.append("correo",fdata.elements['correo'].value );
            }
            datos.append("cotizacion_id",fdata.elements['cpe_id'].value );
            datos.append("cpe_tipo",fdata.elements['cpe_tipo'].value );

            $(`#btnEnviarComprobantePor${canal_envio==='whatsapp'? 'Whatsapp' : 'Correo'}`).html(`<i class="spinner-border spinner-border-sm" ></i> Enviando ...`);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $(`#btnEnviarComprobantePor${canal_envio==='whatsapp'? 'Whatsapp' : 'Correo'}`).html(`<i class="fa fa-check fa-fw"></i> Enviado!`);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });


        },
        enviarNota  :   function(e,fdata,canal_envio)
        {
            var endpoint =`${base}${canal_envio}/enviar-nota-credito-debito`;


            var datos = new FormData();
            if(canal_envio === 'whatsapp'){
                datos.append("number", fdata.elements['whatsapp'].value);
            }
            else{
                datos.append("correo",fdata.elements['correo'].value );
            }
            datos.append("cpe_id",fdata.elements['cpe_id'].value );
            datos.append("cpe_tipo",fdata.elements['cpe_tipo'].value );

            $(`#btnEnviarComprobantePor${canal_envio==='whatsapp'? 'Whatsapp' : 'Correo'}`).html(`<i class="spinner-border spinner-border-sm" ></i> Enviando ...`);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $(`#btnEnviarComprobantePor${canal_envio==='whatsapp'? 'Whatsapp' : 'Correo'}`).html(`<i class="fa fa-check fa-fw"></i> Enviado!`);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
        setCampo  : function(field, value){
            $("#modalDescargarEnviar [name="+field+"]").val(value);
        },

        getCampo  : function(field){
            return $("#modalDescargarEnviar [name="+field+"]").val();
        },

        getDatos: function () {
            var formElement = document.getElementById("formEnviarCorreo");
            var formData = new FormData(formElement);

            return formData;
        },



        Limpiar     : function(){
            $("#formEnviarCorreo [name=factura_id]").val("");
            $("#formEnviarCorreo [name=correo]").val("");
            $("#formEnviarCorreo [name=correo]").focus();

            $(`#${ModalDescargarEnviar.IdModal} #titulo1`).html(``);
            $(`#${ModalDescargarEnviar.IdModal} #titulo2`).html(``);
            $(`#${ModalDescargarEnviar.IdModal} #titulo3`).html(``);
        },

        DescargarA4: function () {
            var cpe_tipo = this.getCampo('cpe_tipo');
            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" ) {
                location.href = `${base}sunat-fe-facturas/descargar-pdf/`+this.getCampo('cpe_id');
            } else if (cpe_tipo == "NOTAVENTA") {
                location.href = `${base}ventas/descargar-nota-venta-pdf/`+this.getCampo('cpe_id');
            } else if (cpe_tipo == "NC") {
                location.href = `${base}sunat-fe-notas-credito/descargar-pdf/`+this.getCampo('cpe_id')+ "/a4/1";
            } else if (cpe_tipo == "ND") {
                location.href = `${base}sunat-fe-notas-debito/descargar-pdf/`+this.getCampo('cpe_id')+ "/a4/1";
            }else if(cpe_tipo == "COTIZACION"){
                location.href = `${base}cotizaciones/descargar-pdf/`+this.getCampo('cpe_id')+ "/a4";
            }
        },

        DescargarTicket: function () {
            var cpe_tipo = this.getCampo('cpe_tipo');
            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" ) {
                location.href = `${base}sunat-fe-facturas/descargar-pdf/`+this.getCampo('cpe_id') + `/ticket`;
            } else if (cpe_tipo == "NOTAVENTA") {
                location.href = `${base}ventas/descargar-nota-venta-ticket/`+this.getCampo('cpe_id') + `/ticket`;
            } else if (cpe_tipo == "NC") {
                location.href = `${base}sunat-fe-notas-credito/descargar-pdf/`+this.getCampo('cpe_id') + "/ticket/1";
            } else if (cpe_tipo == "ND") {
                location.href = `${base}sunat-fe-notas-debito/descargar-pdf/`+this.getCampo('cpe_id') + "/ticket/1";
            }else if(cpe_tipo == "COTIZACION"){
                location.href = `${base}cotizaciones/descargar-pdf/`+this.getCampo('cpe_id')+ "/ticket";
            }
        },

        DescargarXML: function () {
            var cpe_tipo = this.getCampo('cpe_tipo');
            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" ) {
                location.href = `${base}sunat-fe-facturas/descargar-xml/`+this.getCampo('cpe_id');
            } else if (cpe_tipo == "NC" || cpe_tipo == "ND") {
                location.href = `${base}sunat-fe-notas-credito/descargar-xml/`+this.getCampo('cpe_id');
            } else if(cpe_tipo == "COTIZACION"){
                alert("Esta funcionalidad no es valida para proformas");
            }
        },

        DescargarCDR: function () {
            var cpe_tipo = this.getCampo('cpe_tipo');
            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" ) {
                location.href = `${base}sunat-fe-facturas/descargar-cdr/`+this.getCampo('cpe_id');
            } else {
                alert("Esta funcionalidad no es valida para este tipo de documento");
            }
        }

    };
</script>

<style>
    .click {
        cursor: pointer;
    }
</style>

