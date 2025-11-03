<div class="modal fade"
     id="listadoCreditosPagarModal"
     tabindex="-1" role="dialog"
     aria-labelledby="listadoCreditosPagarModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listadoCreditosPagarModalLabel">Historial de Pago de Créditos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <fieldset>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Monto</th>
                            <th>Fecha vencimiento</th>
                            <th>Estado</th>
                            <th>Voucher</th>

                        </tr>
                        </thead>

                        <tbody id="listadoCronogramas">

                        </tbody>

                    </table>

                </fieldset>

            </div>

        </div>
    </div>
</div>
</div>


<script>
    var ListadoCreditosPagar = {

        Limpiar: function () {
            $('#listadoCronogramas').html('');
        },

        Nuevo: function (credito_id) {
            this.Limpiar();
            $('#listadoCreditosPagarModal').modal('show');
            this.poblarTabla(credito_id);
        },

        Cerrar: function () {
            $('#listadoCreditosPagarModal').modal('hide');
            this.Limpiar();
        },

        poblarTabla: function (credito_id) {
            var endpoint = `${base}credito-cronogramas/get-all/${credito_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    if (r.success) {
                        $('#listadoCronogramas').html('');
                        $(r.data).each(function (i, o) {
                            var descargar = `No disponible`;
                            if (o.estado == 'PAGADO') {
                                descargar = `
                                        <a href="${base.replace("intranet/", "")}${o.ruta_adjunta}" target="_blank"><i class="fas fa-eye fa-fw"></i> Ver</a><br>
                                        <a href="${base.replace("intranet/", "")}${o.ruta_adjunta}" download="cuota_${i+1}"><i class="fas fa-download fa-fw"></i> Descargar</a>
                                `;
                            }
                            var fila = `
                                <tr>
                                      <td>${i+1}</td>
                                      <td>${parseFloat(o.monto).toFixed(2)}</td>
                                      <td>${o.fecha_vencimiento.substring(0, 10)}</td>
                                      <td>${o.estado}</td>
                                      <td>
                                        ${descargar}
                                      </td>
                                </tr>
                              `;

                            $('#listadoCronogramas').append(fila);
                        });

                    }
                }
            });
        }
    };
</script>
