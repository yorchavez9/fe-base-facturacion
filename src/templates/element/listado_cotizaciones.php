<div class="modal fade"
     id="listadoCotizacionesModal"
     tabindex="-1" role="dialog"
     aria-labelledby="listadoCotizacionesLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listadoCotizacionesLabel">Listado de Proformas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <fieldset>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Correlativo</th>
                            <th>Generado Por</th>
                            <th>Proyecto</th>
                            <th>Moneda</th>
                            <th>Monto Total</th>
                            <th>Tipo</th>
                            <th>Protegido</th>
                            <th>Acciones</th>

                        </tr>
                        </thead>

                        <tbody id="listadoCotizaciones">

                        </tbody>

                    </table>

                </fieldset>


            </div>
        </div>
    </div>
</div>

<script>
    var ListadoCotizaciones = {
        IdModal: 'listadoCotizacionesModal',
        Limpiar: function () {
            $('#listadoCotizaciones').html('');
        },

        Nuevo: function (p_id) {
            this.Limpiar();
            $('#'+this.IdModal).modal('show');
            this.poblarTabla(p_id);
        },

        Cerrar: function () {
            $("#"+this.IdModal).modal('hide');
            this.Limpiar();
        },

        poblarTabla: function (p_id) {
            var endpoint = `${base}cotizaciones/get-all/${p_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        $('#listadoCotizaciones').html('');
                        $(r.data).each(function (i, o) {
                            var acciones = ``;
                            var fila = `<tr>
                                            <td>${o.correlativo}</td>
                                            <td>${o.usuario_id}</td>
                                            <td>${o.proyecto_id}</td>
                                            <td>${o.moneda}</td>
                                            <td>${o.monto_total}</td>
                                            <td>${o.tipo}</td>
                                            <td>${o.protegido}</td>
                                            <td>${acciones}</td>
                                        </tr>`;

                            $('#listadoCotizaciones').append(fila);
                        });


                    }
                }
            });
        }
    };
</script>

