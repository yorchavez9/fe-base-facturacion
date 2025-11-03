<div class="modal fade"
     id="formVentaRegistroModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formVentaRegistroLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formVentaRegistroLabel">Registro Item de Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formVentaRegistro" onsubmit="FormVentaRegistro.Guardar(event)">
                    <div class="row align-items-end">
                        <div class="col mb-2">
                            <label>Cambiar Item</label>
                            <input type="text" name="opt_item_id" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-6 col-lg-3 mb-2">
                            <label>ID: </label>
                            <input type="text" name="id" class="form-control" readonly>
                        </div>
                        <div class="col-6 col-lg-3 mb-2">
                            <label>Venta ID: </label>
                            <input type="text" name="venta_id" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-lg-3 mb-2">
                            <label>Venta Index: </label>
                            <input type="text" name="item_index" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-lg-3 mb-2">
                            <label>Item ID: </label>
                            <input type="text" name="item_id" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-12 mb-2">
                            <label>Item Código: </label>
                            <input type="text" name="item_codigo" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-5 mb-2">
                            <label>Item Nombre: </label>
                            <input type="text" name="item_nombre" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-7 mb-2">
                            <label>Item Comentario: </label>
                            <input type="text" name="item_comentario" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-6 col-lg-2 mb-2">
                            <label>Item Unidad: </label>
                            <input type="text" name="item_unidad" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-lg-2 mb-2">
                            <label>Cantidad: </label>
                            <input type="text" name="cantidad" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-lg mb-2">
                            <label>Precio Unit Compra: </label>
                            <input type="text" name="precio_ucompra" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-lg mb-2">
                            <label>Precio Unit Venta: </label>
                            <input type="text" name="precio_uventa" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg mb-2">
                            <label>Precio Unit Venta Sin IGV: </label>
                            <input type="text" name="valor_venta" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-6 col-lg-4 mb-2">
                            <label>Subtotal: </label>
                            <input type="text" name="subtotal" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-lg-4 mb-2">
                            <label>IGV Monto: </label>
                            <input type="text" name="igv_monto" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Precio Total: </label>
                            <input type="text" name="precio_total" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Guardar</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    var FormVentaRegistro = {

        IdFormulario: 'formVentaRegistro',

        IdModal: 'formVentaRegistroModal',

        Limpiar: function () {
            this.setCampo('id', '');
            this.setCampo('venta_id', '');
            this.setCampo('item_index', '');
            this.setCampo('item_id', '');
            this.setCampo('item_codigo', '');
            this.setCampo('item_nombre', '');
            this.setCampo('item_comentario', '');
            this.setCampo('item_unidad', '');
            this.setCampo('cantidad', '');
            this.setCampo('precio_ucompra', '');
            this.setCampo('precio_uventa', '');
            this.setCampo('valor_venta', '');
            this.setCampo('subtotal', '');
            this.setCampo('igv_monto', '');
            this.setCampo('precio_total', '');
        },

        setCampo  : function(field, value){
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },

        getCampo  : function(field){
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },

        getDatos: function () {
            var formElement = document.getElementById(this.IdFormulario);
            var formData = new FormData(formElement);

            return formData;
        },

        setDatos: function (d) {
            this.setCampo('id', d.id);
            this.setCampo('venta_id', d.venta_id);
            this.setCampo('item_index', d.item_index);
            this.setCampo('item_id', d.item_id);
            this.setCampo('item_codigo', d.item_codigo);
            this.setCampo('item_nombre', d.item_nombre);
            this.setCampo('item_comentario', d.item_comentario);
            this.setCampo('item_unidad', d.item_unidad);
            this.setCampo('cantidad', d.cantidad);
            this.setCampo('precio_ucompra', d.precio_ucompra);
            this.setCampo('precio_uventa', d.precio_uventa);
            this.setCampo('valor_venta', d.valor_venta);
            this.setCampo('subtotal', d.subtotal);
            this.setCampo('igv_monto', d.igv_monto);
            this.setCampo('precio_total', d.precio_total);
        },

        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
            this.Limpiar();
        },

        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}ventas/save-venta-registros`;
            var datos = this.getDatos();
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                success: function (r) {
                    if (!r.success) {
                        alert(r.message);
                    } else {
                        FormVentaRegistro.Cerrar();
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        Editar: function (id) {
            //var all_items = [];
            $('input[name=opt_item_id]').autocomplete({
                minChars: 0,
                autoSelectFirst: true,
                showNoSuggestionNotice: 'Sin resultados',
                lookup: all_items,
                onSelect: function (suggestion) {
                    console.log(suggestion);
                    $("input[name=item_id]").val(suggestion.id);
                    $("input[name=item_codigo]").val(suggestion.codigo);
                    $("input[name=item_nombre]").val(suggestion.nombre);
                    $("input[name=item_unidad]").val(suggestion.unidad);
                    $("input[name=precio_ucompra]").val(suggestion.precio_compra);
                    $("input[name=precio_uventa]").val(suggestion.precio_venta);

                    if (suggestion.inc_igv == "1") {
                        $("input[name=precio_uventa_sinigv]").val(parseFloat(parseFloat(suggestion.precio_venta) * 0.82).toFixed(2));
                    } else {
                        $("input[name=valor_venta").val(suggestion.precio_venta);
                    }

                    var cant = parseFloat($("input[name=cantidad]").val());
                    var pv = parseFloat($("input[name=precio_uventa]").val());
                    var pvs = parseFloat($("input[name=valor_venta]").val());

                    $("input[name=subtotal]").val(parseFloat(cant * pvs).toFixed(2));
                    $("input[name=igv_monto]").val(parseFloat(cant * (pv - pvs)).toFixed(2));
                    $("input[name=precio_total]").val(parseFloat(cant * pv).toFixed(2));


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

            $(`#${this.IdModal}`).modal('show');
            this.fetchDatos(id);
        },

        fetchDatos: function(id) {
            var endpoint = `${base}ventas/get-one-venta-registros/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type:'GET',
                success : function(r){
                    if(r.success){
                        FormVentaRegistro.setDatos(r.data);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        }

    };
</script>

<style>
    .autocomplete-suggestions {color:gray; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-no-suggestion { padding: 2px 5px;}
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: bold; color: #000; }
    .autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
</style>













