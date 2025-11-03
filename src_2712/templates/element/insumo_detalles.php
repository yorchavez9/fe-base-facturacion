<div class="modal fade"
     id="insumoDetalles"
     tabindex="-1" role="dialog"
     aria-labelledby="detallesLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesLabel">Serivios Afiliados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>P. Servicio</th>
                    </tr>
                    </thead>

                    <tbody id="listadoServicios">

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var InsumoDetalles = {
        Limpiar: function () {
            $('#listadoServicios').html('');
        },

        Nuevo: function (id) {
            this.Limpiar();
            $('#insumoDetalles').modal('show');
            var endpoint = `${base}items/get-servicios-por-insumo/${id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    if (r.success) {
                        var html = ``;
                        $(r.data).each(function (i, o) {
                            if (o.item != null) {


                                html += `<tr>
                                             <td>${o.item.codigo}</td>
                                             <td>${o.item.nombre}</td>
                                             <td>${o.item.es_visible == "1" ? "Es Visible" : "No Visible"}</td>
                                             <td>${o.item.precio_venta}</td>
                                         </tr>`;
                            }
                        });

                        $('#listadoServicios').html(html);
                    }

                }
            });
        }
    };
</script>



