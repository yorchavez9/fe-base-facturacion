
<div class="modal fade" id="formGlobalServicios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="GlobalServicios.guardar(event)">
                    <div class="form-row mb-2">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-shield-alt fa-fw"></i>
                                </span>
                                </div>
                                <input type="text" placeholder="Código" autocomplete="off" name="codigo_" class="form-control form-control-sm" required/>
                            </div>
                        </div>
                        <div class="col-md-8 mt-1 mt-sm-0">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-taxi fa-fw"></i> Servicios
                                </span>
                                </div>
                                <input type="text" name="servicio_nombre" id="servicio_nombre" placeholder="Buscar Servicio" class="form-control form-control-sm" >
                                <input type="text" hidden name="opt_item_id" id="opt_item_id" >
                            </div>

                        </div>
                    </div>

                    <div class="row mb-4 mb-sm-4">
                        <div class="col-md-12">
                            <textarea placeholder="Descripción del Servicio" name="servicio" class="form-control form-control-sm" required></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <input type="text" hidden name="unidad" class="form-control form-control-sm" value="ZZ"/>

                        <div class="col-md-5 mt-1 mt-sm-0">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-cash-register fa-fw"></i>
                                </span>
                                </div>
                                <input type="number" step="any" autocomplete="off" placeholder="Precio" name="precio" value="0" class="form-control form-control-sm" required/>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-money-bill-wave-alt fa-fw"></i>&nbsp; Moneda
                                </span>
                                </div>
                                <select name="tipo_moneda" id="" class="form-control form-control-sm" >
                                    <option value="PEN"> Soles (PEN) </option>
                                    <option value="USD"> Dólares (USD) </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col-md-4 mt-1 mt-sm-0">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Inc. IGV
                                </span>
                                </div>
                                <select name="serv_inc_igv" id="serv_inc_igv" class="form-control form-control-sm">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 mt-1 mt-sm-0" >
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Afec. IGV</span>
                                </div>
                                <select class="form-control"  name="afectacion_igv">
                                    <option value="10">Gravado - Operación Onerosa</option>
                                    <option value="20">Exonerado - Operación Onerosa</option>
                                    <option value="30">Inafecto - Operación Onerosa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row  mx-2 justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fa fa-save fa-fw"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var GlobalServicios =
        {
            idModal : 'formGlobalServicios',
            servicios  :    [],
            init    :   function()
            {
                GlobalServicios.getAllServices();
                $(`#${GlobalServicios.idModal} #servicio_nombre`).autocomplete({
                    serviceUrl: base + 'items/ajax-autocomplete-services',
                    onSelect: function (item) {
                        console.log(item);
                        GlobalServicios.fetchServicio(item.id);
                        //nuevaVenta.clienteEstablecerDatos(item);
                    }
                });

            },
            abrir   :   function()
            {
                GlobalServicios.limpiar();
                $(`#${GlobalServicios.idModal}`).modal('show');
            },
            getAllServices  :   function()
            {
                var endpoint = `${base}items/get-all-services`
                $.ajax({
                    url : endpoint,
                    data: '',
                    type: 'GET',
                    success :   function(data)
                    {
                        if(data.success)
                        {
                            GlobalServicios.servicios = data.data;
                            console.log("Servicios");
                            console.log(GlobalServicios.servicios);
                        }
                    },
                    error  :    function(xhr,ajaxOptions,thrownError){
                        console.log(xhr.status);
                        console.log(thrownError);
                    }
                })
            },
            fetchServicio   :   function(item_id)
            {
                $(`#${GlobalServicios.idModal} [name=opt_item_id]`).val(item_id);
                for (var i = 0; i < GlobalServicios.servicios.length; i++) {
                    if (item_id == GlobalServicios.servicios[i].id) {
                        $(`#${GlobalServicios.idModal} [name=codigo_]`).val(GlobalServicios.servicios[i].codigo);
                        $(`#${GlobalServicios.idModal} [name=servicio]`).val(GlobalServicios.servicios[i].nombre);
                        $(`#${GlobalServicios.idModal} [name=precio]`).val(parseFloat(GlobalServicios.servicios[i].precio_venta).toFixed(2));
                        break;
                    }
                }
            },
            limpiar :   function()
            {
                $(`#${GlobalServicios.idModal} [name=serv_inc_igv]`).val('1');
                $(`#${GlobalServicios.idModal} [name=codigo_]`).val('');
                $(`#${GlobalServicios.idModal} [name=opt_item_id]`).val('');
                $(`#${GlobalServicios.idModal} [name=servicio_nombre]`).val('');
                $(`#${GlobalServicios.idModal} [name=precio]`).val('');
                $(`#${GlobalServicios.idModal} [name=servicio]`).val('');
                $(`#${GlobalServicios.idModal} [name=cantidad]`).val('');
                $(`#${GlobalServicios.idModal} [name=unidad]`).val('ZZ');
            },
            guardar     :   function(e)
            {
                e.preventDefault();
                var
                    id              = $(`#${GlobalServicios.idModal} [name=opt_item_id]`).val(),
                    serv_inc_igv    = $(`#${GlobalServicios.idModal} [name=serv_inc_igv]`).val(),
                    codigo          = $(`#${GlobalServicios.idModal} [name=codigo_]`).val(),
                    precio          = $(`#${GlobalServicios.idModal} [name=precio]`).val(),
                    servicio        = $(`#${GlobalServicios.idModal} [name=servicio]`).val(),
                    cantidad        = $(`#${GlobalServicios.idModal} [name=cantidad]`).val(),
                    unidad          = $(`#${GlobalServicios.idModal} [name=unidad]`).val(),
                    tipo_moneda     = $(`#${GlobalServicios.idModal} [name=tipo_moneda]`).val(),
                    afectacion_igv  = $(`#${GlobalServicios.idModal} [name=afectacion_igv]`).val();

                var itemObj = {
                        id          :   id,
                        serv_inc_igv:   serv_inc_igv,
                        codigo      :   codigo,
                        precio      :   precio,
                        servicio    :   servicio,
                        cantidad    :   cantidad,
                        unidad      :   unidad,
                        tipo_moneda :   tipo_moneda,
                        afectacion_igv  :   afectacion_igv
                    };
                console.log(itemObj);
                GlobalServicios.callback(itemObj);
                GlobalServicios.limpiar();

                $(`#${GlobalServicios.idModal}`).modal('hide');

            },
            callback    :   function() {},


        };




</script>
