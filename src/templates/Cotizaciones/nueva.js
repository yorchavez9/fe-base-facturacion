

$(document).ready(function(){

    GlobalFormTipoCambio.init();
    GlobalFormTipoCambio.callback = function(cambio)
    {
        nuevaCotizacion.setTipoCambio(cambio);
        if (nuevaCotizacion._coti_id != "") {
            $("input[name=numero_cotizacion]").val(nuevaCotizacion._coti_id);
            $("input[name=cotizacion_id]").val(nuevaCotizacion._coti_id);
            nuevaCotizacion.cargarCotizacion();
        }
    }

    FormVentaDesdeExcel.callback = function(r){
        nuevaCotizacion.cargarInfoFromExcel(r.data);
    };

    FormEditarProductoDetalle.callback = function(item)
    {
        nuevaCotizacion.itemActualizarRegistro(item);
    };


    nuevaCotizacion.init(
        {
            _doctipo_default     : _doctipo_default,
            _coti_id             :   _coti_id,
            servicios            :   servicios,
            last_tipo_moneda     :  $('select[name=tipo_moneda]').val()

            }

    );



    // nuevaCotizacion.consultarTipoCambio();

    // $('#tipo_cambio_venta').keypress(function(e) {
    //     if(e.which === 13) {
    //         nuevaCotizacion.actualizarPrecioProductos();
    //     }
    // });
    $('select[name=tipo_moneda]').focus(function(){
        // alert(this.defaultValue);
        nuevaCotizacion.last_tipo_moneda = $('select[name=tipo_moneda]').val();
    });
    $('select[name=tipo_moneda]').change(function(){
        nuevaCotizacion.actualizarCotizacionDetalle();
        $('select[name=tipo_moneda]').blur();
    });

    GlobalServicios.init();
    GlobalServicios.callback = function(itemServicioObj)
    {
        nuevaCotizacion.itemAddServicio(itemServicioObj);
    }

    //
    // $('#tipo_cambio_venta').change(function(){
    //     nuevaCotizacion.actualizarPrecioProductos();
    // });

    // inicializamos el reloj
    nuevaCotizacion.startTime();

    // paramos la ejecucion de la tecla ENTER
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    // configuramos el datepicker para cambiar las fechas
    $("[name=fecha_venta]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
    $("[name=fecha_vencimiento]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });

    $("input[name=cliente_ruc]").autocomplete({
        serviceUrl: base + 'cuentas/api-consulta-clientes',
        onSelect: function (item) {
            nuevaCotizacion.clienteEstablecerDatos(item);
        }
    });

    // enter en la tecla de lectura de codigo de barras
    // enter en la tecla de lectura de codigo de barras
    $(".ingresocodigo").on('keypress', function(e) {
        var codigo = $(this).val();
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            nuevaCotizacion.buscarProductoPorCodigo(codigo);
        }
    });

    // enter en la tecla de lectura de codigo de barras
    $(".ingresoproducto").on('keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            nuevaCotizacion.abrirBuscadorProductos()

        }
    });

    nuevaCotizacion._table = $("#tablafiltro").DataTable({
        'select'    : true,
        'searching' : false,
        'paging' : true,
        'pageLength':10,
        'lengthChange': false,
        "language": {
            "url": base_root + "/assets/data-tables/es.json"
        },

    });

    newItemsList.callback = function(itemObj){
        nuevaCotizacion.itemsAgregar(itemObj);
        nuevaCotizacion.actualizarCotizacionDetalle();
    }


    $('#tablafiltro tbody').on( 'click', 'tr', function () {
        console.log( nuevaCotizacion._table.row( this ).data() );
        var rowdata =  nuevaCotizacion._table.row( this ).data();
        nuevaCotizacion.buscarProductoPorCodigo(rowdata[2]);
    } );

    $("[name=documento_numero]").mask("Z000-00000000",{
        translation: {
            'Z': {
                pattern: /[A-Z]/, optional: true
            }}
    });
    $("[name=pago_inicial]").mask("#00.00",{reverse:true});

    // bind para limpiar datos
    $(".limpiar_formulario").click(function(){
        nuevaCotizacion.clienteLimpiarDatos();
    });

    $("[name=documento_tipo]").change(function(){
        var tipo =  $("[name=documento_tipo]").val();
        switch (tipo){
            case 'NOTAVENTA' :
                $("#documento_serie").parent().hide();
                $("#clienteTipoDocInput").attr("placeholder", 'RUC o DNI');
                break;
            case 'FACTURA':
                $("#documento_serie").parent().show();
                $("#clienteTipoDocInput").attr("placeholder", 'RUC');
                break;
            case 'BOLETA':
                $("#documento_serie").parent().show();
                $("#clienteTipoDocInput").attr("placeholder", 'DNI');
                break
        }
    });


    $("[name=documento_tipo]").val(nuevaCotizacion._doctipo_default);
    $("[name=documento_tipo]").change();

    // binding
    $("#campoBusqueda2").on("keyup change", function(){
        var value = $("#campoBusqueda2").val();
        $("#campoBusqueda1").val(value);
    });



    $('[name=dcto_percent]').keyup(function () {
        var total = parseFloat($('[name=total]').val());
        var dcto_percent = parseFloat($('[name=dcto_percent]').val());
        var dcto_monto = total * dcto_percent / 100.0;
        var nuevo_total = total - dcto_monto;
        nuevo_total = isNaN(nuevo_total) ? total : nuevo_total;

        $('[name=dcto_monto]').val(dcto_monto.toFixed(2));
        $('[name=nuevo_total]').val(nuevo_total.toFixed(2));
        $('[name=pago_inicial]').val(nuevo_total.toFixed(2));
    });

    $('[name=dcto_monto]').keyup(function () {
        var total = parseFloat($('[name=total]').val());
        var dcto_monto = parseFloat($('[name=dcto_monto]').val());
        var dcto_percent = 100.0 * dcto_monto / total;
        var nuevo_total = total - dcto_monto;
        nuevo_total = isNaN(nuevo_total) ? total : nuevo_total;

        $('[name=dcto_percent]').val(dcto_percent.toFixed(2));
        $('[name=nuevo_total]').val(nuevo_total.toFixed(2));
        $('[name=pago_inicial]').val(nuevo_total.toFixed(2));
    });

    // nuevaCotizacion.formServicioPoblarUnidadesMedida();

    // if (nuevaCotizacion._coti_id != "") {
    //     $("input[name=numero_cotizacion]").val(nuevaCotizacion._coti_id);
    //     $("input[name=cotizacion_id]").val(nuevaCotizacion._coti_id);
    //     nuevaCotizacion.cargarCotizacion();
    // }

    $("#formNuevaVenta #btnGuardarImprimirCoti").click(function() {

        nuevaCotizacion.imprimir_coti = 1;
    });

});



var nuevaCotizacion = {
    _detalle : new Array(),
    _items:[],
    _op_gravadas    : 0,
    _op_inafectas   : 0,
    _op_exoneradas  : 0,
    _cuenta : {},
    _persona : {},
    _totales : {},
    _table : null,
    _doctipo_default    :   "",
    last_tipo_moneda    :   "",
    servicios           :   null,
    _coti_id            :   0,
    inc_igv : true,
    serv_id : 100000,
    tipo_cambio_venta   : 1,
    base_path            :  `${base}cotizaciones/`,
    imprimir_coti       :   0,

    init        :   function(data)
    {
        nuevaCotizacion._doctipo_default    = data._doctipo_default;
        nuevaCotizacion._coti_id            = data._coti_id;
        nuevaCotizacion.servicios           = data.servicios;
        nuevaCotizacion.last_tipo_moneda    = data.last_tipo_moneda;
    },
    guardar :   function(e)
    {

        e.preventDefault();

        if(!this.validacionesNuevaCotizacion())
        {
            return;
        }


        var str_data = JSON.stringify(nuevaCotizacion._detalle);
        $(".detalle_items").val(str_data);
        var str_items_originales = JSON.stringify(nuevaCotizacion._items);
        $("#reg_items_originales").val(str_items_originales);
        /**
         * Todo este metodo falta modificar
         *
         *
         * */
        GlobalSpinner.mostrar();

        var form = new FormData( document.getElementById("formNuevaVenta"));
        var endpoint = `${nuevaCotizacion.base_path}nueva`;
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
                console.log(data);
                console.log(nuevaCotizacion.redirect_facturas);
                if(data.success){
                    if(nuevaCotizacion.imprimir_coti == 1)
                    {
                        location.href = `${nuevaCotizacion.base_path}procesar-coti/${data.data.id}` ;
                    }else{
                        location.href = nuevaCotizacion.base_path ;
                    }
                }else{
                    console.log(data);

                    alert(data.message);
                    //location.reload();
                }

            },
            error:function( xhr , ajaxOptions , thrownError )
            {

                alert("no entra al success");
                alert(xhr.status);
                alert(thrownError);
                location.reload();

            },
            complete: (a,b,c)  =>     {
                GlobalSpinner.ocultar();
            }
        })
    },

    validacionesNuevaCotizacion : function()
    {
        var tipodoc = $("#formNuevaVenta [name=documento_tipo]").val();
        var clieruc = $("#formNuevaVenta [name=cliente_ruc]").val();

        nuevaCotizacion.bloquearBoton();

        if (!this.validarTipoDocumentos(tipodoc, clieruc)            ||
            !this.validarCantidadItems()                             ||
            !this.validarIGV()                                       ||
            !this.validarFecVen()
        )
        {
            nuevaCotizacion.desbloquearBoton();
            return false;
        }
        return  true;
    },
    validarTipoDocumentos    :   function(tipodoc,clieruc)
    {

        if (tipodoc === 'FACTURA' && clieruc.length < 11){
            alert("No Puede emitir una factura sin un RUC válido");
            $("#formNuevaVenta [name=cliente_ruc]").focus()

            return false;
        }
        return true;
    },
    validarCantidadItems    :   function()
    {
        if (nuevaCotizacion._detalle.length == 0) {
            alert("Ingrese al menos un producto");
            return false;
        }
        return true;
    },
    validarIGV  :   function()
    {
        if (!nuevaCotizacion.inc_igv) {
            $(nuevaCotizacion._detalle).each(function (i, o) {
                o.precio_venta *= 1.18;
                o.precio_total = (o.precio_venta * o.cantidad);
                o.precio_total = nuevaCotizacion.roundFloat(o.precio_total, 2);
            });

            var str_data = JSON.stringify(nuevaCotizacion._detalle);
            $(".detalle_items").val(str_data);
        }
        return true;
    },
    validarFecVen  :   function()
    {
        return true;
    },

    /**
     * Bloquea el boton de guardado de la venta
     */
    bloquearBoton   :  function() {
        //$('#botones_venta').hide();
        $('#btnGuardarCoti').hide();
        $('#btnGuardarImprimirCoti').hide();
    },

    /**
     * Desbloquea el boton de guardado de la venta
     */
    desbloquearBoton    :   function() {
        $('#btnGuardarCoti').show();
        $('#btnGuardarImprimirCoti').show();
    },

    // actualizarPrecioProductos :function(){
    //     var cambio_venta = parseFloat($('#tipo_cambio_venta').val());
    //     if(nuevaCotizacion._items.length > 0 && typeof cambio_venta != 'undefined' && cambio_venta > 0 ) {
    //         nuevaCotizacion.itemsGenerarTabla();
    //     }
    // },

    actualizarDescuentos:function() {
        var total = parseFloat($('[name=total]').val());
        var dcto_percent = parseFloat($('[name=dcto_percent]').val());
        var dcto_monto = total * dcto_percent / 100.0;
        var nuevo_total = total - dcto_monto;
        nuevo_total = isNaN(nuevo_total) ? total : nuevo_total;

        $('[name=dcto_monto]').val(dcto_monto.toFixed(2));
        $('[name=nuevo_total]').val(nuevo_total.toFixed(2));
        $('[name=pago_inicial]').val(nuevo_total.toFixed(2));
    },


    abrirBuscadorProductos :function(){
        var producto = $("[name=nombre_producto]").val();
        newItemsList.abrir(producto);
        // $("#modalBuscadorProductos").modal("show");
        // $("#campoBusqueda2").val(producto)
    },

    clienteLimpiarDatos :function(){
        nuevaCotizacion.fillTiposDocumentos("");

        $("#clienteEstadoBusqueda1").show();
        $("#clienteEstadoBusqueda2").hide();
        $("#clienteEstadoBusqueda3").hide();



        //establecemos valores en el form
        $("#formNuevaVenta input[name=cliente_ruc]").val("");
        $("#formNuevaVenta input[name=cliente_razon_social]").val("");
        $("#formNuevaVenta input[name=cliente_domicilio_fiscal]").val("");
        $("#formNuevaVenta [name=persona_id]").val(0);
        $("#formNuevaVenta [name=cuenta_id]").val(0);

        $("input[name=cliente_ruc]").attr("readonly",false);
        $("input[name=cliente_razon_social]").attr("readonly",false);
        $("input[name=cliente_domicilio_fiscal]").attr("readonly",false);

    },

    clienteEstablecerDatos :function(item){
        console.log(item);


        $(".limpiar_formulario").show();

        nuevaCotizacion.fillTiposDocumentos(item.cliente_doc_tipo)
        if(item.cliente_doc_tipo == '6'){
            $("#formNuevaVenta [name=cliente_persona_tipo]").val("JURIDICA");
        }else if(item.cliente_doc_tipo == '1'){
            $("#formNuevaVenta [name=cliente_persona_tipo]").val("NATURAL");
        }

        $("#formNuevaVenta [name=cliente_id]").val(item.id);
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

    },

     fillTiposDocumentos :function(cliente_doc_tipo){

        var html = ""
        if (cliente_doc_tipo == '1'){
            html += "<option value='BOLETA'>Boleta</option>"
            html += "<option value='NOTAVENTA'>Nota de Venta</option>"
        }else{
            html += "<option value='FACTURA'>Factura</option>"
            html += "<option value='BOLETA'>Boleta</option>"
            html += "<option value='NOTAVENTA'>Nota de Venta</option>"
        }

        $("[name=documento_tipo]").html(html);
    },
    

     buscarProductoPorCodigo :function(codigo){
        $.ajax({
            url     :   base + "api/get-item-by-code",
            data    :   {'codigo' : codigo},
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                if (data.estado === 'ok'){
                    nuevaCotizacion.itemsAgregar(data.data);
                    nuevaCotizacion.actualizarCotizacionDetalle();
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

     roundFloat :function(num,dec){
        var d = 1;
        for (var i=0; i<dec; i++){
            d += "0";
        }
        return Math.round(num * d) / d;
    },

     startTime :function() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = nuevaCotizacion.checkTime(m);
        s = nuevaCotizacion.checkTime(s);
        $("input[name=hora_venta]").val(h + ":" + m + ":" + s);
        var t = setTimeout(nuevaCotizacion.startTime, 500);
    },
    checkTime  :function(i) {
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
            nuevaCotizacion._items.push(obj);
        });
        nuevaCotizacion.actualizarCotizacionDetalle();
    },

    // guarda la info del cliente en la variable global
    guardarCliente  :function(){

        $("#clienteEstadoBusqueda2").html("<i class='fas fa-spinner fa-spin fa-fw'></i>");
        var tipo_cliente = $('input[name=cliente_persona_tipo]').val();

        if (tipo_cliente == 'NATURAL'){
            nuevaCotizacion.guardarClienteNatural();
        }else{
            nuevaCotizacion.guardarClienteJuridico();
        }

    },

    /**
     * Registra a los cliente naturales
     * es decir, con DNI
     */
    guardarClienteNatural   :   function ()
    {
        // obtenemos la información modificada
        nuevaCotizacion._persona.dni = $("input[name=cliente_ruc]").val();
        nuevaCotizacion._persona.direccion = $("input[name=cliente_domicilio_fiscal]").val();
        console.log(nuevaCotizacion._persona)
        var endpoint = base + "personas/save"
        $.ajax({
            url     :   endpoint,
            data    :   nuevaCotizacion._persona,
            type    :   'POST',
            success :   function (data, status, xhr) {
                $("#clienteEstadoBusqueda2").html("<i class='fas fa-save fa-fw'></i>");
                $("#clienteEstadoBusqueda2").hide();
                $("#clienteEstadoBusqueda3").show();

                nuevaCotizacion.clienteEstablecerDatos({
                    cliente_doc_tipo: '1',
                    cliente_doc_numero: data.data.dni,
                    cliente_razon_social  : `${data.data.nombres} ${data.data.apellidos}`,
                    cliente_domicilio_fiscal  : `${data.data.direccion}`
                });
                console.log(data);
            },
        });

    },
    /**
     * Registra a los cliente juridicos
     * es decir, con RUC
     */
    guardarClienteJuridico  :   function()
    {
        nuevaCotizacion._cuenta.ruc = $("input[name=cliente_ruc]").val();
        nuevaCotizacion._cuenta.domicilio_fiscal = $("input[name=cliente_domicilio_fiscal]").val();
        console.log(nuevaCotizacion._cuenta)
        var endpoint = base + "cuentas/save-from-json"
        $.ajax({
            url     :   endpoint,
            data    :   nuevaCotizacion._cuenta,
            type    :   'POST',
            success :   function (data, status, xhr) {
                $("#clienteEstadoBusqueda2").html("<i class='fas fa-save fa-fw'></i>");
                $("#clienteEstadoBusqueda2").hide();
                $("#clienteEstadoBusqueda3").show();

                nuevaCotizacion.clienteEstablecerDatos({
                    cliente_doc_tipo: '6',
                    cliente_doc_numero: data.data.ruc,
                    cliente_razon_social  : data.data.razon_social,
                    cliente_domicilio_fiscal  : data.data.domicilio_fiscal
                });
                console.log(data);
            },
        });
    },
    // extrae datos desde el api y los establece en los campos del cliente
    consultaDniRucAjax :function(){
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
                console.log(data);
                if (data.success){

                    if(data.result.cliente_doc_tipo == '1'){
                        $("[name=documento_tipo]").val("BOLETA")
                        $("[name=cliente_persona_tipo]").val("NATURAL")
                        nuevaCotizacion._persona = data.result
                    }else{
                        $("[name=documento_tipo]").val("FACTURA")
                        $("[name=cliente_persona_tipo]").val("JURIDICA")
                        nuevaCotizacion._cuenta = data.result
                    }
                    $('input[name=cliente_razon_social]').val(data.result.razon_social);
                    $('input[name=cliente_domicilio_fiscal]').val(data.result.direccion);
                    $('input[name=cliente_id]').val(""); // si es vacio, es para crear

                    $("#clienteEstadoBusqueda1").html("<i class='fas fa-exchange-alt fa-fw'></i>");
                    $("#clienteEstadoBusqueda1").hide();

                    $("#clienteEstadoBusqueda2").show()

                }
                else{
                    $(".estado_busqueda").html("<i class='fas fa-times fa-fw'></i>");

                }
            }
        });
    },
    setTipoCambio(cambio)
    {
        nuevaCotizacion.tipo_cambio_venta = parseFloat(cambio);
        $('#linkTipoCambio').text(`Cambio Venta : ${nuevaCotizacion.tipo_cambio_venta}`);
        nuevaCotizacion.actualizarCotizacionDetalle();
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


    itemsAgregar :function(data){
        var existe = false;
        $(nuevaCotizacion._items).each(function(i,itemOriginal){
            if(itemOriginal.id == data.id){
                itemOriginal.cantidad += 1;
                existe = true;
            }
        });

        if(!existe){
            data.index = nuevaCotizacion._detalle.length +1;
            data.cantidad = 1;
            data.comentario = "";
            if(data.inc_igv == "0"){
                data.afectacion_igv = '20';
            }else{
                data.afectacion_igv = '10';
            }
            data.valor_venta = 0;
            data.igv = 0;

            nuevaCotizacion._items.push(data);
        }
    },

    cambiarEstadoIGV :function() {
        nuevaCotizacion.inc_igv = !nuevaCotizacion.inc_igv;
        // nuevaCotizacion.itemsGenerarTabla();
        nuevaCotizacion.actualizarCotizacionDetalle();
    },
    actualizarCotizacionDetalle :   function()
    {
        this.copiarItemsEnDetalle();
        this.calcularTotalesGlobales();
        this.itemsGenerarTabla();
    },
    copiarItemsEnDetalle  :function(){

        nuevaCotizacion._detalle = [];
        var item_index = 0;
        $(nuevaCotizacion._items).each(function(i, o) {
            item_index++;
            var itemDetalle =  {};

            var objItemCalculos                 = nuevaCotizacion.calcularTotalesByItem(o.id);

            itemDetalle.item_id                 = o.id;
            itemDetalle.item_codigo             = o.codigo;//revisar
            itemDetalle.item_nombre             = o.nombre;
            itemDetalle.item_unidad             = o.unidad;
            itemDetalle.item_index              = item_index;
            itemDetalle.item_comentario         = o.comentario;
            itemDetalle.tipo_moneda             = o.tipo_moneda;
            itemDetalle.cantidad                = parseFloat(o.cantidad);
            itemDetalle.afectacion_igv          = o.afectacion_igv;
            itemDetalle.precio_uventa           = parseFloat(objItemCalculos.precio_venta_unitario);
            itemDetalle.valor_venta             = parseFloat(objItemCalculos.valor_venta_unitario);
            itemDetalle.subtotal                = parseFloat(objItemCalculos.valor_venta_total);
            itemDetalle.igv_monto               = parseFloat(objItemCalculos.monto_igv);
            // itemDetalle.isc_monto               = "";
            // itemDetalle.icb_per_monto           = "";
            itemDetalle.precio_total            = parseFloat(objItemCalculos.precio_total);

            itemDetalle.subtotal        = nuevaCotizacion.roundFloat(itemDetalle.subtotal,2);
            itemDetalle.precio_total    = nuevaCotizacion.roundFloat(itemDetalle.precio_total,2);
            itemDetalle.igv_monto       = nuevaCotizacion.roundFloat(itemDetalle.igv_monto,2);

            itemDetalle.subtotal.toFixed(2);
            itemDetalle.igv_monto.toFixed(2);
            itemDetalle.precio_total.toFixed(2);

            nuevaCotizacion._detalle.push(itemDetalle);

        });

    },
    calcularTotalesByItem :   function(item_id)
    {
        var itemOriginal    = nuevaCotizacion._items.find(itemOr => itemOr.id == item_id);
        var objItemTotales = {};

        /**
         * Obtenemos los valores unitarios
         */

        var objValoresUnitarios                 = nuevaCotizacion.calcularMontosUnitariosByItem(itemOriginal.id);
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
            objItemTotales.monto_igv = nuevaCotizacion.roundFloat(objItemTotales.monto_igv, 2);

        }else if(['20','30'].indexOf(itemOriginal.afectacion_igv) !== -1)
        {
            objItemTotales.monto_igv = 0;

        }else{
            objItemTotales.monto_igv = objItemTotales.valor_venta_total  * 0.18;
            objItemTotales.monto_igv = nuevaCotizacion.roundFloat(objItemTotales.monto_igv, 2);

        }



        return objItemTotales;

    },
    calcularMontosUnitariosByItem(item_id)
    {
        var itemOriginal            = nuevaCotizacion._items.find(itemOr => itemOr.id == item_id);
        var venta_tipo_moneda       = $('select[name=tipo_moneda]').val();
        var cambio                  = nuevaCotizacion.tipo_cambio_venta;
        var valoresUnitarios        =   {};

        itemOriginal.precio_venta = parseFloat(itemOriginal.precio_venta);

        if(itemOriginal.tipo_moneda ==='USD' && venta_tipo_moneda === 'PEN')
        {
            valoresUnitarios.precio_venta_unitario = itemOriginal.precio_venta * cambio;

        }else if(itemOriginal.tipo_moneda === 'PEN' && venta_tipo_moneda === 'USD')
        {
            valoresUnitarios.precio_venta_unitario =itemOriginal.precio_venta / cambio;
            valoresUnitarios.precio_venta_unitario = nuevaCotizacion.roundFloat(valoresUnitarios.precio_venta_unitario,2);
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


        return valoresUnitarios;

    },
    calcularTotalesGlobales()
    {
        nuevaCotizacion._totales.total = 0;
        nuevaCotizacion._totales.igvs = 0;
        nuevaCotizacion._totales.subtotales = 0;

        nuevaCotizacion._op_gravadas     = 0.0;
        nuevaCotizacion._op_exoneradas   = 0.0;
        nuevaCotizacion._op_inafectas    = 0.0;

        $(nuevaCotizacion._detalle).each(function(i, o) {
            o.subtotal = parseFloat(o.subtotal);
            o.igv_monto = parseFloat(o.igv_monto);
            o.precio_total = parseFloat(o.precio_total);

            switch (o.afectacion_igv){
                case '10':

                    nuevaCotizacion._op_gravadas   += o.subtotal ;
                    nuevaCotizacion._op_gravadas    = parseFloat(nuevaCotizacion._op_gravadas);
                    nuevaCotizacion._totales.igvs  += o.igv_monto;

                    break;
                case '20':
                    nuevaCotizacion._op_exoneradas   += o.subtotal ;
                    nuevaCotizacion._op_exoneradas    = parseFloat(nuevaCotizacion._op_exoneradas);

                    break;
                case '30':
                    nuevaCotizacion._op_inafectas    += o.subtotal ;
                    nuevaCotizacion._op_inafectas   = parseFloat(nuevaCotizacion._op_inafectas);
                    break;
                default:
                    nuevaCotizacion._op_gravadas     += o.valor_venta ;
                    nuevaCotizacion._op_gravadas      = parseFloat(nuevaCotizacion._op_gravadas);
                    nuevaCotizacion._totales.igvs    += o.igv_monto;

                    o.afectacion_igv = '10';
                    break;
            }


            /**
             * Contabilizamos totales
             */
            nuevaCotizacion._totales.subtotales += o.subtotal;
            nuevaCotizacion._totales.total += o.precio_total;

            /**
             * Formateamos los totales
             */
            nuevaCotizacion._totales.igvs.toFixed(2)
            nuevaCotizacion._totales.subtotales.toFixed(2)
            nuevaCotizacion._totales.total.toFixed(2);

            if (!nuevaCotizacion.inc_igv) {
                nuevaCotizacion._totales.igvs = 0;
                nuevaCotizacion._totales.subtotales = nuevaCotizacion._op_gravadas + nuevaCotizacion._op_exoneradas + nuevaCotizacion._op_inafectas;
            }

        });

        /**
         * Formateamos y contabilizados los totales globales
         */

        nuevaCotizacion._op_gravadas   = nuevaCotizacion.roundFloat(nuevaCotizacion._op_gravadas,2);
        nuevaCotizacion._op_exoneradas = nuevaCotizacion.roundFloat(nuevaCotizacion._op_exoneradas,2);
        nuevaCotizacion._op_inafectas  = nuevaCotizacion.roundFloat(nuevaCotizacion._op_inafectas,2);

        nuevaCotizacion._totales.subtotales = nuevaCotizacion._op_gravadas + nuevaCotizacion._op_exoneradas + nuevaCotizacion._op_inafectas;
        nuevaCotizacion._totales.total = nuevaCotizacion._op_gravadas + nuevaCotizacion._op_exoneradas + nuevaCotizacion._op_inafectas + nuevaCotizacion._totales.igvs;
        nuevaCotizacion._totales.total.toFixed(2);

        nuevaCotizacion._totales.igvs.toFixed(2)
        nuevaCotizacion._totales.subtotales.toFixed(2)
        nuevaCotizacion._totales.total.toFixed(2);
        nuevaCotizacion.validarUsoCamposMontosGlobales();
    },
    itemsGenerarTabla :function(){

        var html = "";
        $(nuevaCotizacion._detalle).each(function(i, o){

            var itemOriginal = nuevaCotizacion._items.find(item => item.id == o.item_id);
            var precio_venta_original_formateado = nuevaCotizacion.roundFloat(itemOriginal.precio_venta,2);
            var precio_venta_formateado          = nuevaCotizacion.roundFloat(o.precio_uventa,2);
            var afectacion_igv = nuevaCotizacion.getNombreAfectacionIGV(o.afectacion_igv)

            html += "<tr>";
            html += "<td>" + o.item_index + "</td>";
            html += "<td>" + o.item_codigo + "</td>";
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
                                ${o.subtotal.toString()} <br>
                                ${o.igv_monto.toString()} <br>
                                ${o.precio_total.toString()} <br>
                          </td>       `;
            html += "<td>";
            html += "<i class='fas fa-edit item_actualizar' data-id='"+itemOriginal.id+"'></i> &nbsp;";
            html += "<i class='fas fa-trash item_eliminar' data-id='"+itemOriginal.id+"' ></i>";
            html += "</td>";
            html += "</tr>";

        });
        this.llenarTotalesCotizacion();
        $("tbody#detalle").html(html);

        // cuando presionamos el tachito de basura en cada registro

        this.actualizarEventosItemsDetalle();



        // guardamos en el campo oculto el detalle

        this.guardarDetalle();

        nuevaCotizacion.actualizarDescuentos();
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
    guardarDetalle  :   function()
    {
        var str_data = JSON.stringify(nuevaCotizacion._detalle);
        $(".detalle_items").val(str_data);
    },
    /**
     * Asigna los totales de las ventas
     * a los inputs correspondientes
     */
    llenarTotalesCotizacion :    function()
    {
        if (!nuevaCotizacion.inc_igv) {
            nuevaCotizacion._totales.total /= 1.18;
        }
        $(".precio_total").val(nuevaCotizacion._totales.total.toFixed(2));
        $("[name=pago_inicial]").val(nuevaCotizacion._totales.total.toFixed(2));

        $("[name=mto_coti_igv]").val(nuevaCotizacion._totales.igvs.toFixed(2));
        $("[name=mto_oper_gravadas]").val(nuevaCotizacion._op_gravadas.toFixed(2));
        $("[name=mto_oper_inafectas]").val(nuevaCotizacion._op_inafectas.toFixed(2));
        $("[name=mto_oper_exoneradas]").val(nuevaCotizacion._op_exoneradas.toFixed(2));

    },

    /**
     * Asigna los eventos asociados a cada item
     * del detalle al momento de actualizar la tabla de items
     */
    actualizarEventosItemsDetalle   :   function()
    {
        $(".item_eliminar").click(function(){
            var id = $(this).attr("data-id");
            nuevaCotizacion.itemEliminarRegistro(id);
        });

        $(".item_actualizar").click(function()
        {
            var id = $(this).attr("data-id");
            var itemOriginal = nuevaCotizacion._items.find(item => item.id == id);

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



    /**
     * Actualiza los campos que pueden cambiar
     * en el detalle, como precio, cantidad y el comentario de cada producto
     */
    itemActualizarRegistro  :   function(data){
        $(nuevaCotizacion._items).each(function(i,original){
            if(original.id == data.id)
            {
                original.nombre = original.unidad == 'ZZ' ? data.nombre : original.nombre;
                original.comentario = data.comentario;
                original.afectacion_igv = data.afectacion_igv;
                original.precio_venta = data.precio;
                original.cantidad = data.cantidad;
            }
        });
        nuevaCotizacion.actualizarCotizacionDetalle();

    },
    itemEliminarRegistro :function(id){
        var _items2 = [];
        for( var i = 0; i <  nuevaCotizacion._items.length; i++){
            if (  nuevaCotizacion._items[i].id != id) {
                _items2.push(nuevaCotizacion._items[i]);

            }
        }

        nuevaCotizacion._items = _items2;


        nuevaCotizacion.actualizarCotizacionDetalle();
    },



    showModalCoti :function() {
        $(`[name=numero_cotizacion]`).val('');
        $(`#selCoti`).modal('show');
    },


    // cargamos la cotizacion en la coti
    cargarCotizacion :function(limpiar="1"){
        var cotiid = $("input[name=numero_cotizacion]").val();
        $.ajax({
            url     :   base + "cotizaciones/get-one/" + cotiid,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data);
                if(data.success == true){
                    if (limpiar == "1") {
                        nuevaCotizacion._detalle = [];
                    }
                    nuevaCotizacion.cargarInfoFromCoti(data.data);
                    $(`#selCoti`).modal('hide');
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
    cargarInfoFromCoti :function(coti){

        // datos principales de la venta
        $("[name=cotizacion_id]").val(coti.id);

        // establecemos los datos del cliente
        nuevaCotizacion.clienteEstablecerDatos(coti);

        $("[name=comentarios]").val(coti.comentarios);
        $("[name=codvendedor]").val(coti.codvendedor);

        //cotizacion_registros
        // index = 1;
        // $(coti.cotizacion_registros).each (function(i, o){
        //     var obj = {};
        //     obj.index = index;
        //     obj.id = o.item_id;
        //     obj.nombre = o.item_nombre;
        //     obj.codigo = o.item_codigo;
        //     obj.unidad = o.item_unidad;
        //     obj.cantidad = o.cantidad;
        //     obj.precio_venta = o.precio_uventa;
        //     //obj.precio_original = o.precio_original;
        //     obj.actualizar = 1;
        //     obj.tipo_moneda= o.tipo_moneda;
        //     obj.precio_total = o.precio_total;
        //     obj.comentario = o.item_comentario;
        //     obj.inc_igv = "1";
        //     index++;
        //     nuevaCotizacion._detalle.push(obj);
        // });
        // nuevaCotizacion.itemsGenerarTabla();


        if(typeof  coti.items_originales != 'undefined' && coti.items_originales != null)
        {

            nuevaCotizacion._items = JSON.parse(coti.items_originales) ;

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
                nuevaCotizacion._items.push(obj);
            });
        }
        nuevaCotizacion.actualizarCotizacionDetalle();
    },


    // clienteEstablecerDatos :function(item){
    //     console.log(item);
    //
    //     $(".limpiar_formulario").show();
    //
    //     if(item.cliente_doc_tipo == '6'){
    //         $("#formNuevaVenta [name=cliente_persona_tipo]").val("JURIDICA");
    //     }else if(item.cliente_doc_tipo == '1'){
    //         $("#formNuevaVenta [name=cliente_persona_tipo]").val("NATURAL");
    //     }
    //
    //     var cid = $("#formNuevaVenta [name=cliente_id]").val();
    //     if (cid == "") {
    //         $("#formNuevaVenta [name=cliente_id]").val(item.id);
    //     }
    //     $("#formNuevaVenta [name=cliente_doc_tipo]").val(item.cliente_doc_tipo);
    //     $("#formNuevaVenta [name=cliente_doc_numero]").val(item.cliente_doc_numero);
    //
    //     $("#formNuevaVenta [name=cliente_ruc]").val(item.cliente_doc_numero);
    //     $("#formNuevaVenta [name=cliente_razon_social]").val(item.cliente_razon_social);
    //     $("#formNuevaVenta [name=cliente_domicilio_fiscal]").val(item.cliente_domicilio_fiscal);
    //
    //     // procedemos a bloquear los controles
    //     $("#formNuevaVenta [name=cliente_ruc]").attr("readonly",true);
    //     $("#formNuevaVenta [name=cliente_razon_social]").attr("readonly",true);
    //     $("#formNuevaVenta [name=cliente_domicilio_fiscal]").attr("readonly",true);
    //
    //     $("#clienteEstadoBusqueda1").hide();
    //     $("#clienteEstadoBusqueda2").hide();
    //     $("#clienteEstadoBusqueda3").show();
    // },

    //serv_id;
    /***************** mantenimiento para los servicios ++++++++++*/
    itemAddServicio :   function(itemServicioObj){

        nuevaCotizacion.itemsAgregar({
            'id'            : itemServicioObj.id === "" ? nuevaCotizacion.serv_id : itemServicioObj.id,
            'inc_igv'       : itemServicioObj.serv_inc_igv,
            'codigo'        : itemServicioObj.codigo,
            'cantidad'      : itemServicioObj.cantidad,
            'unidad'        : itemServicioObj.unidad,
            'precio_venta'  : itemServicioObj.precio,
            'nombre'        : itemServicioObj.servicio,
            'tipo_moneda'   : itemServicioObj.tipo_moneda,
            'afectacion_igv': itemServicioObj.afectacion_igv
        })

        nuevaCotizacion.serv_id++;

        nuevaCotizacion.actualizarCotizacionDetalle();
        $("#formServicio").modal("hide");
    },
    abrirModalServicio  :   function() {

        GlobalServicios.abrir();
    },

    validarUsoCamposMontosGlobales :   function()
        {
            if(nuevaCotizacion._op_gravadas > 0)
            {
                $("#div_op_gravadas").attr('hidden',false);
            }else{
                $("#div_op_gravadas").attr('hidden',true);

            }

            if(nuevaCotizacion._op_exoneradas > 0)
            {
                $("#div_op_exoneradas").attr('hidden',false);

            }else{
                $("#div_op_exoneradas").attr('hidden',true);

            }

            if(nuevaCotizacion._op_inafectas > 0)
            {
                $("#div_op_inafectas").attr('hidden',false);

            }else{
                $("#div_op_inafectas").attr('hidden',true);
            }

        },
    // formServicioPoblarUnidadesMedida :function(){
    //     var endpoint = base + 'sunat-fe-facturas/api-get-unidades-medida'
    //     $.ajax({
    //         url     :   endpoint,
    //         data    :   {},
    //         type    :   'POST',
    //         success :   function (data, status, xhr) {
    //
    //             var html = data.map((obj) => {
    //                 return `<option value="${obj.id}">${obj.nombre}</option>`
    //             })
    //
    //             $("#formServicio [name=unidad]").html(html)
    //             $("#formServicio [name=unidad]").val("ZZ")
    //         },
    //     });
    //
    // }

}
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
