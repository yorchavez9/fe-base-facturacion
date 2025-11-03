const validarFechas =   (fecha1, fecha2,simbolo)    =>  {
    if(['>' ,'>=','<=' ,'<','=='].indexOf(simbolo) == -1){
        return false;
    }

    var d_fecha1 =  new Date(fecha1);
    var d_fecha2 =  new Date(fecha2);

    if(simbolo == '=='){
        return (d_fecha1.getTime() == d_fecha2.getTime)
    }

    return eval(`'${d_fecha1}' ${simbolo} '${d_fecha2}'`);
}


$(document).ready(function(){

    nuevaVenta.init(
        {
            _is_allowed_factura  : _is_allowed_factura,
            _doctipo_default     : _doctipo_default,
            _series              :   _series,
            _coti_id             :   _coti_id,
            _ntventa_id          :   _ntventa_id,
            servicios            :   servicios,
            last_tipo_moneda     :  $('select[name=tipo_moneda]').val()
        }

    );


    GlobalFormTipoCambio.init();
    GlobalFormTipoCambio.callback = function(cambio)
    {
        nuevaVenta.setTipoCambio(cambio);
        if (_coti_id != "") {
            $("input[name=numero_cotizacion]").val(nuevaVenta._coti_id);
            nuevaVenta.cargarCotizacion();
            _coti_id = "";
        }else if(_ntventa_id != ""){
            nuevaVenta.cargarNotaVenta(nuevaVenta._ntventa_id);

        }
    }

    /**
     * luego de que se carga una venta desde un excel
     * los items se cargan en esta funcion
     */
    FormVentaDesdeExcel.callback = function(r){
        console.log(r);
        nuevaVenta.cargarInfoFromExcel(r.data);
    };

    FormEditarProductoDetalle.callback = function(item)
    {
        nuevaVenta.itemActualizarRegistro(item);
    };


    ModalDetracciones.init();
    ModalDetracciones.callback = function(detraccionObj)
    {
        nuevaVenta.actualizarDetraccion(detraccionObj);
    }


    $('select[name=tipo_moneda]').focus(function(){
        // alert(this.defaultValue);
        nuevaVenta.last_tipo_moneda = $('select[name=tipo_moneda]').val();
    });
    $('select[name=tipo_moneda]').change(function(){
        // alert(this.defaultValue);
        nuevaVenta.actualizarVentaDetalle();
        $('select[name=tipo_moneda]').blur();
    });

    GlobalServicios.init();
    GlobalServicios.callback = function(itemServicioObj)
    {
        nuevaVenta.itemAddServicio(itemServicioObj);
    }


    // inicializamos el reloj
    nuevaVenta.startTime();


    $('[name=fecha_vencimiento]').datepicker({
        locale: 'es-es',
        format:'yyyy-mm-dd',
        uiLibrary:'bootstrap4'
    });

    // paramos la ejecucion de la tecla ENTER
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    // configuramos el datepicker para cambiar las fechas
    if(_editar_fecha_emision != undefined && parseInt(_editar_fecha_emision) == 1 )
    {
        $("[name=fecha_venta]").datepicker({
            locale: 'es-es',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4'
        });
    }


    $("input[name=cliente_ruc]").autocomplete({
        serviceUrl: base + 'cuentas/api-consulta-clientes',
        onSelect: function (item) {
            nuevaVenta.clienteEstablecerDatos(item);
        }
    });

    // enter en la tecla de lectura de codigo de barras
    $(".ingresocodigo").on('keypress', function(e) {
        var codigo = $(this).val();
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            nuevaVenta.buscarProductoPorCodigo(codigo);
        }
    });

    // enter en la tecla de lectura de codigo de barras
    $(".ingresoproducto").on('keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            nuevaVenta.abrirBuscadorProductos()

        }
    });

    nuevaVenta._table = $("#tablafiltro").DataTable({
        'select'    : true,
        'searching' : false,
        'paging' : true,
        'pageLength':10,
        'lengthChange': false,
        "language": {
            "url": base_root + "/assets/data-tables/es.json"
        },
    });

    $('#tablafiltro tbody').on( 'click', 'tr', function () {
        // console.log( nuevaVenta._table.row( this ).data() );
        var rowdata =  nuevaVenta._table.row( this ).data();
        nuevaVenta.buscarProductoPorCodigo(rowdata[2]);
    } );

    newItemsList.callback = function(itemObj){
        nuevaVenta.itemsAgregar(itemObj);
        nuevaVenta.actualizarVentaDetalle();
    }

    $("[name=documento_numero]").mask("Z000-00000000",{
        translation: {
            'Z': {
                pattern: /[A-Z]/, optional: true
            }}
    });
    $("[name=pago_inicial]").mask("#00.00",{reverse:true});

    // bind para limpiar datos
    $(".limpiar_formulario").click(function(){
        nuevaVenta.clienteLimpiarDatos();
    });

    // binding
    $("#campoBusqueda2").on("keyup change", function(){
        var value = $("#campoBusqueda2").val();
        $("#campoBusqueda1").val(value);
    });


    // nuevaVenta.formServicioPoblarUnidadesMedida();


    $("#formNuevaVenta button[name=imprimir]").click(function() {
        nuevaVenta.imprimir = 1;
        nuevaVenta.botonClick = "#btn_guardar_imprimir_venta";
    });

    $(`[name=nro_cuotas]`).on('input',function(){

        if($(`[name=nro_cuotas]`).val() == ''){
            $(`[name=nro_cuotas]`).val(1)
        }
        nuevaVenta.generarTablaCuotas()
    });
    $(`[name=monto_total]`).on('input',function(){
        if($(`[name=monto_total]`).val() == ''){
            $(`[name=monto_total]`).val(0)
        }
        nuevaVenta.generarTablaCuotas()
    });

    $("[name=medio_pago_venta]").change(function(){
        if($("[name=medio_pago_venta]").val() == 'OTRO'){
            $("#div_otro_pago").show()
            $("[name=medio_pago_otro]").attr('required', true)
        }else{
            $("#div_otro_pago").css('display', 'none')
            $("[name=medio_pago_otro]").attr('required', false)
        }
    });

    
    $("[name=documento_tipo]").change(function(){
        var tipo =  $("[name=documento_tipo]").val();
        switch (tipo){
            case 'NOTAVENTA' :
                $("#documento_serie").parent().hide();
                $("[name=documento_serie]").prop('required', false);
                $("#clienteTipoDocInput").attr("placeholder", 'RUC o DNI');
                $(".nv-caption").show();
                break;
            case 'FACTURA':
                $("#documento_serie").parent().show();
                $("[name=documento_serie]").prop('required', true);
                $("#clienteTipoDocInput").attr("placeholder", 'RUC o DNI');
                $(".nv-caption").hide();
                break;
            case 'BOLETA':
                $("#documento_serie").parent().show();
                $("[name=documento_serie]").prop('required', true);
                $("#clienteTipoDocInput").attr("placeholder", 'RUC o DNI');
                $(".nv-caption").hide();
                break;
        }
        nuevaVenta.validarUsoDetraccion();
    });

    if(nuevaVenta._doctipo_default === 'FACTURA' && nuevaVenta._is_allowed_factura === 1){
        $("[name=documento_tipo]").val(nuevaVenta._doctipo_default);
    }
    else if(nuevaVenta._doctipo_default === 'BOLETA' && nuevaVenta._is_allowed_boleta === 1){
        $("[name=documento_tipo]").val(nuevaVenta._doctipo_default);
    }
    nuevaVenta.setEstablecimientos();

});

const listaMeses = [31,28,31,30,31,30,31,31,30,31,30,31]

var nuevaVenta =
    {
        _op_gravadas    : 0,
        _op_inafectas   : 0,
        _op_exoneradas  : 0,
        _detalle : [],
        _items:[],
        _cuenta : {},
        _persona : {},
        _totales : {},
        _table : null,
        _is_allowed_factura :   false,
        _is_allowed_boleta  :   false,
        _doctipo_default    :   "",
        _series             :   {},
        last_tipo_moneda    :   "",
        servicios           :   null,
        _coti_id            :   0,
        _ntventa_id         :   0,
        inc_igv             : true,
        error_code_sunat    : "",
        imprimir            : 0,
        venta_id            : 0,
        documento_tipo      : "",
        doc_tipo_nombre     : "",
        resultado_sunat     : "",
        codigo_detraccion   :   '000',
        detraccion_porc     :   0,
        tipo_cambio_venta   :     1,
        stateXmlLink        :     `<i class="fa spinner-border spinner-border-sm" ></i> Generando Xml`,
        stateSunatLink      :     `<i class="fa spinner-border spinner-border-sm" ></i> Enviando a Sunat `,
        stateSunatEnviado   :     `<i class="fa fa-check fa-fw" ></i> Enviado `,
        stateComprobanteReg :     `<i class="fa fa-check fa-fw" ></i> Comprobante Registrado `,
        stateError          :     `<i class="fas fa-times fa-fw" ></i> Ha habido un error `,
        botonClick          :   "#btn_guardar_venta",
        serv_id             :   100000,
        base_path_venta     :   `${base}ventas/`,
        base_path_facturas  :   `${base}sunat-fe-facturas/`,
        api_base_path : `${base}api/`           ,
        init    :   function(data)
        {
            nuevaVenta._is_allowed_boleta   = data._is_allowed_boleta;
            nuevaVenta._is_allowed_factura  = data._is_allowed_factura;
            nuevaVenta._doctipo_default     = data._doctipo_default;
            nuevaVenta._series              = data._series;
            nuevaVenta._coti_id             = data._coti_id;
            nuevaVenta._ntventa_id          = data._ntventa_id ;
            nuevaVenta.servicios            = data.servicios;
            nuevaVenta.last_tipo_moneda     =  data.last_tipo_moneda;
        },
        guardar :   function(e)
        {
            e.preventDefault();

            if(!this.validacionesNuevaVenta())
            {
                return;
            }
            var form = new FormData( document.getElementById("formNuevaVenta"));
            var endpoint = `${nuevaVenta.base_path_venta}nueva-venta`;
            //$("#global_spinner").attr('hidden',false);
            GlobalSpinner.mostrar();
            GlobalSpinner.setText('Registrando venta');
            $.ajax
            ({
                url    :   endpoint,
                data   :   form,
                type   :   "POST",
                cache   :   false,
                contentType :   false,
                processData :   false,
                success:   function(data)
                {

                    if(data.success){
                        nuevaVenta.venta_id = data.data.id;
                        nuevaVenta.doc_tipo_nombre = data.documento_tipo;
                        if(data.documento_tipo != "NOTAVENTA")
                        {
                            nuevaVenta.generarXML(data.data);
                            GlobalSpinner.ocultar();
                            nuevaVenta.abrirModalImprimirVenta(1);
                        }else{
                            GlobalSpinner.ocultar();
                            nuevaVenta.abrirModalImprimirVenta(0);
                            $(nuevaVenta.botonClick).html(nuevaVenta.stateComprobanteReg);

                        }

                    }else{
                        alert(data.message);
                        GlobalSpinner.ocultar();
                        nuevaVenta.abrirModalImprimirVenta(2);
                    }

                },
                error:function( xhr , ajaxOptions , thrownError )
                {
                    alert(xhr.status);
                    alert(thrownError);
                    GlobalSpinner.ocultar();
                    // location.reload();
                    nuevaVenta.abrirModalImprimirVenta(2);
                }
            });
        },
        generarXML  :   function( venta )
        {
            var endpoint = `${API_FE}/sunat-fe-facturas/add`;
            $(nuevaVenta.botonClick).html(nuevaVenta.stateXmlLink);
            GlobalSpinner.setText('Generando Xml');
            var arr_items = venta.venta_registros.map((e)=>{
                return {
                    item_codigo: e.item_codigo,
                    item_unidad: e.item_unidad,
                    cantidad: e.cantidad,
                    valor_venta: e.valor_venta,
                    precio_uventa: e.precio_uventa,
                    item_nombre: e.item_nombre,
                    afectacion_igv: e.afectacion_igv,
                    subtotal: e.subtotal,
                    igv_monto: e.igv_monto,
                    precio_total: e.precio_total,
                }
            })
            var arr_pagos = venta.venta_pagos.map((e)=>{
                return {
                    monto: e.monto,
                    fecha_pago: e.fecha_pago,
                }
            })
            var formData = {
                "venta_id" : venta.id,
                "establecimiento_id" : venta.establecimiento_id,
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

            $.ajax({
                url    :   endpoint,
                data   :   JSON.stringify(formData),
                processData: false,
                contentType: "application/json",
                type   :   "POST",
                success:   function(data)
                {
                    console.log("XML", data)
                    if(data.success){
                        if(nuevaVenta.documento_tipo != '03' && nuevaVenta.documento_tipo != '3')
                        {
                            nuevaVenta.enviarSunat(data.data.id);
                        }else {
                            GlobalSpinner.ocultar();
                            $(nuevaVenta.botonClick).html(nuevaVenta.stateComprobanteReg);
                            nuevaVenta.resultado_sunat = "Comprobante registrado con éxito";
                            nuevaVenta.abrirModalImprimirVenta(0);
                        }

                    }else{
                        alert(data.message);
                        alert("Ha habido un error al momento de generar el Xml, contactar a soporte")
                        // location.reload();
                        nuevaVenta.abrirModalImprimirVenta(2);
                        GlobalSpinner.ocultar();

                    }

                },
                error:function( xhr , ajaxOptions , thrownError )
                {
                    alert(xhr.status);
                    alert(thrownError);
                    // location.reload();
                    nuevaVenta.abrirModalImprimirVenta(2);
                    GlobalSpinner.ocultar();
                }
            })
        },
        enviarSunat :   function(factura_id)
        {
            var endpoint = `${API_FE}/sunat-fe-facturas/api-enviar-sunat`;
            $(nuevaVenta.botonClick).html(nuevaVenta.stateSunatLink);
            GlobalSpinner.setText('Enviando a Sunat');
            var modal_venta_titulo_index = 0 ;
            $.ajax
            ({
                url    :   endpoint,
                data   :    {factura_id : factura_id},
                type   :   "POST",
                success:   function(data)
                {
                    console.log("ENVIAR-SUNAT", data)

                    if( typeof data === 'object' && data !== null && data.success){
                        nuevaVenta.resultado_sunat = data.data;
                        modal_venta_titulo_index = 1;
                    }else{
                        nuevaVenta.resultado_sunat = `${data.data.mensaje}`;
                        modal_venta_titulo_index = 2;
                        nuevaVenta.error_code_sunat = data.data.codigo;

                    }
                    $(nuevaVenta.botonClick).html(nuevaVenta.stateSunatEnviado);
                    GlobalSpinner.setText('Enviando');
                    // $("#global_spinner").attr('hidden',true);
                    GlobalSpinner.ocultar();
                    nuevaVenta.abrirModalImprimirVenta(modal_venta_titulo_index);

                    //location.href = `${nuevaVenta.base_path_facturas}procesar-nuevo/${nuevaVenta.venta_id}/${nuevaVenta.imprimir}`  ;

                },
                error:function( xhr , ajaxOptions , thrownError )
                {
                    alert(xhr.status);
                    alert(thrownError);
                    // location.reload();
                    nuevaVenta.abrirModalImprimirVenta(2);
                    GlobalSpinner.ocultar();
                }

            })
        },
        abrirModalImprimirVenta : function(titulo_index = 0)
        {
            ModalImprimirVenta.Init(true,nuevaVenta.resultado_sunat,titulo_index,nuevaVenta.error_code_sunat);
            ModalImprimirVenta.Abrir(nuevaVenta.doc_tipo_nombre,nuevaVenta.venta_id) ;
        },
        validacionesNuevaVenta  :   function()
        {
            var total = $("#formNuevaVenta [name=total]").val();
            var cliente_id = $("#formNuevaVenta [name=cliente_id]").val();
            var tipodoc = $("#formNuevaVenta [name=documento_tipo]").val();
            var clieruc = $("#formNuevaVenta [name=cliente_ruc]").val();
            var cliedocnum = $("#formNuevaVenta [name=cliente_doc_numero]").val();
            var forma_pago = $("[name=forma_pago]").val();
            var forma_pago_total =$("[name=monto_credito_total]").val();

            nuevaVenta.bloquearBoton();

            if (!this.validarTipoDocumentos(tipodoc, clieruc)                  ||
                !this.validarMontoTotalVenta(total)                             ||
                !this.validarExistenciaCliente(tipodoc, cliente_id)             ||
                !this.validarGuardadoCliente(clieruc, cliedocnum)               ||
                !this.validarMontoMinimoBoletasConDni(tipodoc, total, clieruc)  ||
                !this.validarFormaPagoCredito(forma_pago, forma_pago_total))
            {
                nuevaVenta.desbloquearBoton();
                return false;
            }
            return  true;
        },
        /**
         * Bloquea el boton de guardado de la venta
         */
        bloquearBoton   :  function() {
            //$('#botones_venta').hide();
            $('#btn_guardar_venta').attr('disabled', true);
            $('#btn_guardar_imprimir_venta').attr('disabled', true);
        },

        /**
         * Desbloquea el boton de guardado de la venta
         */
        desbloquearBoton    :   function() {
            //$('#botones_venta').show();
            $('#btn_guardar_venta').attr('disabled', false);
            $('#btn_guardar_imprimir_venta').attr('disabled', false);
        },
        /**
         * Aqui Comienzan todas las validaciones
         * Correspondientes a la creacion de una nueva venta
         */
        validarTipoDocumentos   :   function(tipodoc,clieruc)
        {
            if( tipodoc == 'FACTURA' && clieruc.length < 11 )
            {
                alert("No Puede emitir una factura sin un RUC válido");
                $("#formNuevaVenta [name=cliente_ruc]").focus();
                return false;
            }
            return true;
        },
        /**
         * Valida que el monto de la venta no sea menor o igual a cero
         */
        validarMontoTotalVenta  :   function(total)
        {
            if(total <= 0)
            {
                alert('Debe ingresar correctamente los datos de la venta');
                return false;
            }
            return true;
        },
        /**
         * Valida que el cliente al cual se le va a emitir una factura
         * este previamente registrado
         */
        validarExistenciaCliente    :   function(tipodoc,cliente_id)
        {
            if ((tipodoc == 'FACTURA') && (cliente_id == "" || cliente_id == "0" || cliente_id == null))
            {
                alert("Debe guardar el clientes antes de continuar\nPresione el disquete para realizar dicho paso");
                console.log("Cliente_id: " + cliente_id)
                $("#clienteEstadoBusqueda2").focus();
                return false;
            }
            return true;
        },
        /**
         *Le indica al usuario que guarde el cliente ,
         * esto se activa cuando se ha consultado un cliente
         * con la api de DNI o RUC
         */
        validarGuardadoCliente  :   function(clieruc,cliedocnum)
        {
            if (clieruc.length > 0 && cliedocnum.length == 0)
            {
                alert('Aprete el botón de guardado de cliente');
                $('#clienteEstadoBusqueda2').focus();
                return false;
            }
            return true;
        },
        /**
         *Valida que una boleta se emita con DNI del cliente
         * cuando el monto total de la boleta excede o es igual a 700 soles
         */
        validarMontoMinimoBoletasConDni :   function(tipodoc,total,clieruc)
        {
            if ((tipodoc == 'BOLETA') && (total >= 700) && (clieruc.length < 8))
            {
                alert("Debe ingresar DNI para boletas de con total de mayor o igual a 700 soles");
                $("#formNuevaVenta [name=cliente_ruc]").focus();
                return false;
            }
            return true;
        },
        /**
         * Valida la forma de pago del comprobante
         * si es CREDITO, activa el modal correspondiente
         */
        validarFormaPagoCredito    :   function(forma_pago, forma_pago_total)
        {
            if(forma_pago === "CREDITO" && parseFloat(forma_pago_total) === 0 )
            {
                nuevaVenta.abrirModalCuotas();
                return false;
            }
            return true;

        },

        /**
         * Aqui terminan las validaciones
         */

        /**
         * Verifica el tipo de comprobante para
         * cerciorarse de que las ventas con creditos
         * se realicen unica y exclusivamente con facturas
         */
        verificarComprobante    :   function()
        {
            var comprobante_tipo = $("[name=documento_tipo]").val();
            if(comprobante_tipo !== "FACTURA")
            {
                $("#formNuevaVentaTipoCambio").hide();
                $("#formNuevaVentaDocumentoTipo").removeClass("col-sm-3")
                $("#formNuevaVentaDocumentoTipo").addClass(" col-sm-6")
                $("[name=forma_pago]").val("CONTADO");
            }else{
                $("#formNuevaVentaDocumentoTipo").removeClass(" col-sm-6")
                $("#formNuevaVentaDocumentoTipo").addClass("col-sm-3")
                $("#formNuevaVentaTipoCambio").show();
            }
        },

        /**
         * Abre el modal correspondiente
         * al pago de cuotas de las facturas a credito
         */
        abrirModalCuotas    :   function() {
            var forma_pago = $(`[name=forma_pago]`).val();

            if (forma_pago == "CONTADO") {
                $("#btnAbrirModalCuotas").attr("disabled",true);
                return;
            }
            var total = $("#formNuevaVenta [name=total]").val();

            if (total <= 0) {
                alert('Ingrese al menos un producto para poder configurar las cuotas');
                $(`[name=forma_pago]`).val('CONTADO');
                return;
            }
            $("#btnAbrirModalCuotas").attr("disabled",false);
            $(`#modalCuotas`).modal('show');
            $(`[name=monto_total]`).val(total);
            $(`[name=nro_cuotas]`).val(2);

            nuevaVenta.generarTablaCuotas();

        },

        /**
         * Genera las cuotas para facturas
         * a credito
         */
        generarTablaCuotas  :   function() {
            var total_venta = parseFloat($("#formNuevaVenta [name=total]").val());
            var total = parseFloat($("[name=monto_total]").val());


            if(total > total_venta ){
                alert("El monto total a pagar no puede ser mayor que el total de la venta");
                $("[name=monto_total]").val(total_venta);
                total = total_venta;
            }

            var html = ``;

            var nro = $(`[name=nro_cuotas]`).val();

            var cuota = (1.0 * parseFloat(total) / parseInt(nro)).toFixed(2);

            //Se recupera la fecha y se pone en formato ISO
            //Se extrae unicamente la fecha, es decir,  lo anterior a la T
            var f = new Date();
            var hoy = f.toISOString().split('T')[0];

            const today = new Date();
            today.setDate(today.getDate() + 1 );
            hoy = `${today.getFullYear()}-${(today.getMonth()+1).toString().padStart(2, "0")}-${today.getDate().toString().padStart(2, "0")}`

            for (var i = 1; i <= parseInt(nro); i++) {

                html += `
                    <tr>
                        <td>${i}</td>
                        <td><input class="form-control form-control-sm" name="cuota_fecha_${i}" value="${hoy}"></td>
                        <td><input class="form-control form-control-sm" name="cuota_monto_${i}" value="${cuota}"></td>
                    </tr>
                `;
                today.setMonth(today.getMonth() + 1 );
                hoy = `${today.getFullYear()}-${(today.getMonth()+1).toString().padStart(2, "0")}-${today.getDate().toString().padStart(2, "0")}`
            }

            $(`#tablaCuotas`).html(html);

            for (var i = 1; i <= parseInt(nro); i++) {
                $(`[name=cuota_fecha_${i}]`).datepicker({
                    format: 'yyyy-mm-dd',
                    locale: 'es-es',
                    uiLibrary: 'bootstrap4'
                });
            }
        },
        guardarCuotas   :   function() {
            var list_json = [];
            var nro = $(`[name=nro_cuotas]`).val();
            var pagos_total = $(`#modalCuotas [name=monto_total]`).val();
            var suma_total = 0;
            for (var i = 1; i <= parseInt(nro); i++) {
                suma_total += parseFloat($(`[name=cuota_monto_${i}]`).val());
                list_json.push({
                    nro: i,
                    fecha: $(`[name=cuota_fecha_${i}]`).val(),
                    monto: $(`[name=cuota_monto_${i}]`).val()
                });
            }
            if(suma_total > pagos_total || suma_total < pagos_total)
            {
                alert("La suma de las cuotas no corresponden al total a pagar");
                return;
            }

            /**Validacion de fechas */
               var venta_fecha_vencimiento = $("[name=fecha_vencimiento").val();
               var venta_fecha_emision = $("[name=fecha_venta]").val();
               var verf = true;
                list_json.forEach(credito_cuota => {
                   if(validarFechas(venta_fecha_emision,credito_cuota.fecha,'==')){
                        alert("Las fechas de las cuotas deben ser mayor a la fecha de emision de comprobante");
                        verf = false;
                    }
                    if(validarFechas(credito_cuota.fecha,venta_fecha_vencimiento,'>')){
                        alert("Las fechas de las cuotas debe ser menor o igual a la fecha de vencimiento");
                        verf = false;
                    }
               });
               if(!verf){
                   return;
               }
            /** */

            $(`[name=monto_credito_total]`).val(pagos_total);
            $(`[name=forma_pago_cuotas]`).val(JSON.stringify(list_json));

            $(`[name=accion]`).click();
            $(`#modalCuotas`).modal('hide');

        },
        /**
         * Buscar producto por codigo, al momento
         * de utilizar un lector de codigo de barras
         * @param codigo
         */
        buscarProductoPorCodigo    :   function(codigo)
        {
            $.ajax({
                url     :   base + "api/get-item-by-code",
                data    :   {'codigo' : codigo},
                type    :   'GET',
                dataType:   'JSON',
                success :   function (data, status, xhr) {
                    if (data.estado === 'ok'){
                        nuevaVenta.itemsAgregar(data.data);
                        nuevaVenta.actualizarVentaDetalle();
                    }
                    // exista o no el producto, se limpia el campo
                    $(".ingresocodigo").val("");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
        /**
         * Redondea decimales
         * @param num
         * @param dec
         * @returns {number}
         */
        roundFloat  :   function(num,dec){
            var d = 1;
            for (var i=0; i<dec; i++){
                d += "0";
            }
            return Math.round(num * d) / d;
        },

        /**
         * Activa el relog que se muestra en la vista
         */
        startTime :   function(){
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = nuevaVenta.checkTime(m);
            s = nuevaVenta.checkTime(s);
            $("input[name=hora_venta]").val(h + ":" + m + ":" + s);
            var t = setTimeout(nuevaVenta.startTime, 500);
        },
        checkTime  :   function(i) {
            if (i < 10) {i = "0" + i;}  // add zero in front of numbers < 10
            return i;
        },
        /**
         * Carga una venta a partir de una formato excel definido
         * Los items de la venta deben de tener sus precios sin igv
         * @param data
         */
        cargarInfoFromExcel :   function(data)
        {
            // establecemos los datos del cliente
            // clienteEstablecerDatos(coti);


            $("#formNuevaVenta [name=cliente_ruc]").val(data.cliente_doc_nro);
            $("#formNuevaVenta [name=cliente_razon_social]").val(data.cliente_razon_social);

            index = 1;
            $(data.items).each (function(i, o){
                var uniqid = index + 100;
                var obj = {};
                obj.index = index;
                obj.id = uniqid;
                obj.codigo =  index.toString().padStart(8, "0");
                obj.nombre = o.nombre;
                obj.unidad = "NIU";//o.unidad
                obj.cantidad = o.cantidad;
                obj.precio_venta = o.valor_venta;
                obj.valor_venta = o.valor_venta;
                obj.igv = 0;
                obj.tipo_moneda =  "PEN";
                obj.precio_total = o.total;
                obj.comentario = "";
                obj.inc_igv = "0";
                obj.afectacion_igv = '10';
                index++;
                nuevaVenta._items.push(obj);

            });
            nuevaVenta.actualizarVentaDetalle();
        },

        actualizarVentaDetalle  :   function()
        {
            nuevaVenta.copiarItemsEnDetalle();
            nuevaVenta.calcularTotalesGlobales();
            nuevaVenta.itemsGenerarTabla();
        },
        copiarItemsEnDetalle   :   function()
        {
            nuevaVenta._detalle = [];
            var item_index = 0;
            $(nuevaVenta._items).each(function(i, o) {
                item_index++;
                var itemDetalle =  {};

                var objItemCalculos                 = nuevaVenta.calcularTotalesByItem(o.id);

                itemDetalle.item_id                 = o.id;
                itemDetalle.item_codigo             = o.codigo;//revisar
                itemDetalle.item_nombre             = o.nombre;
                itemDetalle.item_unidad             = o.unidad;
                itemDetalle.item_index              = item_index;
                itemDetalle.item_comentario         = o.comentario;
                itemDetalle.img_ruta                = o.img_ruta;
                itemDetalle.cantidad                = parseFloat(o.cantidad);
                itemDetalle.afectacion_igv          = o.afectacion_igv;
                itemDetalle.precio_uventa           = parseFloat(objItemCalculos.precio_venta_unitario);
                itemDetalle.valor_venta             = parseFloat(objItemCalculos.valor_venta_unitario);
                itemDetalle.subtotal                = parseFloat(objItemCalculos.valor_venta_total);
                itemDetalle.igv_monto               = parseFloat(objItemCalculos.monto_igv);
                // itemDetalle.isc_monto               = "";
                // itemDetalle.icb_per_monto           = "";
                itemDetalle.precio_total            = parseFloat(objItemCalculos.precio_total);

                itemDetalle.subtotal   = nuevaVenta.roundFloat(itemDetalle.subtotal,2);
                itemDetalle.precio_total  = nuevaVenta.roundFloat(itemDetalle.precio_total,2);
                itemDetalle.igv_monto = nuevaVenta.roundFloat(itemDetalle.igv_monto,2);

                nuevaVenta._detalle.push(itemDetalle);

            });

        },

        calcularTotalesByItem :   function(item_id)
        {
            var itemOriginal    = nuevaVenta._items.find(itemOr => itemOr.id == item_id);
            var objItemTotales = {};

            /**
             * Obtenemos los valores unitarios
             */

            var objValoresUnitarios = nuevaVenta.calcularMontosUnitariosByItem(itemOriginal.id);
            objItemTotales.valor_venta_unitario     = objValoresUnitarios.valor_venta_unitario;
            objItemTotales.precio_venta_unitario    = objValoresUnitarios.precio_venta_unitario;

            /**
             * Obtenemos el valor de venta total
             */
            objItemTotales.valor_venta_total = objItemTotales.valor_venta_unitario *  itemOriginal.cantidad;


            /**
             * Contabilizamos el precio total
             */

            if (itemOriginal.afectacion_igv == '10') {
                objItemTotales.precio_total = objItemTotales.valor_venta_total * 1.18;
                // objItemTotales.precio_total = objItemTotales.precio_venta_unitario * itemOriginal.cantidad;
            }else if(['20','30'].indexOf(itemOriginal.afectacion_igv) !== -1)
            {
                objItemTotales.precio_total = objItemTotales.valor_venta_total;
            }


            /**
             * Calculamos la afectacion igv correspondiente
             */
            if(itemOriginal.afectacion_igv == '10')
            {
                objItemTotales.monto_igv = objItemTotales.valor_venta_total  * 0.18;
                objItemTotales.monto_igv = nuevaVenta.roundFloat(objItemTotales.monto_igv, 2);

            }else if(['20','30'].indexOf(itemOriginal.afectacion_igv) !== -1)
            {
                objItemTotales.monto_igv = 0;

            }else{
                objItemTotales.monto_igv = objItemTotales.valor_venta_total  * 0.18;
                objItemTotales.monto_igv = nuevaVenta.roundFloat(objItemTotales.monto_igv,2);

            }



            return objItemTotales;

        },
        calcularMontosUnitariosByItem(item_id)
        {
            var itemOriginal            = nuevaVenta._items.find(itemOr => itemOr.id == item_id);
            var venta_tipo_moneda       = $('select[name=tipo_moneda]').val();
            var cambio                  = nuevaVenta.tipo_cambio_venta;
            var valoresUnitarios        =   {};

            itemOriginal.precio_venta = parseFloat(itemOriginal.precio_venta);

                if(itemOriginal.tipo_moneda ==='USD' && venta_tipo_moneda === 'PEN')
                {
                    valoresUnitarios.precio_venta_unitario = itemOriginal.precio_venta * cambio;

                }else if(itemOriginal.tipo_moneda === 'PEN' && venta_tipo_moneda === 'USD')
                {
                    valoresUnitarios.precio_venta_unitario = itemOriginal.precio_venta / cambio;
                    valoresUnitarios.precio_venta_unitario = nuevaVenta.roundFloat(valoresUnitarios.precio_venta_unitario,2);
                }else{

                    valoresUnitarios.precio_venta_unitario = itemOriginal.precio_venta;
                }


            if(itemOriginal.inc_igv == '0'){
                valoresUnitarios.valor_venta_unitario = parseFloat(valoresUnitarios.precio_venta_unitario);
            }else{
                valoresUnitarios.valor_venta_unitario = parseFloat(valoresUnitarios.precio_venta_unitario)/ 1.18;
            }


            if(itemOriginal.afectacion_igv == '10')
            {
                valoresUnitarios.precio_venta_unitario = valoresUnitarios.valor_venta_unitario * 1.18;

            }else if(['20','30'].indexOf(itemOriginal.afectacion_igv) !== -1)
            {
                valoresUnitarios.precio_venta_unitario = valoresUnitarios.valor_venta_unitario;
            }else{
                valoresUnitarios.precio_venta_unitario = valoresUnitarios.valor_venta_unitario * 1.18;
            }

            // valoresUnitarios.valor_venta_unitario = valoresUnitarios.valor_venta_unitario.toFixed(2);
            // valoresUnitarios.precio_venta_unitario = valoresUnitarios.precio_venta_unitario.toFixed(2);
            return valoresUnitarios;

        },
        calcularTotalesGlobales()
        {
            nuevaVenta._totales.total = 0;
            nuevaVenta._totales.igvs = 0;
            nuevaVenta._totales.subtotales = 0;

            nuevaVenta._op_gravadas     = 0.0;
            nuevaVenta._op_exoneradas   = 0.0;
            nuevaVenta._op_inafectas    = 0.0;

            $(nuevaVenta._detalle).each(function(i, o) {
                o.subtotal = parseFloat(o.subtotal);
                o.igv_monto = parseFloat(o.igv_monto);
                o.precio_total = parseFloat(o.precio_total);

                switch (o.afectacion_igv){
                    case '10':

                        nuevaVenta._op_gravadas     += o.subtotal ;
                        nuevaVenta._op_gravadas = parseFloat(nuevaVenta._op_gravadas);
                        nuevaVenta._totales.igvs += o.igv_monto;

                        break;
                    case '20':
                        nuevaVenta._op_exoneradas   += o.subtotal ;
                        nuevaVenta._op_exoneradas = parseFloat(nuevaVenta._op_exoneradas);

                        break;
                    case '30':
                        nuevaVenta._op_inafectas    += o.subtotal ;
                        nuevaVenta._op_inafectas = parseFloat(nuevaVenta._op_inafectas);
                        break;
                    default:
                        nuevaVenta._op_gravadas     += o.subtotal ;
                        nuevaVenta._op_gravadas = parseFloat(nuevaVenta._op_gravadas);
                        nuevaVenta._totales.igvs += o.igv_monto;

                        o.afectacion_igv = '10';
                        break;
                }


                /**
                 * Contabilizamos totales
                 */
                nuevaVenta._totales.subtotales += o.subtotal;
                nuevaVenta._totales.total += o.precio_total;



                /**
                 * Formateamos los totales
                 */
                nuevaVenta._totales.igvs.toFixed(2)
                nuevaVenta._totales.subtotales.toFixed(2)
                nuevaVenta._totales.total.toFixed(2);

                if (!nuevaVenta.inc_igv) {
                    nuevaVenta._totales.igvs = 0;
                    nuevaVenta._totales.subtotales = nuevaVenta._op_gravadas + nuevaVenta._op_exoneradas + nuevaVenta._op_inafectas;
                }

            });
            /**Validacion para retirar igv de la venta */
            var tipo_comprobante = $("[name=documento_tipo").val();
            if(tipo_comprobante != undefined && tipo_comprobante == 'NOTAVENTA'){
                if(!nuevaVenta.inc_igv){
                    nuevaVenta._totales.igvs = 0;
                }
            }

            /**
             * Formateamos y contabilizados los totales globales
             */

            nuevaVenta._op_gravadas   = nuevaVenta.roundFloat(nuevaVenta._op_gravadas,2);
            nuevaVenta._op_exoneradas = nuevaVenta.roundFloat(nuevaVenta._op_exoneradas,2);
            nuevaVenta._op_inafectas  = nuevaVenta.roundFloat(nuevaVenta._op_inafectas,2);

            nuevaVenta._totales.subtotales = nuevaVenta._op_gravadas + nuevaVenta._op_exoneradas + nuevaVenta._op_inafectas;
            nuevaVenta._totales.total = nuevaVenta._op_gravadas + nuevaVenta._op_exoneradas + nuevaVenta._op_inafectas + nuevaVenta._totales.igvs;

            nuevaVenta._totales.igvs.toFixed(2)
            nuevaVenta._totales.subtotales.toFixed(2)
            nuevaVenta._totales.total.toFixed(2);

            nuevaVenta.validarUsoDetraccion();
            nuevaVenta.validarUsoCamposMontosGlobales();
        },
        validarUsoDetraccion    :   function()
        {
            var venta_total = parseFloat(nuevaVenta._totales.total);
            var tipo_documento = $("#formNuevaVenta [name=documento_tipo]").val();
            if(venta_total >= 400 && tipo_documento == 'FACTURA')
            {
                $("#link_detracciones").attr('hidden',false);
                nuevaVenta.actualizarMontoDetraccion();
            }else{
                $("#link_detracciones").html(`Detraccion : Sin Especificar <span id="monto_detraccion">Monto : 0</span>`);

                nuevaVenta.codigo_detraccion = '000';
                nuevaVenta.detraccion_porc = 0;
                $("#tipo_detraccion_codigo").val(nuevaVenta.codigo_detraccion);

                $("#link_detracciones").attr('hidden',true);
            }
        },
        validarUsoCamposMontosGlobales :   function()
        {
            if(nuevaVenta._op_gravadas > 0)
            {
                $("#div_op_gravadas").attr('hidden',false);
            }else{
                $("#div_op_gravadas").attr('hidden',true);

            }

            if(nuevaVenta._op_exoneradas > 0)
            {
                $("#div_op_exoneradas").attr('hidden',false);

            }else{
                $("#div_op_exoneradas").attr('hidden',true);

            }

            if(nuevaVenta._op_inafectas > 0)
            {
                $("#div_op_inafectas").attr('hidden',false);

            }else{
                $("#div_op_inafectas").attr('hidden',true);
            }

        },
        actualizarDetraccion    :   function(detraccionObj)
        {
            $("#link_detracciones").html(`Detraccion : ${detraccionObj.nombre} <span id="monto_detraccion" ></span>`);
            nuevaVenta.codigo_detraccion = detraccionObj.codigo;
            nuevaVenta.detraccion_porc = parseFloat(detraccionObj.porcentaje);
            nuevaVenta.actualizarMontoDetraccion();
            $("#tipo_detraccion_codigo").val(detraccionObj.codigo);
        },
        actualizarMontoDetraccion   :   function()
        {

            var venta_total           = parseFloat(nuevaVenta._totales.total);
            var monto_detraccion = venta_total * (parseFloat(nuevaVenta.detraccion_porc) /100);
            $("#monto_detraccion").text(`(${monto_detraccion.toFixed(2)})`);


        },
        abrirModalDetracciones  :   function()
        {
            var codigo_actual_detracciones = $("#tipo_detraccion_codigo").val();
            ModalDetracciones.abrir(codigo_actual_detracciones);
            console.log(codigo_actual_detracciones);
        },
        /**
         * Agrega un item al array de items
         * @param data
         */

        itemsAgregar    :   function(data){
            var existe = false;
            $(nuevaVenta._items).each(function(i,itemOriginal){
                if(itemOriginal.id == data.id){
                    itemOriginal.cantidad += 1;
                    existe = true;
                }
            });



            if(!existe){
                data.index = nuevaVenta._detalle.length +1;
                data.cantidad = 1;
                data.comentario = "";
                console.log(data);
                if(data.unidad != 'ZZ' || (data.unidad == 'ZZ' && data.afectacion_igv == undefined))
                {
                    if(data.inc_igv == "0"){
                        data.afectacion_igv = '20';
                    }else{
                        data.afectacion_igv = '10';
                    }
                }


                data.valor_venta = 0;
                data.igv = 0;
                nuevaVenta._items.push(data);
            }
        },


        /**
         * Este metodo genera la tabla de detalles
         * de los items
         */
        itemsGenerarTabla   :   function()
        {

            var html = "";
            $(nuevaVenta._detalle).each(function(i, o){

                var itemOriginal = nuevaVenta._items.find(item => item.id == o.item_id);
                var precio_venta_original_formateado = nuevaVenta.roundFloat(itemOriginal.precio_venta,2);
                var precio_venta_formateado          = nuevaVenta.roundFloat(o.precio_uventa,2);
                var afectacion_igv = nuevaVenta.getNombreAfectacionIGV(o.afectacion_igv)


                html += "<tr>";
                html += "<td>" + o.item_index + "</td>";
                html += "<td>" + o.item_codigo + "</td>";
                html += "<td>";
                if(o.img_ruta != null && o.img_ruta !== ''){
                    html += `<img src="${base_root}${o.img_ruta}" class="img-fluid" width="64px" height="64px" style="border-radius: 10px" >`;
                }else{
                    html += `<img src="${base_root}media/iconos/placeholder_items.png"  class="img-fluid" width="64px" height="64px" style="border-radius: 10px" >`;
                }
                html += "</td>";
                html += `<td>${o.item_nombre}<br/>`;
                html += `<small><b>${afectacion_igv}</b></small><br/>`;
                html += "<input disabled placeholder='Comentario' type='text' class='item_data item_comentario_"+o.id+"' value='"+o.item_comentario+"' data-id='"+o.id+"' />";
                html += "</td>";
                html += "</td>";
                html += "<td>" + o.item_unidad + "</td>";
                html += "<td class='text-center'><input disabled type='number' class='item_data item_cantidad_"+o.id+"' value='"+itemOriginal.cantidad+"'  data-id='"+o.id+"'  /></td>";
                html += `<td>
                        <input disabled class='item_data item_precio_${o.id}' type='number' value='${precio_venta_formateado}' data-id='${o.id}'><br/>
                        <small>
                            ${ (itemOriginal.inc_igv == '0') ? 'No Inc. IGV': 'Inc. IGV' }
                        </small> <br/>
                        <small>
                            ${precio_venta_original_formateado} (${itemOriginal.tipo_moneda})
                        </small>
                 </td>`;
                html += "<td> <i class='fa fa-coins fa-fw'></i> Subtotal  <br>";
                html += "<i class='fa fa-sort-amount-up fa-fw'></i> IGV  <br>";
                html +=  "<i class='fa fa-wallet fa-fw'></i> Total  </td>";
                html += ` <td>
                                ${o.subtotal.toFixed(2)} <br>
                                ${o.igv_monto.toFixed(2)} <br>
                                ${o.precio_total.toFixed(2)} <br>
                          </td>       `;
                html += "<td>";
                html += "<i class='fas fa-edit item_actualizar' data-id='"+itemOriginal.id+"'></i> &nbsp;";
                html += "<i class='fas fa-trash item_eliminar' data-id='"+itemOriginal.id+"' ></i>";
                html += "</td>";
                html += "</tr>";

            });

            $("tbody#detalle").html(html);

            this.llenarTotalesVenta();
            this.actualizarEventosItemsDetalle();

            // guardamos en el campo oculto el detalle
            var str_data = JSON.stringify(nuevaVenta._detalle);
            //alert(str_data);
            $(".detalle_items").val(str_data);
            $(".ingresocodigo").val('');
        },
        getNombreAfectacionIGV  :   function(afectacion)
        {
          if(afectacion == '10')
          {
              return 'GRAVADO';
          }else if(afectacion == '20')
          {
              return 'EXONERADO';
          }else if(afectacion == '30')
          {
              return 'INAFECTO';
          }else{
              return 'GRAVADO';
          }

        },
        /**
         * Asigna los totales de las ventas
         * a los inputs correspondientes
         */
        llenarTotalesVenta :    function()
        {
            $("[name=op_gravadas]").val(nuevaVenta._op_gravadas.toFixed(2));
            $("[name=op_exoneradas]").val(nuevaVenta._op_exoneradas.toFixed(2));
            $("[name=op_inafectas]").val(nuevaVenta._op_inafectas.toFixed(2));


            $(".precio_total").val(this._totales.total.toFixed(2));
            $(".mto_igv").val(this._totales.igvs.toFixed(2));
            $(".mto_subtotal").val(this._totales.subtotales.toFixed(2));
            $("[name=pago_inicial]").val(this._totales.total.toFixed(2));

        },
        /**
         * Asigna los eventos asociados a cada item
         * del detalle al momento de actualizar la tabla de items
         */
        actualizarEventosItemsDetalle   :   function()
        {
            $(".item_eliminar").click(function(){
                var id = $(this).attr("data-id");
                nuevaVenta.itemEliminarRegistro(id);
            });

            $(".item_actualizar").click(function()
            {

                var id = $(this).attr("data-id");
                var itemOriginal = nuevaVenta._items.find(item => item.id == id);

                var nombre          = itemOriginal.nombre;
                var comentario      = itemOriginal.comentario;
                var cantidad        = itemOriginal.cantidad;
                var precio          = itemOriginal.precio_venta;
                var afectacion_igv  = itemOriginal.afectacion_igv;
                var unidad          = itemOriginal.unidad;
                 console.log(itemOriginal);

                FormEditarProductoDetalle.es_servicio = unidad == 'ZZ' ? 1 : 0;
                FormEditarProductoDetalle.abrir(id,nombre,comentario,cantidad,precio,afectacion_igv);
            })

        },
        itemEliminarRegistro    :   function(id){
            var _items2 = [];
            for( var i = 0; i <  nuevaVenta._items.length; i++){
                if (  nuevaVenta._items[i].id != id) {
                    _items2.push(nuevaVenta._items[i]);

                }
            }
            nuevaVenta._items = _items2;


            nuevaVenta.actualizarVentaDetalle();
        },
        /**
         * Actualiza los campos que pueden cambiar
         * en el detalle, como precio, cantidad y el comentario de cada producto
         */
        itemActualizarRegistro  :   function(data){

            $(nuevaVenta._items).each(function(i,original){
                if(original.id == data.id)
                {
                    original.nombre = original.unidad == 'ZZ' ? data.nombre : original.nombre;
                    original.comentario = data.comentario;
                    original.afectacion_igv = data.afectacion_igv;
                    original.precio_venta = data.precio;
                    original.cantidad = data.cantidad;
                }
            });
            nuevaVenta.actualizarVentaDetalle();

        },
        /**
         * Coloca el boton solicitado en estado de carga
         */
        setButtonLoad   :   function(icon_id,btn_id,class_to_replace)
        {
            $(`#${icon_id}`).removeClass(`${class_to_replace}`);
            $(`#${icon_id}`).addClass('spinner-border');
            $(`#${icon_id}`).addClass('spinner-border-sm');
            $(`#${btn_id}`).prop('disabled',true);

        },
        stopButtonLoad      :   function(icon_id,btn_id,class_to_set)
        {
            $(`#${icon_id}`).removeClass('spinner-border');
            $(`#${icon_id}`).removeClass('spinner-border-sm');
            $(`#${icon_id}`).addClass(`${class_to_set}`);
            $(`#${btn_id}`).prop('disabled',false);
        },
        setTipoCambio(cambio)
        {
          nuevaVenta.tipo_cambio_venta = parseFloat(cambio);
          $('#linkTipoCambio').text(`Cambio Venta : ${nuevaVenta.tipo_cambio_venta}`);
          nuevaVenta.actualizarVentaDetalle();
        },

        /**
         * Extrae los datos de las personas o empresas
         * y llena sus datos correspondientes
         */
        consultaDniRucAjax  :   function(){
            var ruc = $('input[name=cliente_ruc]').val();
            $("#clienteEstadoBusqueda1").html("<i class='fas fa-spinner fa-spin fa-fw'></i>");
            var endpoint = base + "sunat-fe/api-consulta-dni-ruc"
            // alert(endpoint)

            $.ajax({
                url     :   endpoint,
                data    :   {'documento' : ruc},
                type    :   'GET',
                dataType:   'JSON',
                success :   function (data, status, xhr) {// success callback function

                    if (data.success){

                        if(data.result.cliente_doc_tipo == '1'){
                            if(nuevaVenta._is_allowed_boleta === 1){
                                $("[name=documento_tipo]").val("BOLETA")
                            }
                            $("[name=cliente_persona_tipo]").val("NATURAL")
                            nuevaVenta._persona = data.result
                        }else{
                            if(nuevaVenta._is_allowed_factura === 1){
                                $("[name=documento_tipo]").val("FACTURA")
                            }
                            $("[name=cliente_persona_tipo]").val("JURIDICA")
                            nuevaVenta._cuenta = data.result
                        }
                        nuevaVenta.setSeries();

                        $('input[name=cliente_razon_social]').val(data.result.razon_social);
                        $('input[name=cliente_domicilio_fiscal]').val(data.result.direccion);
                        $('input[name=cliente_id]').val(""); // si es vacio, es para crear

                        $("#clienteEstadoBusqueda1").html("<i class='fas fa-exchange-alt fa-fw'></i>");
                        $("#clienteEstadoBusqueda1").hide();

                        $("#clienteEstadoBusqueda2").show()


                        // luego de obtener datos, procedemos a guardar el cliente
                        if(_autosavecliente == '1') {
                            nuevaVenta.guardarCliente();
                        }

                    }
                    else{
                        $(".estado_busqueda").html("<i class='fas fa-times fa-fw'></i>");

                    }
                }
            });
        },
        /**
         * Procede a guardar el cliente segun
         * su tipo
         */
        guardarCliente  :   function()
        {

            $("#clienteEstadoBusqueda2").html("<i class='fas fa-spinner fa-spin fa-fw'></i>");
            var tipo_cliente = $('input[name=cliente_persona_tipo]').val();

            if (tipo_cliente === 'NATURAL'){

                nuevaVenta.guardarClienteNatural();

            }else {

                nuevaVenta.guardarClienteJuridico();

            }

        },
        /**
         * Registra a los cliente naturales
         * es decir, con DNI
         */
        guardarClienteNatural   :   function ()
        {
            // obtenemos la información modificada
            nuevaVenta._persona.dni = $("input[name=cliente_ruc]").val();
            nuevaVenta._persona.direccion = $("input[name=cliente_domicilio_fiscal]").val();
            console.log(nuevaVenta._persona)
            var endpoint = base + "personas/save"
            $.ajax({
                url     :   endpoint,
                data    :   nuevaVenta._persona,
                type    :   'POST',
                success :   function (data, status, xhr) {
                    console.log(data);
                    $("#clienteEstadoBusqueda2").html("<i class='fas fa-save fa-fw'></i>");
                    $("#clienteEstadoBusqueda2").hide();
                    $("#clienteEstadoBusqueda3").show();
                    $('#formNuevaVenta [name=cliente_id]').val(data.data.id);

                    nuevaVenta.clienteEstablecerDatos({
                        id: data.data.id,
                        cliente_doc_tipo: '1',
                        cliente_doc_numero: data.data.dni,
                        cliente_razon_social  : `${data.data.nombres} ${data.data.apellidos}`,
                        cliente_domicilio_fiscal  : `${data.data.direccion}`
                    });

                },
            });
        },
        /**
         * Registra a los cliente juridicos
         * es decir, con RUC
         */
        guardarClienteJuridico  :   function()
        {
            nuevaVenta._cuenta.ruc = $("input[name=cliente_ruc]").val();
            nuevaVenta._cuenta.domicilio_fiscal = $("input[name=cliente_domicilio_fiscal]").val();
            console.log(nuevaVenta._cuenta);
            var endpoint = base + "cuentas/save-from-json"
            $.ajax({
                url     :   endpoint,
                data    :   nuevaVenta._cuenta,
                type    :   'POST',
                success :   function (data, status, xhr) {

                    $("#clienteEstadoBusqueda2").html("<i class='fas fa-save fa-fw'></i>");
                    $("#clienteEstadoBusqueda2").hide();
                    $("#clienteEstadoBusqueda3").show();
                    $('#formNuevaVenta [name=cliente_id]').val(data.data.id);

                    nuevaVenta.clienteEstablecerDatos({
                        id: data.data.id,
                        cliente_doc_tipo: '6',
                        cliente_doc_numero: data.data.ruc,
                        cliente_razon_social  : data.data.razon_social,
                        cliente_domicilio_fiscal  : data.data.domicilio_fiscal
                    });

                },
            });
        },
        /**
         * Establece los datos del cliente
         * que ha sido seleccionado desde el
         * autocomplete
         * @param item
         */
        clienteEstablecerDatos  :   function(item){

            $(".limpiar_formulario").show();
            nuevaVenta.fillTiposDocumentos(item.cliente_doc_tipo)

            if(item.cliente_doc_tipo == '6'){
                $("#formNuevaVenta [name=cliente_persona_tipo]").val("JURIDICA");
            }else if(item.cliente_doc_tipo == '1'){
                $("#formNuevaVenta [name=cliente_persona_tipo]").val("NATURAL");
            }


            var cid = $("#formNuevaVenta [name=cliente_id]").val();
            if (cid == "") {
                $("#formNuevaVenta [name=cliente_id]").val(item.id);
            }
            $("#formNuevaVenta [name=cliente_doc_tipo]").val(item.cliente_doc_tipo);
            $("#formNuevaVenta [name=cliente_doc_numero]").val(item.cliente_doc_numero);

            $("#formNuevaVenta [name=cliente_ruc]").val(item.cliente_doc_numero);
            $("#formNuevaVenta [name=cliente_razon_social]").val(item.cliente_razon_social);
            $("#formNuevaVenta [name=cliente_domicilio_fiscal]").val(item.cliente_domicilio_fiscal);

            // procedemos a bloquear los controles
            $("#formNuevaVenta [name=cliente_ruc]").attr("readonly",true);
            $("#formNuevaVenta [name=cliente_razon_social]").attr("readonly",true);
            $("#formNuevaVenta [name=cliente_domicilio_fiscal]").attr("readonly",false);

            $("#clienteEstadoBusqueda1").hide();
            $("#clienteEstadoBusqueda2").hide();
            $("#clienteEstadoBusqueda3").show();

            nuevaVenta.setSeries();

        },
        hideModalGuia   :   function() {
            $(`#selGuia`).modal('hide');
        },

        showModalCoti   :   function() {
            $(`[name=numero_cotizacion]`).val('');
            $(`#selCoti`).modal('show');
        },

// cargamos la cotizacion en la venta
    cargarCotizacion    :   function(){
        var cotiid = $("input[name=numero_cotizacion]").val();

        $.ajax({
            url     :   base + "cotizaciones/get-one/" + cotiid,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data);
                if(data.success == true){
                    $("#global_spinner").attr('hidden',false);
                    nuevaVenta.cargarInfoFromCoti(data.data);
                    $(`#selCoti`).modal('hide');
                    $("#global_spinner").attr('hidden',true);
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
    cargarInfoFromCoti  :   function(coti){
            console.log("Entra al procedimiento de cargar la coti");

        // datos principales de la venta
        $("[name=cotizacion_id]").val(coti.id);

        // establecemos los datos del cliente
        nuevaVenta.clienteEstablecerDatos(coti);

        $("[name=comentarios]").val(coti.comentarios);
        $("[name=codvendedor]").val(coti.codvendedor);
        $(`select[name=tipo_moneda] option[value=${coti.tipo_moneda}]`).attr('selected',true);

        if(typeof  coti.items_originales != 'undefined' && coti.items_originales != null)
        {

            nuevaVenta._items = JSON.parse(coti.items_originales) ;

        }else{
            //cotizacion_registros
            index = 1;

            $(coti.cotizacion_registros).each (function(i, o){
                var obj = {};
                obj.index = index;
                obj.id = o.item_id;
                obj.codigo = o.item_codigo;
                obj.nombre = o.item_nombre;
                obj.unidad = o.item_unidad;
                obj.cantidad = o.cantidad;
                obj.precio_venta = o.precio_uventa;
                obj.valor_venta = 0;
                obj.igv = 0;
                obj.tipo_moneda =  o.tipo_moneda;
                obj.precio_total = o.precio_total;
                obj.comentario = o.item_comentario;
                obj.inc_igv = "1";
                obj.afectacion_igv = '10';

                index++;
                // nuevaVenta._detalle.push(obj);
                nuevaVenta._items.push(obj);
            });
        }

        nuevaVenta.actualizarVentaDetalle();
        // nuevaVenta.itemsGenerarTabla();
    },
    cargarNotaVenta    :   function(notaventa_id){
        // var cotiid = $("input[name=numero_cotizacion]").val();
        // var notaventa_id = ;
        $.ajax({
            url     :   base + "ventas/get-one-venta-reg/" + notaventa_id,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data);
                if(data.success == true){
                    $("#global_spinner").attr('hidden',false);
                    nuevaVenta.cargarInfoFromNotaVenta(data.data);
                    $(`#selCoti`).modal('hide');
                    $("#global_spinner").attr('hidden',true);
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
    cargarInfoFromNotaVenta :   function(notaventa){
        nuevaVenta.clienteEstablecerDatos({
            id                      : notaventa.cliente_id,
            cliente_doc_tipo        : notaventa.cliente_doc_tipo,
            cliente_doc_numero      : notaventa.cliente_doc_numero,
            cliente_razon_social    : notaventa.cliente_razon_social,
            cliente_domicilio_fiscal: notaventa.cliente_domicilio_fiscal
        });

        $("[name=comentarios]").val(notaventa.comentarios);
        // $("[name=codvendedor]").val(coti.codvendedor);
        $(`select[name=tipo_moneda] option[value=${notaventa.tipo_moneda}]`).attr('selected',true);

            //cotizacion_registros
            index = 1;

            $(notaventa.venta_registros).each (function(i, o){
                var obj = {};
                obj.index = index;
                obj.id = o.item_id;
                obj.codigo = o.item_codigo;
                obj.nombre = o.item_nombre;
                obj.unidad = o.item_unidad;
                obj.cantidad = parseFloat(o.cantidad);
                obj.precio_venta = o.precio_uventa;
                obj.valor_venta = 0;
                obj.igv = 0;
                obj.tipo_moneda =  'PEN';//Falta mejorar, en el caso sea tambien USD
                obj.precio_total = o.precio_total;
                obj.comentario = o.item_comentario;
                obj.inc_igv = o.afectacion_igv == '10' ? '1' : '0';
                obj.afectacion_igv = o.afectacion_igv;

                index++;
                // nuevaVenta._detalle.push(obj);
                nuevaVenta._items.push(obj);
            });


        nuevaVenta.actualizarVentaDetalle();
    },
    clienteLimpiarDatos :   function(){

        nuevaVenta.fillTiposDocumentos("");

        $("#clienteEstadoBusqueda1").show();
        $("#clienteEstadoBusqueda2").hide();
        $("#clienteEstadoBusqueda3").hide();

        //establecemos valores en el form
        $("#formNuevaVenta input[name=cliente_ruc]").val("");
        $("#formNuevaVenta input[name=cliente_razon_social]").val("");
        $("#formNuevaVenta input[name=cliente_domicilio_fiscal]").val("");
        $("#formNuevaVenta input[name=cliente_doc_numero]").val("");
        $("#formNuevaVenta [name=persona_id]").val(0);
        $("#formNuevaVenta [name=cuenta_id]").val(0);

        $("input[name=cliente_ruc]").attr("readonly",false);
        $("input[name=cliente_razon_social]").attr("readonly",false);
        $("input[name=cliente_domicilio_fiscal]").attr("readonly",false);
        nuevaVenta.setSeries();
    },
    mostrarCheck    :   function() {
        var tipo_doc = $('[name=documento_tipo]').val();
        if (tipo_doc == "NOTAVENTA") {
            $('#check_igv').css('display', 'block');
            if ($('[name=igv_input]').prop('checked')) {
                nuevaVenta.inc_igv = true;
            } else {
                nuevaVenta.inc_igv = false;
            }
        } else {
            $('#check_igv').css('display', 'none');
            nuevaVenta.inc_igv = true;
        }

        nuevaVenta.actualizarVentaDetalle();
    },

    changeCheckIGV  :   function() {
        nuevaVenta.inc_igv = $('[name=igv_input]').prop('checked');

        nuevaVenta.actualizarVentaDetalle();
    },

    /**
     * Abre el DataTable de busqueda de productos
     */
    abrirBuscadorProductos  :   function(){
        var producto = $("[name=nombre_producto]").val();
        newItemsList.abrir(producto);
    },

    /***************** mantenimiento para los servicios ++++++++++*/
    itemAddServicio :   function(itemServicioObj){

        nuevaVenta.itemsAgregar({
            'id'            : itemServicioObj.id === "" ? nuevaVenta.serv_id : itemServicioObj.id,
            'inc_igv'       : itemServicioObj.serv_inc_igv,
            'codigo'        : itemServicioObj.codigo,
            'cantidad'      : itemServicioObj.cantidad,
            'unidad'        : itemServicioObj.unidad,
            'precio_venta'  : itemServicioObj.precio,
            'nombre'        : itemServicioObj.servicio,
            'tipo_moneda'   : itemServicioObj.tipo_moneda,
            'afectacion_igv': itemServicioObj.afectacion_igv
        })

        nuevaVenta.serv_id++;

        nuevaVenta.actualizarVentaDetalle();
        $("#formServicio").modal("hide");
    },
    abrirModalServicio  :   function() {

        GlobalServicios.abrir();
    },

    // cargar desde guia de remision
    cargarGuirem    :   function(){
        var guiarem = $("input[name=guia_remision]").val();

        $.ajax({
            url     :   base + "sunat-fe-guiarem-remitentes/get-one-by-code/" + guiarem,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data);
                if(data.success == true){
                    $("#global_spinner").attr('hidden',false);
                    nuevaVenta.cargarInfoFromGuiarem(data.data);
                    $(`#selGuia`).modal('hide');
                    $("#global_spinner").attr('hidden',true);
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
    cargarInfoFromGuiarem  :   function(guiarem){

        // establecemos los datos del cliente
        nuevaVenta.clienteEstablecerDatos({
            id : guiarem.cliente_id,
            cliente_doc_tipo : guiarem.cliente_tipo_doc,
            cliente_doc_numero : guiarem.cliente_num_doc,
            cliente_razon_social : guiarem.cliente_razon_social,
            cliente_domicilio_fiscal : guiarem.llegada_direccion,
        });

        try {
            var index = 1;
            nuevaVenta._detalle = [];
            $(guiarem.guia_registros).each (function(i, data){
                data.index = nuevaVenta._detalle.length +1;

                data.comentario = "";
                if(data.unidad != 'ZZ' || (data.unidad == 'ZZ' && data.afectacion_igv == undefined))
                {
                    if(data.inc_igv == "0"){
                        data.afectacion_igv = '20';
                    }else{
                        data.afectacion_igv = '10';
                    }
                }

                data.valor_venta = 0;
                data.igv = 0;
                nuevaVenta._items.push(data);

            });
        } catch (error) {
            nuevaVenta._detalle = [];
        }
        nuevaVenta.actualizarVentaDetalle();
    },
    setSeries:  function(){
        var doc_tipo = $("[name=documento_tipo]").val();
        var estab = $("[name=establecimiento_id]").val();
        $.ajax({
            url     :   API_FE + `/sunat-fe-series/get-series-by-almacen?tipo=${doc_tipo}&establecimiento_id=${estab}`,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                if(data.success == true){
                    var str = '';
                    data.data.forEach(e => {    
                        str += `<option value="${e.serie}"> ${e.serie} </option>`
                    })
                    $("[name=documento_serie]").html(str)
                }else{
                    alert(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function (){
                nuevaVenta.mostrarCheck();
                nuevaVenta.verificarComprobante();
            }
        });
    },
    setEstablecimientos:  function(){
        $.ajax({
            url     :   API_FE + "/establecimientos/",
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                if(data.success == true){
                    var str = '';
                    data.data.forEach(e => {    
                        str += `<option value="${e.id}"> ${e.nombre} </option>`
                    })
                    $("[name=establecimiento_id]").html(str)
                    $("[name=establecimiento_id]").val(establecimiento_default)
                }else{
                    alert(data.message);
                }
                nuevaVenta.setSeries();

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    fillTiposDocumentos :   function(cliente_doc_tipo){

        var html = ""
        if (cliente_doc_tipo == '1'){
            html += "<option value='BOLETA'>BOLETA</option>"
            html += "<option value='NOTAVENTA'>NOTA DE VENTA</option>"
        }else{
            html += "<option value='FACTURA'>FACTURA</option>"
            html += "<option value='BOLETA'>BOLETA</option>"
            html += "<option value='NOTAVENTA'>NOTA DE VENTA</option>"
        }

        $("[name=documento_tipo]").html(html);
    },
};
