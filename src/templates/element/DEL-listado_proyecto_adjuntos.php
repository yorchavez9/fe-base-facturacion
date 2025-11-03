<div class="modal fade"
     id="listadoProyectoAdjuntosModal"
     tabindex="-1" role="dialog"
     aria-labelledby="listadoProyectoAdjuntosLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listadoProyectoAdjuntosLabel">Archivos Adjuntos</h5>
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
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Archivo</th>

                        </tr>
                        </thead>

                        <tbody id="listadoProyectoAdjuntoTabla">

                        </tbody>

                    </table>

                </fieldset>

            </div>
        </div>
    </div>
</div>

<script>
    var ListadoProyectoAdjuntos = {
        IdModal: 'listadoProyectoAdjuntosModal',

        IdTabla: 'listadoProyectoAdjuntoTabla',

        Limpiar: function () {
            $('#'+this.IdTabla).html('');
        },

        Nuevo: function (proyecto_id) {
            this.Limpiar();
            $('#'+this.IdModal).modal('show');
            this.poblarTabla(proyecto_id);
        },

        Cerrar: function () {
            $('#'+this.IdModal).modal('hide');
            this.Limpiar();
        },

        poblarTabla: function (proyecto_id) {
            var endpoint = `${base}proyecto-adjuntos/get-all/${proyecto_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    if (r.success) {
                        $('#'+ListadoProyectoAdjuntos.IdTabla).html('');
                        $(r.data).each(function (i, o) {
                            var descargar = `
                                        <a href="${base.replace("intranet/", "")}${o.ruta_adjunta}" target="_blank"><i class="fas fa-eye fa-fw"></i> Ver</a><br>
                                        <a href="${base.replace("intranet/", "")}${o.ruta_adjunta}" download="archivo_${i+1}"><i class="fas fa-download fa-fw"></i> Descargar</a>
                                `;

                            var fila = `
                                <tr>
                                      <td>${i+1}</td>
                                      <td>${o.nombre}</td>
                                      <td>${o.created.substring(0, 10)}</td>
                                      <td>
                                        ${descargar}
                                      </td>
                                </tr>
                              `;

                            $('#'+ListadoProyectoAdjuntos.IdTabla).append(fila);
                        });

                    }
                }
            });
        }
    };
</script>



