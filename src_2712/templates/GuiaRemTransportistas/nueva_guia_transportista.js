var items = [];

$(document).ready(function () {
    $('#transportista_ruc').mask('00000000000');
    $('[name=modal_venta]').mask('AAAA-00000000');
    $('[name=doc_adicional_serie]').mask('AAAA');
    $('[name=doc_adicional_correlativo]').mask('00000000');
    $('[name=doc_adicional_emisor]').mask('00000000000');

    $("input[name=partida_ubigeo_full]").autocomplete({
        serviceUrl: base + `api/get-ubigeo`,
        autoSelectFirst: true,
        onSelect: function (item) {
            $("[name=partida_ubigeo]").val(item.id);
        }
    });

    $("input[name=llegada_ubigeo_full]").autocomplete({
        serviceUrl: base + `api/get-ubigeo` ,
        autoSelectFirst: true,
        onSelect: function (item) {
            $("[name=llegada_ubigeo]").val(item.id);
        }
    });


    $("input[name=cliente_razon_social]").autocomplete({
        serviceUrl: base + 'cuentas/api-consulta-clientes',
        onSelect: function (item) {
            $('input[name=cliente_id]').val(item.id);
            $('input[name=cliente_tipo_doc]').val(item.cliente_doc_tipo);
            $('input[name=cliente_num_doc]').val(item.cliente_doc_numero);
            $('input[name=cliente_razon_social]').val(item.cliente_razon_social);

            GuiaremRemitenteAdd.actualizarDirLlegada(item.cliente_id);
        }
    });

    $('input[name=item_nombre]').autocomplete({
        minChars: 1,
        autoSelectFirst: true,
        showNoSuggestionNotice: 'Sin resultados',
        lookup: listado_items,
        onSelect: function (suggestion) {
            console.log(suggestion)
            $('[name=item_id]').val(suggestion.id);
            $('[name=item_codigo]').val(suggestion.codigo);
            $('[name=item_costo]').val(suggestion.precio_venta);
            $(` #item_unidad_medida option[value=${suggestion.unidad}]` ).attr('selected',true);
        },
        onSearchComplete: function (query, suggestions) {

        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x').val(hint);
        },
        onInvalidateSelection: function () {
            $('#selction-ajax').html('You selected: none');
        }
    });

    $("#formNuevaGuia").submit(function(e){
        e.preventDefault();
        if(GuiaremRemitenteAdd.validarFechas()){
            GuiaremRemitenteAdd.save();
        }
    });

    $("[name=fecha_emision]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });

    $('[name=fecha_traslado]').datepicker({
        format: 'yyyy-mm-dd',
        locale: 'es-es',
        uiLibrary: 'boostrap4'
    })

    $("input[name=establecimiento_origen]").autocomplete({
        serviceUrl: base + 'almacenes/api-consulta-establecimiento',
        onSelect: function (establecimiento) {
            $('input[name=codigo_establecimiento_origen]').val(item.codigo);
            $('input[name=establecimiento_origen]').val(item.nombre);
        }
    });
    $("input[name=establecimiento_destino]").autocomplete({
        serviceUrl: base + 'almacenes/api-consulta-establecimiento',
        onSelect: function (establecimiento) {
            $('input[name=codigo_establecimiento_destino]').val(establecimiento.codigo);
            $('input[name=establecimiento_destino]').val(item.nombre);
        }
    });

    $("[name=doc_adicional_tipo]").on('change', () => {
        if($(this).val()  != ''){
            $('[name=doc_adicional_tipo]').attr('required', true);
            $('[name=doc_adicional_serie]').attr('required', true);
            $('[name=doc_adicional_correlativo]').attr('required', true);
            $('[name=doc_adicional_emisor]').attr('required', true);
        }else{
            $('[name=doc_adicional_tipo]').attr('required', false);
            $('[name=doc_adicional_serie]').attr('required', false);
            $('[name=doc_adicional_correlativo]').attr('required', false);
            $('[name=doc_adicional_emisor]').attr('required', false);
        }
    })

    GuiaremRemitenteAdd.setEstablecimiento();
    GuiaremRemitenteAdd.getEstablecimientos();

});



function mayus(e) {
    e.value = e.value.toUpperCase();
}




var GuiaremRemitenteAdd = {
    // Items   : null,
    Items: [],
    Conductores:[
        {
            documento: '',
            licencia: '',
            nombres: '',
            apellidos: '',
        }
    ],
    Vehiculos:[
        { tipo:'principal', placa: '' }
    ],
    save: function(){

        if( GuiaremRemitenteAdd.Items.length==0){
            alert("Debe tener un producto como mínimo en su guía de remisión");
            return;
        }

        var fdata = new FormData(document.getElementById('formNuevaGuia'));
        console.log( JSON.stringify(GuiaremRemitenteAdd.Items));
        fdata.append("registros", JSON.stringify(GuiaremRemitenteAdd.Items));

        // nuevo en guias de remision
        fdata.append("transp_privado_conductores", JSON.stringify(GuiaremRemitenteAdd.Conductores));
        fdata.append("transp_privado_vehiculos", JSON.stringify(GuiaremRemitenteAdd.Vehiculos));

        $("#btnGuiaRemSubmit").prop("disabled", true)
        $("#btnGuiaRemSubmit").html("<i class='fas fa-spin fa-spinner'></i> Guardando")

        var endpoint = `${API_FE}/sunat-fe-guiarem-transportistas/add`;


        $.ajax({
            url     : endpoint,
            data    : fdata,
            type    : 'POST',
            processData: false,
            contentType: false,
            success : function (r) {
                console.log(r);
                if (r.success){
                    GuiaremRemitenteAdd.generarXML(r.data.id);
                }else{
                    alert(r.message);
                    // location.reload();
                }
            },error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    },

    generarXML  : function (guiarem_id){
        console.log("Generando XML de la guia "+guiarem_id+" ...");
        $("#btnGuiaRemSubmit").html("<i class='fas fa-spin fa-spinner'></i> Generando XML")
        var endpoint = `${API_FE}/sunat-fe-guiarem-transportistas/api-generar-xml/${guiarem_id}`;
        $.ajax({
            url     : endpoint,
            type    : 'POST',
            success : function (r) {
                // console.log(r);
                if(r.success){

                    //temporalmente por pruebas desactivamos el envío a sunat si no esta comentado esta en produccion
                    GuiaremRemitenteAdd.enviarSunat(guiarem_id);
                }else{
                    alert(r.data.data)
                    alert(r.data.message)
                }
            },error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },

    enviarSunat: function(guiarem_id){
        console.log("Enviando a sunat ...");
        $("#btnGuiaRemSubmit").html("<i class='fas fa-spin fa-spinner'></i> Enviando a Sunat")

        var endpoint = `${API_FE}/sunat-fe-guiarem-transportistas/api-enviar-sunat/${guiarem_id}`;
        $.ajax({
            url     : endpoint,
            type    : 'POST',
            success : function (r) {
                console.log(r);
                if(r.success){
                    alert("La guia de remisión ha sido aceptada en Sunat")
                    location.href = `${base}sunat-fe-guiarem-transportistas/`;
                }else{
                    alert(r.data.message)
                    // alert(r.data.data);
                }

            },error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    },



    actualizarDirLlegada : function()
    {
        var id= $('[name=cliente_id]').val();

        var endpoint= `${base}cuentas/get-one-cuenta/${id}`;
        $.ajax({
            url:endpoint,
            data:'',
            type: 'GET',
            success : function (r)
            {
                if(r.success)
                {
                    var c= r.data;
                    $('[name=direccion_llegada]').val(c.domicilio_fiscal);
                }
            }
        });
    },
    editarItem : function()
    {
        var item_id = $('[name=item_id]').val();
        var item_nombre = $('[name=item_nombre]').val();
        var item_cant = $('[name=item_cant]').val();
        var item_costo = $('[name=item_costo]').val();
        var item_codigo = $('[name=item_codigo]').val();
        var item_unidad_medida = $('#item_unidad_medida').val();

        var item_index = GuiaremRemitenteAdd.Items.indexOf(GuiaremRemitenteAdd.Items.find(item => item.item_codigo === item_codigo && item.item_nombre === item_nombre));

        GuiaremRemitenteAdd.Items[item_index].item_id= item_id;
        GuiaremRemitenteAdd.Items[item_index].item_codigo= item_codigo;
        GuiaremRemitenteAdd.Items[item_index].item_nombre= item_nombre;
        GuiaremRemitenteAdd.Items[item_index].item_unidad = item_unidad_medida;
        GuiaremRemitenteAdd.Items[item_index].cantidad= parseFloat(item_cant);
        GuiaremRemitenteAdd.Items[item_index].item_costo= parseFloat(item_costo);
        GuiaremRemitenteAdd.Items[item_index].precio_total= parseFloat(item_cant) * parseFloat(item_costo);


        GuiaremRemitenteAdd.generarTabla();
    },

    agregarItem:function() {
        var item_id = $('[name=item_id]').val();
        var item_nombre = $('[name=item_nombre]').val();
        var item_cant = $('[name=item_cant]').val();
        var item_costo = $('[name=item_costo]').val();
        var item_codigo = $('[name=item_codigo]').val();
        var item_unidad_medida = $('#item_unidad_medida').val();


        GuiaremRemitenteAdd.Items.push({
            item_id: item_id,
            item_codigo: item_codigo,
            item_nombre: item_nombre,
            item_unidad : item_unidad_medida,
            cantidad: parseFloat(item_cant),
            item_costo: parseFloat(item_costo),
            precio_total: parseFloat(item_cant) * parseFloat(item_costo)
        });

        GuiaremRemitenteAdd.generarTabla();

    },

    modificarItem : function(codigo)
    {

        var data;

        data = JSON.parse(JSON.stringify(GuiaremRemitenteAdd.Items.find(item => item.item_codigo === codigo)));
        // for(var i = 0 ; i < items.length; i++)
        // {
        //     if(GuiaremRemitenteAdd.Items[i].item_codigo == codigo && GuiaremRemitenteAdd.Items[i].item_nombre==nombre)
        //     {
        //         data = JSON.parse(JSON.stringify(GuiaremRemitenteAdd.Items[i]));
        //         break;
        //     }
        // }
        FormGuiaRemRemProductos.Editar(data);
    }
    ,
    agregarVenta: function(id) {
        var endpoint = `${base}guia-rem-transportistas/get-venta-registros/${id}`;

        $.ajax({
            url: endpoint,
            data: '',
            type: 'GET',
            success: function (r) {
                GuiaremRemitenteAdd.Items = r.data;
                GuiaremRemitenteAdd.generarTabla();
            }
        });
    },

////////////////////////////////////ANTIGUO
    fetchVenta: function (tipo = 'factura'){
        var venta_correlativo = '';
        if(tipo == 'factura'){
            $("[name=modal_venta]").focus();
            venta_correlativo = $("[name=modal_venta]").val();
        }else{
            $("[name=modal_nventa]").focus();
            venta_correlativo = $("[name=modal_nventa]").val();
        }
        var endpoint = `${base}ventas/api-fetch-by-correlativo/${venta_correlativo}`;
        $.ajax({
            url: endpoint,
            data : { },
            type : 'GET',
            success: function (data, status, xhr) {
                console.log(data);
                if (data.success){
                    GuiaremRemitenteAdd.Items = data.data.venta_registros;
                    GuiaremRemitenteAdd.generarTabla();
                    $('[name=cliente_razon_social]').val(data.data.cliente_razon_social);
                    $('[name=llegada_direccion]').val(data.data.cliente_domicilio_fiscal);
                    $('[name=cliente_num_doc]').val(data.data.cliente_doc_numero);
                    $('[name=cliente_id]').val(data.data.cliente_id);
                    $('[name=cliente_tipo_doc]').val(data.data.cliente_doc_tipo);
                    $('[name=doc_adicional_serie]').val(data.data.documento_serie);
                    $('[name=doc_adicional_correlativo]').val(data.data.documento_correlativo);

                    $("#guiaRemModalFromVenta").modal("hide")
                }else{
                    alert(data.message)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }

        });
    },


    abrirModalDesdeVenta : function(){
        $("#guiaRemModalFromVenta").modal("show")
    },

    generarTabla : function() {

        var html = "";
        $(GuiaremRemitenteAdd.Items).each(function (i, o) {
            html += `<tr>
                <td>${o.item_codigo}</td>
                <td>${o.item_nombre}</td>
                <td>${o.item_unidad}</td>
                <td>${o.cantidad}</td>
                <td><i class="fa fa-trash text-danger"></i><a class="text-danger" href="javascript:GuiaremRemitenteAdd.eliminarItem('${o.item_codigo}')" > Eliminar</a>
                    <br>
                    <i class="fa fa-edit text-warning"></i><a class="text-warning" href="javascript:GuiaremRemitenteAdd.modificarItem('${o.item_codigo}')" > Editar</a>
                </td>
            </tr>`
        });

        $('#tabla_items').html(html);

        //$('[name=registros]').val(JSON.stringify(items));
    },
    ModoTrasladoChange : function(input){
        var html_trans_pub = `
        <div class="row pb-3 px-2">
            <div class="col-sm-12 col-md-6 col-lg-4 mb-2 px-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend  ">
                        <label class="input-group-text ">
                            <i class=" fa fa-user fa-fw"></i> RUC
                        </label>
                    </div>
                    <input type="hidden" name="transp_tipo_doc" value="6" >
                    <input class="form-control form-control-sm" placeholder="RUC" name="transp_num_doc" id="transp_ruc" autocomplete="off" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary " id="btnVerfTransRuc" onclick="GuiaremRemitenteAdd.VerificarRucTransPubl(event)">
                            <i class=" fa fa-search fa-fw" id="iconVerfTransRuc"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 mb-2 px-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend  ">
                        <label class="input-group-text ">
                            <i class=" fa fa-user fa-fw"></i>
                        </label>
                    </div>
                    <input class="form-control form-control-sm" placeholder="Razon Social" readonly name="transp_razon_social" id="transp_razon_social" required>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 mb-2 px-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend  ">
                        <label class="input-group-text ">
                            <i class=" fa fa-code fa-fw"></i>
                        </label>
                    </div>
                    <input class="form-control form-control-sm" placeholder="Codigo MTC" name="transp_codigo_mtc" id="transp_codigo_mtc" required>
                </div>
            </div>
        </div>
        `;
        $("#guiaremTrans").show();
        $("#datosTrans").hide();
        $("#guiaremVehiculos").hide();
        $("#checkTrasnPrivado").hide();
        $("#addConductor").hide();
        if(input.value == '01'){
            $('#guiaremTrans #transTipo').html('Transportista');
            $("#datosTrans").html(html_trans_pub);

            //Revisar las siguientes lineas
            $("#vehiculo_id").prop('required',false);
            $("#transportista_id").prop('required',false);
            $("#transportista_ruc").prop('required',true);
            $("#transportista_razon_social").prop('required',true);
            $('#transp_ruc').mask('00000000000');
            $("#datosTrans").show();

        }else if(input.value == '02'){
            GuiaremRemitenteAdd.GenerarFormVehiculoPrivado();
            GuiaremRemitenteAdd.GenerarFormConductorPrivado();
            $("#datosTrans").show();
            $("#checkTrasnPrivado").show();
            $("#addConductor").show();
            GuiaremRemitenteAdd.cambioVehiculoMenor();
            //Revisar las siguientes lineas
            $("#vehiculo_id").prop('required',true);
            $("#transportista_id").prop('required',true);
            $("#transportista_ruc").prop('required',false);
            $("#transportista_razon_social").prop('required',false);
        }else{
            $("#guiaremTrans").hide();
            $("#checkTrasnPrivado").hide();
            $("#addConductor").hide();
            $("#guiaremVehiculos").hide();
        }

    },
    VerificarRucTransPubl : function(e){
        e.preventDefault();
        var ruc = $('#transp_ruc').val();
        var endpoint = `https://www.factura24.pe/api/consulta-doc/ruc/${ruc}`;

        if( ruc.length < 11){
            alert('El RUC Ingresado no es valido');
            return;
        }
        $('#datosTrans #btnVerfTransRuc').prop('disabled',true);
        $('#datosTrans #iconVerfTransRuc').removeClass('fa-search');
        $('#datosTrans #iconVerfTransRuc').addClass('fa-spinner');
        $('#datosTrans #iconVerfTransRuc').addClass('fa-spin');

        $.ajax({
            url: endpoint,
            data:'',
            type:'GET',
            success: function(r){
                if(r.success){
                    $('#transp_razon_social').val(r.data.nombre);
                    $('#iconVerfTransRuc').prop('disabled',true);
                }else{
                    alert(r.message);
                }
                $('#datosTrans #btnVerfTransRuc').prop('disabled',false);
                $('#datosTrans #iconVerfTransRuc').removeClass('fa-spinner');
                $('#datosTrans #iconVerfTransRuc').removeClass('fa-spin');
                $('#datosTrans #iconVerfTransRuc').addClass('fa-search');

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }


        })

    },

    eliminarItem: function (codigo)
    {
        var new_items=[];
        for(var i = 0 ; i < GuiaremRemitenteAdd.Items.length; i++)
        {
            if(GuiaremRemitenteAdd.Items[i].item_codigo !== codigo)
            {
                new_items.push(GuiaremRemitenteAdd.Items[i]);
            }
        }
        GuiaremRemitenteAdd.Items = new_items;
        GuiaremRemitenteAdd.generarTabla();
    },

    /**
     * @author Ali Reyes
     *
     */
     unblockCancelGuideField(){
        $('#anular_guia_codigo').prop('readonly',false);
    },
    cleanCancellingButton(flag){
        if(flag){
            $("#divCleanCanceling").show();
        }else{
            $("#divCleanCanceling").hide();
        }
    },
    showCancelingModal(){
        $("#guiaRemAnularGuia").modal('show');
    },
    hideCancelingModal(){
        $("#guiaRemAnularGuia").modal('hide');
    },
    fetchGuideForCanceling(){
        var codigo = $("#guiarem_codigo_anular").val();
        var endpoint = `${base}sunat-fe-guiarem-transportistas/get-one-by-code/${codigo.toString().replaceAll(" ","")}`;

        $.ajax({
            url : endpoint,
            data : '',
            type : 'GET',
            success :   function(data){
                console.log(data);
                if(data.success){
                    alert("El código ingresado es válido");
                    GuiaremRemitenteAdd.hideCancelingModal();
                    $("#anular_guia_codigo").val(codigo);
                    GuiaremRemitenteAdd.cleanCancellingButton(true);
                }else{
                    alert(`No existe una guía con código ${codigo}`);
                }
            },
            error   :   function(xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        })

    },
    cleanCancelingFields(){
        $("#anular_guia_codigo").val('');
        $("#guiarem_codigo_anular").val('');
        GuiaremRemitenteAdd.cleanCancellingButton(false);
    },
    /**
     *
     */
    GenerarFormVehiculoPrivado : function(){
        $("#guiaremVehiculos").show();
        var html_vehiculo = '';
        GuiaremRemitenteAdd.Vehiculos.forEach( (e, i) => {
            html_vehiculo += `
            <div class="row">
            <div class="col-sm-11 col-lg-6 pb-2">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend  ">
                        <label class="input-group-text ">
                            <i class=" fa fa-user fa-fw"></i> Placa Vehículo ${ e.tipo == 'principal' ? 'Principal' : 'Secundario'}
                        </label>
                    </div>
                    <input type="text" class="form-control form-control-sm class_trans_priv" id="vehiculo_placa_${i}" data-index="${i}" data-obj="placa" placeholder="Número de Placa" required value="${ e.placa }" onkeyup="GuiaremRemitenteAdd.setDataVehiculos(this)">
                </div>
            </div>

            ${
                i != 0 ?
                `<div class="col-1" style:"width:">
                    <button class="btn btn-sm btn-danger" type="button" onclick="GuiaremRemitenteAdd.removerUnRegistro('vehiculo', ${i})">
                        <i class=" fa fa-trash fa-fw"></i>
                    </button>
                </div>`
                : ''
            }
            </div>
        `;
        })
        $('#guiaremVehiculos #transTipo').html('Vehículos');
        $("#datosVehiculos").html(html_vehiculo);
    },
    GenerarFormConductorPrivado : function(){
        var html_conductor = '';
        GuiaremRemitenteAdd.Conductores.forEach( (e, i) => {
            html_conductor += `
            <div class="row  px-2">
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 px-1" >
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend  ">
                            <label class="input-group-text ">
                                <i class=" fa fa-user fa-fw"></i> DNI
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm class_dni class_trans_priv" placeholder="Nº DNI" required value="${e.documento}" id="conductor_num_doc_${i}" data-index="${i}" data-obj="documento" onkeyup="GuiaremRemitenteAdd.setDataConductores(this)">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary " id="btnBuscarDni_${i}" onclick="GuiaremRemitenteAdd.VerificarDniConductor(event, ${i})" type="button">
                                <i class="fa fa-search fa-fw" id="iconBuscarDni_${i}"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 px-1">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend  ">
                            <label class="input-group-text ">
                                <i class=" fa fa-user fa-fw"></i> Licencia
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm class_trans_priv" placeholder="Licencia" required value="${e.licencia}" id="conductor_licencia_${i}" data-index="${i}" data-obj="licencia" onkeyup="GuiaremRemitenteAdd.setDataConductores(this)">
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 px-1">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend ">
                            <label class="input-group-text ">
                                <i class=" fa fa-user fa-fw"></i> Nombres
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm class_trans_priv" autocomplete="off" placeholder="Nombres" required value="${e.nombres}" id="conductor_nombres_${i}" data-index="${i}" data-obj="nombres" onkeyup="GuiaremRemitenteAdd.setDataConductores(this)">
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 px-1">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend ">
                            <label class="input-group-text ">
                                <i class=" fa fa-user fa-fw"></i> Apellidos
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm class_trans_priv" autocomplete="off" placeholder="Apellidos" required value="${e.apellidos}" id="conductor_apellidos_${i}" data-index="${i}" data-obj="apellidos" onkeyup="GuiaremRemitenteAdd.setDataConductores(this)">
                    </div>
                </div>
                ${
                    i != 0 ?
                    `<div class="col-md-1 px-2" style:"min-width:90px;max-width:90px">
                        <button class="btn btn-sm btn-danger" type="button" onclick="GuiaremRemitenteAdd.removerUnRegistro('conductor', ${i})">
                            <i class=" fa fa-trash fa-fw"></i>
                        </button>
                    </div>`
                    : ''
                }
            </div>
        `;
        });
        $('#guiaremTrans #transTipo').html('Conductores');
        $("#datosTrans").html(html_conductor);
        $('.class_dni').mask('00000000');
    },
    agregarVehiculoPrivado: function(){
        GuiaremRemitenteAdd.Vehiculos.push({
            tipo:'secundario', placa:''
        })
        GuiaremRemitenteAdd.GenerarFormVehiculoPrivado();
    },
    agregarConductorPrivado: function(){
        GuiaremRemitenteAdd.Conductores.push({
            documento:'',
            licencia:'',
            nombres:'',
            apellidos:'',
        })
        GuiaremRemitenteAdd.GenerarFormConductorPrivado();
    },
    setDataVehiculos : function(e){
        // $(e).attr('data-index') -> Es el INDEX de su posicion
        GuiaremRemitenteAdd.Vehiculos[$(e).attr('data-index')][`${$(e).attr('data-obj')}`] = e.value
    },
    setDataConductores : function(e){
        GuiaremRemitenteAdd.Conductores[$(e).attr('data-index')][`${$(e).attr('data-obj')}`] = e.value
    },
    removerUnRegistro : function(tipo, index){
        if(tipo == 'conductor'){
            GuiaremRemitenteAdd.Conductores.splice(index,1)
            GuiaremRemitenteAdd.GenerarFormConductorPrivado();
        }else if(tipo == 'vehiculo'){
            GuiaremRemitenteAdd.Vehiculos.splice(index,1)
            GuiaremRemitenteAdd.GenerarFormVehiculoPrivado();
        }
    },
    cambioVehiculoMenor : function(){
        if($("#vehiculo_menor").prop('checked')){
            $("#guiaremTrans").hide();
            $("#guiaremVehiculos").hide();
            $(".class_trans_priv").prop('disabled', true)
        }else{
            $("#guiaremTrans").show();
            $("#guiaremVehiculos").show();
            $(".class_trans_priv").prop('disabled', false)
        }
    },
    VerificarDniConductor : function(e, index){
        e.preventDefault();
        var dni = $(`#conductor_num_doc_${index}`).val();
        var endpoint = `https://www.factura24.pe/api/consulta-doc/dni/${dni}`;

        if( dni.length != 8){
            alert('El DNI Ingresado no es valido');
            return;
        }
        $(`#datosTrans #btnBuscarDni_${index}`).prop('disabled',true);
        $(`#datosTrans #iconBuscarDni_${index}`).removeClass('fa-search');
        $(`#datosTrans #iconBuscarDni_${index}`).addClass('fa-spinner');
        $(`#datosTrans #iconBuscarDni_${index}`).addClass('fa-spin');

        $.ajax({
            url: endpoint,
            data:'',
            type:'GET',
            success: function(r){
                if(r.success){
                    $('#conductor_nombres_'+ index).val(r.data.nombres);
                    $('#conductor_apellidos_' + index).val(`${r.data.apellido_paterno} ${r.data.apellido_materno}`);
                    GuiaremRemitenteAdd.Conductores[index].nombres = r.data.nombres;
                    GuiaremRemitenteAdd.Conductores[index].apellidos = `${r.data.apellido_paterno} ${r.data.apellido_materno}`;
                    // $('#btnBuscarDni_' + index).prop('disabled',true);
                }else{
                    alert(r.message);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete : function(){
                $('#datosTrans #btnBuscarDni_' + index ).prop('disabled',false);
                $('#datosTrans #iconBuscarDni_' + index ).addClass('fa-search');
                $(`#datosTrans #iconBuscarDni_${index}`).removeClass('fa-spinner');
                $(`#datosTrans #iconBuscarDni_${index}`).removeClass('fa-spin');
            }


        })

    },
    validarFechas : function(){
        var fecha_emision = $("[name=fecha_emision]").val();
        var fecha_traslado = $("[name=fecha_traslado]").val();
        var hoy = new Date(fecha_emision);
        var t = new Date(fecha_traslado);
        if(t >= hoy){
            return true
        }
        alert("La fecha de traslado tiene que ser mayor a la de hoy.")
        return false
    },
    getEstablecimientos :   function(){
        $.ajax({
            url     :   API_FE + "/establecimientos/",
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data)
                if(data.success){
                    let str = '';
                    data.data.forEach((e)=>{
                        str += `<option value="${e.id}"> ${e.nombre} </option>`;
                    })
                    $("[name=codigo_establecimiento_origen]").html(str);
                    $("[name=codigo_establecimiento_destino]").html(str);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

        
        
    },
    setEstablecimiento:  function(){
        $.ajax({
            url     :   API_FE + "/establecimientos/get-one/" + establecimiento_default,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data)
                if(data.success == true){
                    $("[name=establecimiento_id]").val(data.data.id);
                    $("[name=establecimiento_codigo]").val(data.data.codigo);
                    $("[name=establecimiento_ubigeo]").val(data.data.ubigeo);
                    
                    $("[name=establecimiento_departamento]").val(data.data.departamento);
                    $("[name=establecimiento_provincia]").val(data.data.provincia);
                    $("[name=establecimiento_distrito]").val(data.data.distrito);
                    $("[name=establecimiento_urbanizacion]").val(data.data.urbanizacion);
                    $("[name=establecimiento_direccion]").val(data.data.direcicon);
                    $("[name=establecimiento]").val(data.data.nombre);

                    $("[name=partida_ubigeo]").val(data.data.ubigeo);
                    $("[name=partida_ubigeo_full]").val(data.data.ubigeo_dpr);

                    $("[name=partida_direccion]").val(`${data.data.urbanizacion} ${data.data.direccion}`);

                }else{
                    alert(data.message);
                }
                GuiaremRemitenteAdd.setSeries();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    },
    setSeries:  function(){
        var estab = $("[name=establecimiento_id]").val();
        $.ajax({
            url     :   API_FE + `/sunat-fe-series/get-series-by-almacen?tipo=GRE_TRANSPORTISTA&establecimiento_id=${estab}`,
            data    :   '',
            type    :   'GET',
            dataType:   'JSON',
            success :   function (data, status, xhr) {
                console.log(data)
                if(data.success == true){
                    var str = '';
                    data.data.forEach(e => {    
                        str += `<option value="${e.serie}"> ${e.serie} </option>`
                    })
                    $("[name=serie]").html(str)
                }else{
                    alert(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
        });
    },
};

