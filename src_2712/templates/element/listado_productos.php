<div class="modal fade"
     id="listadoProductosModal"
     tabindex="-1" role="dialog"
     aria-labelledby="listadoProductosLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listadoProductosLabel">Productos Requeridos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table cellspacing="0" class="table table-responsive-sm">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>P. Venta</th>
                    </tr>
                    </thead>
                    <tbody id="listadoProductosTable">

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script>
    ListadoProductos = {
        Limpiar: function () {
            $(`#listadoProductosTable`).html('')
        },

        Nuevo: function (servicio_id) {
            this.Limpiar();
            $(`#listadoProductosModal`).modal('show');
            var endpoint = `${base}items/get-for-service/${servicio_id}`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    var html = "";
                    if (r.success) {
                        $(r.data).each(function (i, o) {
                            html += `
                            <tr>
                                <td>${o.codigo}</td>
                                <td>${o.nombre}</td>
                                <td>${o.precio_venta}</td>
                            </tr>
                            `;
                        });

                        if (r.data.length === 0) {
                            html += `
                            <tr>
                                <td>Servicio sin productos</td>
                                <td></td>
                                <td></td>
                            </tr>
                            `;
                        }
                    }
                    $(`#listadoProductosTable`).html(html);
                }
            });
        },

        Cerrar: function () {
            $(`#listadoProductosModal`).modal('hide');
        }
    };
</script>




