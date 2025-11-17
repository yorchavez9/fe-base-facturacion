<div class="modal fade" id="ModalImprimirVenta" tabindex="-1"
     role="dialog"  aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <div class="alert alert-info m-0" role="alert" id="titulo_principal">
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
                <div class="row mb-1">
                    <div class="col-4" >
                        <div class="animation-box">
                            <i class="fa fa-print fa-fw animation-box-icon" ></i>
                        </div>
                    </div>
                    <div class="col-8" >
                        <h6 id="titulo1" class="font-weight-bold"></h6>
                        <h6 id="titulo2"></h6>
                        <h6 id="titulo3"></h6>
                    </div>
                </div>

                <div class="row pt-1">
                    <div class="col-sm-12">
                        <p id="comprobante_resultado">
                        </p>
                    </div>
                </div>
                <div class="row pb-2 " >
                    <div class="col-sm-12">

                        <div class="comprobante_error" id="comprobante_error">
                        </div>
                        <a id="comprobante_enviar_soporte"  href="javascript:void(0)"  onclick="ModalImprimirVenta.enviarComprobanteSoporte(this)">
                            <i class="fab fa-whatsapp fa-fw"></i> Enviar a Soporte
                        </a>

                    </div>
                </div>


                <div class="d-flex flex-row pb-4 pt-3 justify-content-between " style="width: 100% !important;"   >
                    <div class="col-md-6 py-0 py-sm-0 text-center" id="imprimirComprobanteA4">
                        <div class="row" >
                            <div class="col-5 click" onclick="ModalImprimirVenta.Imprimir('a4')">
                                <img class="img-fluid" src="<?=$this->Url->Build("/media/iconos/documento_a4.png")?>" width="64px" alt="">
                            </div>
                            <div class="col-7 text-left click" onclick="ModalImprimirVenta.Imprimir('a4')">
                                <span>
                                 Imprimir <br> A4
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 py-0 py-sm-0 text-center" id="imprimirComprobanteTicket">
                        <div class="row" >
                            <div class="col-5 click" onclick="ModalImprimirVenta.Imprimir('ticket')">
                                <img class="img-fluid" src="<?=$this->Url->Build("/media/iconos/ticket.png")?>" width="64px" alt="">
                            </div>
                            <div class="col-7 text-left click" onclick="ModalImprimirVenta.Imprimir('ticket')" >
                                <span>
                                    Imprimir <br> Ticket
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <form onsubmit="ModalImprimirVenta.enviarCorreo(event, this)">
                    <div class="row pb-4">
                        <div class="col-md-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend text-center">
                                    <span class="input-group-text " style="display: block; width: 48px;" >
                                        <i class="fas fa-envelope fa-fw"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control form-control-sm" name="correo" placeholder="Ejem : prueba@gmail.com" autocomplete="off" required>
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

                <div class="row  text-right" id="div_nueva_venta">
                    <div class="col">
                        <a class="btn btn-sm btn-warning text-light" href="<?= $this->Url->build(['controller'=>'Ventas','action' => 'nuevaVenta']) ?>" >
                            <i class="fa fa-cash-register fa-fw"></i> Realizar una Nueva Venta
                        </a>
                        <a class="btn btn-sm btn-primary" href="<?= $this->Url->build(['controller'=>'Ventas','action' => 'index']) ?>" >
                         <i class="fa fa-file-import fa-fw "></i> Ir a Mis Ventas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var ModalImprimirVenta= {
        IdModal : 'ModalImprimirVenta',
        es_nueva_venta: false,
        resultado_sunat : "",
        listado_titulos :
            [
              `<span class="size_titulo_principal m-0">
                    <i class="fa fa-mail-bulk fa-fw"></i> Imprimir y Enviar
                </span>`,
                `<span class="size_titulo_principal m-0">
                    <i class="fas fa-smile-wink fa-fw"></i> Su comprobante ha sido enviado con éxito
                </span>`,
                `<span class="size_titulo_principal m-0">
                    <i class="far fa-sad-tear fa-fw"></i> Ha habido un error
                </span>`,
            ],
        listado_bgcolor_titulos :
        [
          'alert-info','alert-success','alert-danger'
        ],
        titulo_modal_index : 0,
        titulo_background_index : 0,
        titulo_old_background_index : 0,
        Init : function (es_nueva_venta= false,resultado_sunat="",titulo_modal=0,error_code=""){
            $('[name=whatsapp]').mask('00000000000000');
            this.es_nueva_venta = es_nueva_venta;
            ModalImprimirVenta.titulo_modal_index = titulo_modal;
            ModalImprimirVenta.titulo_background_index = titulo_modal;

            if(error_code != "")
            {
                ModalImprimirVenta.setLinkEnviarSoporte(error_code,resultado_sunat);
                ModalImprimirVenta.cambiarIconAnimationBox("far fa-sad-tear fa-fw",'text-danger');
                $("#comprobante_enviar_soporte").show();
            }else{
                $(`#${ModalImprimirVenta.IdModal} .animation-box`).html(`
                    <img class="img-fluid mx-auto" src="${base_root}media/iconos/animacion_venta_finalizada.gif" />
                `) ;
                ModalImprimirVenta.resultado_sunat = resultado_sunat;
                $("#comprobante_enviar_soporte").hide();
            }


        },
        setLinkEnviarSoporte :  function(error_code, error_descripcion)
        {

            error_descripcion =error_descripcion.replace(/[ '"]+/g, ' ');

            var link =
                ` <a
                    tabindex="0" style="color: red;text-decoration: none!important;cursor: pointer"
                    data-placement="right"
                    data-toggle="popover"
                    data-trigger="click" title="Error ${error_code}"
                    data-content="${error_descripcion}">
                    Ver error </a>`;


            $(`#${ModalImprimirVenta.IdModal} #comprobante_error`).html(link) ;

            $(function () {
                $('[data-toggle="popover"]').popover()
            });

        },
        cambiarIconAnimationBox :   function(icon_class,color_class)
        {
            var icon =
                `<i class="${icon_class} ${color_class} animation-box-icon" ></i>`;
            $(`#${ModalImprimirVenta.IdModal} .animation-box`).html(icon) ;
        },
        Abrir: function(cpe_tipo, cpe_id){
            $('#ModalImprimirVenta').modal('show');
            ModalImprimirVenta.Limpiar();
            ModalImprimirVenta.actualizarTituloModal();

            if(ModalImprimirVenta.es_nueva_venta)
            {
                $("#div_nueva_venta").show();

            }else{
                $("#div_nueva_venta").hide();
            }
            $(`#${ModalImprimirVenta.IdModal} #comprobante_resultado`).html(ModalImprimirVenta.resultado_sunat);


            ModalImprimirVenta.setCampo('cpe_id', cpe_id);
            ModalImprimirVenta.setCampo('cpe_tipo', cpe_tipo);


            ModalImprimirVenta.recuperarInfoDocumento();
            ModalImprimirVenta.recuperarInfoCliente(cpe_id);

        },
        AbrirDesdeLink: function (e, el) {
            e.preventDefault();

            var cpe_id = $(el).attr("data-cpe-id");
            var cpe_tipo = $(el).attr("data-cpe-tipo");

            $('#ModalImprimirVenta').modal('show');
            ModalImprimirVenta.Limpiar();
            ModalImprimirVenta.actualizarTituloModal();
            if(ModalImprimirVenta.es_nueva_venta)
            {
                $("#div_nueva_venta").show();
            }else{
                $("#div_nueva_venta").hide();
            }

            ModalImprimirVenta.setCampo('cpe_id', cpe_id);
            ModalImprimirVenta.setCampo('cpe_tipo', cpe_tipo);

            ModalImprimirVenta.recuperarInfoDocumento();
            ModalImprimirVenta.recuperarInfoCliente(cpe_id);

        },
        actualizarTituloModal   :   function()
        {
            $(`#${ModalImprimirVenta.IdModal} #titulo_principal`).html(ModalImprimirVenta.listado_titulos[ModalImprimirVenta.titulo_modal_index]);
            $(`#${ModalImprimirVenta.IdModal} #titulo_principal`).removeClass( ModalImprimirVenta.listado_bgcolor_titulos[ModalImprimirVenta.titulo_old_background_index]);
            $(`#${ModalImprimirVenta.IdModal} #titulo_principal`).addClass( ModalImprimirVenta.listado_bgcolor_titulos[ModalImprimirVenta.titulo_background_index] );
            ModalImprimirVenta.titulo_old_background_index= ModalImprimirVenta.titulo_background_index;
        },
        setNuevaVentaStyle  :   function (){

        },
        recuperarInfoDocumento  :   function()
        {
            var cpe_tipo = ModalImprimirVenta.getCampo('cpe_tipo'),
                cpe_id   = ModalImprimirVenta.getCampo('cpe_id'),
                endpoint = "";
            $(`#${ModalImprimirVenta.IdModal} #titulo1`).html(`<i class="spinner-grow text-secondary"></i>`);

            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" || cpe_tipo == "NOTAVENTA") {
                endpoint = `${base}ventas/get-one/${cpe_id}`;
                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function (r) {
                        console.log(r)
                        if (r.success) {
                            $(`#${ModalImprimirVenta.IdModal} #titulo1`).html(`<i class="fa fa-building fa-fw"></i> ` +r.data.cliente_razon_social);
                            $(`#${ModalImprimirVenta.IdModal} #titulo2`).html(`<i class="fa fa-user-tie fa-fw"></i> ` + r.data.cliente_doc_numero);
                            $(`#${ModalImprimirVenta.IdModal} #titulo3`).html(`<i class="fa fa-mail-bulk fa-fw"></i> Venta #` +r.data.id);
                        }

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
                        $(`#${ModalImprimirVenta.IdModal} #titulo1`).html(`<i class="fa fa-building fa-fw"></i> ` +r.data.cliente_razon_social);
                        $(`#${ModalImprimirVenta.IdModal} #titulo2`).html(`<i class="fa fa-user-tie fa-fw"></i> ` +'Doc Nro: <i></i> ' + r.data.cliente_doc_numero);
                        $(`#${ModalImprimirVenta.IdModal} #titulo3`).html(`<i class="fa fa-mail-bulk fa-fw"></i> `+ r.data.id.toString().padStart(8,'0'));

                    }
                });

            }
        },
        recuperarInfoCliente : function(venta_id){
            var endpoint = `${base}ventas/get-cliente-por-venta/${venta_id}`;

            $.ajax({
                url : endpoint,
                data: '',
                type: 'GET',
                success: function(data){
                    if(data.success){
                        if($('[name=whatsapp]').length > 0){
                            $('[name=whatsapp]').val(data.data.whatsapp);
                        }
                        $('[name=correo').val(data.data.correo);
                    }else{
                        alert(data.message);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                }
            })
        },
        Cerrar: function(){
            $('#formEnviarCorreoModal').modal('hide');
        },

        enviarCorreo: function(e, fdata){
            e.preventDefault();


            if(fdata.elements['cpe_tipo'].value === 'COTIZACION'){
                ModalImprimirVenta.enviarCotizacion(e,fdata,'email');
                return;
            }

            var endpoint = `${base}email/enviar-comprobante`;

            var datos = new FormData();
            datos.append("correo",fdata.elements['correo'].value )
            datos.append("cpe_id",fdata.elements['cpe_id'].value )
            datos.append("cpe_tipo",fdata.elements['cpe_tipo'].value )

            $(`#${ModalImprimirVenta.IdModal} #btnEnviarComprobantePorCorreo`).html(`<i class="spinner-border spinner-border-sm" ></i> Enviando ...`);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $(`#${ModalImprimirVenta.IdModal} #btnEnviarComprobantePorCorreo`).html(`<i class="fa fa-check fa-fw"></i> Enviado!`);
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
                ModalImprimirVenta.enviarCotizacion(e,fdata,'whatsapp');
                return;
            }

            var wasap = fdata.elements['whatsapp'].value;

            var datos = new FormData();
            datos.append("whatsapp", wasap);
            datos.append("cpe_id",fdata.elements['cpe_id'].value )
            datos.append("cpe_tipo",fdata.elements['cpe_tipo'].value )

            var endpoint = 'whatsapp/enviar-comprobante/';

            $(`#${ModalImprimirVenta.IdModal} #btnEnviarComprobantePorWhatsapp`).html("<i class='spinner-border spinner-border-sm'></i> Enviando ...");

            $.ajax({
                url : base + endpoint,
                type: 'POST',
                data: datos,
                processData: false,
                contentType: false,
                success:function (data){
                    console.log(data);
                    $(`#${ModalImprimirVenta.IdModal} #btnEnviarComprobantePorWhatsapp`).html("<i class='fas fa-check fa-fw'></i> Enviando!");
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

            $(`#${ModalImprimirVenta.IdModal} #btnEnviarComprobantePor${canal_envio==='whatsapp'? 'Whatsapp' : 'Correo'}`).html(`<i class="spinner-border spinner-border-sm" ></i> Enviando ...`);
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $(`#${ModalImprimirVenta.IdModal} #btnEnviarComprobantePor${canal_envio==='whatsapp'? 'Whatsapp' : 'Correo'}`).html(`<i class="fa fa-check fa-fw"></i> Enviado!`);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });


        },

        setCampo  : function(field, value){
            $("#ModalImprimirVenta [name="+field+"]").val(value);
        },

        getCampo  : function(field){
            return $("#ModalImprimirVenta [name="+field+"]").val();
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

            $(`#${ModalImprimirVenta.IdModal} #titulo1`).html(``);
            $(`#${ModalImprimirVenta.IdModal} #titulo2`).html(``);
            $(`#${ModalImprimirVenta.IdModal} #titulo3`).html(``);


            //$(`#${ModalImprimirVenta.IdModal} #comprobante_error`).html("");

        },

        Imprimir: function (formato) {
            var cpe_tipo = this.getCampo('cpe_tipo');
            var endpoint = "";
            var venta_id = this.getCampo('cpe_id');

            if (cpe_tipo == "BOLETA" || cpe_tipo == "FACTURA" ) {
                endpoint = `${base}sunat-fe-facturas/api-imprimir-pdf/${venta_id}/${formato}`;
            } else if (cpe_tipo == "NOTAVENTA") {
                endpoint = `${base}ventas/api-imprimir-pdf/${venta_id}/${formato}`;
            }
            console.log(endpoint);

            $.ajax({
                url     :   endpoint,
                data    :   '',
                type    :   'GET',
                dataType:   'json',

                success :   function (data, status, xhr) {// success callback function
                    console.log(data);
                    if(data.success)
                    {
                        printJS({printable: data.data, type: 'pdf', base64: true, modalMessage: 'Cargando Documento'});

                    }else{
                        alert('Error: ' + data.message);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('Error en la petición:', xhr.responseText);
                    if (xhr.status === 404) {
                        alert('No se encontró el comprobante electrónico para esta venta');
                    } else if (xhr.responseText && xhr.responseText.includes('<!DOCTYPE')) {
                        alert('Error: El servidor devolvió una página HTML en lugar de datos JSON. Verifica que la venta tenga un comprobante electrónico emitido.');
                    } else {
                        alert('Error al imprimir: ' + (thrownError || 'Error desconocido'));
                    }
                }
            });

        },
        enviarComprobanteSoporte  : function (element) {
            var venta_id = ModalImprimirVenta.getCampo('cpe_id');

            var link_icono = $(element).find("i")[0];
            ModalImprimirVenta.setLinkLoad(link_icono,element,"fa-whatsapp");

            var url = `${base}sunat-fe-facturas/api-comunicar-soporte-error-comprobante`;
            console.log(url);
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
                    ModalImprimirVenta.stopLinkLoad(link_icono,element,"fa-whatsapp");

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);

                    ModalImprimirVenta.stopLinkLoad(link_icono,element,"fa-whatsapp");

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
        }


    };
</script>

<style>
    .animation-box{
        background-color: #FFFFFF ;
        box-shadow: 2px 3px 5px #6c757d;
        display: flex;
        padding: 10px 10px 10px 10px;
        height: 110px;
        width:140px;
    }
    .animation-box-icon
    {
        margin: auto;
        font-size: 80px;
    }
    #ModalImprimirVenta .size_titulo_principal{
       font-size: 18px;
    }
    .click {
        cursor: pointer;
    }
</style>

