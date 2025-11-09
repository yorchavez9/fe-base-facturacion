<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-excel"></i> Comunicación de Bajas - SUNAT</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Las comunicaciones de baja permiten anular comprobantes electrónicos (facturas, boletas, notas de crédito y débito) ante SUNAT.
                </p>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Nota:</strong> La comunicación de baja de facturas se envía el mismo día de emisión.
                    Para boletas, se puede enviar hasta 7 días calendario después de la emisión.
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Comunicación:</label>
                            <input type="text" class="form-control" id="fecha_comunicacion" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Estado:</label>
                            <select class="form-control" id="estado_filter">
                                <option value="">- Todos -</option>
                                <option value="PENDIENTE">Pendiente</option>
                                <option value="ENVIADO">Enviado</option>
                                <option value="ACEPTADO">Aceptado</option>
                                <option value="RECHAZADO">Rechazado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-right mb-3">
                    <button class="btn btn-primary" id="btnNuevaComunicacion">
                        <i class="fas fa-plus"></i> Nueva Comunicación de Baja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover" id="tablaComunicaciones">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 120px;">Fecha</th>
                                <th style="width: 150px;">Identificador</th>
                                <th>Comprobantes</th>
                                <th style="width: 120px;">Estado</th>
                                <th style="width: 200px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_comunicaciones_bajas">
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No hay comunicaciones de baja registradas</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Comunicación -->
<div class="modal fade" id="modalNuevaComunicacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Nueva Comunicación de Baja</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNuevaComunicacion">
                    <div class="form-group">
                        <label>Fecha de Emisión de Baja:</label>
                        <input type="date" class="form-control" name="fecha_emision_baja" required>
                    </div>

                    <div class="form-group">
                        <label>Motivo de Baja:</label>
                        <textarea class="form-control" name="motivo_baja" rows="3" required placeholder="Ingrese el motivo de la baja..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Comprobantes a dar de baja:</label>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Seleccione los comprobantes que desea incluir en la comunicación de baja.
                        </div>
                        <div id="listaComprobantes">
                            <!-- Aquí se cargarán los comprobantes disponibles -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnGuardarComunicacion">
                    <i class="fas fa-save"></i> Guardar y Enviar a SUNAT
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Cargar comunicaciones
    function cargarComunicaciones() {
        $.ajax({
            url: base + 'sunat-fe-comunicacion-bajas/listar',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success && response.data.length > 0) {
                    var html = '';
                    response.data.forEach(function(item) {
                        var estadoBadge = '';
                        switch(item.estado) {
                            case 'ENVIADO':
                                estadoBadge = '<span class="badge badge-info">Enviado</span>';
                                break;
                            case 'ACEPTADO':
                                estadoBadge = '<span class="badge badge-success">Aceptado</span>';
                                break;
                            case 'RECHAZADO':
                                estadoBadge = '<span class="badge badge-danger">Rechazado</span>';
                                break;
                            default:
                                estadoBadge = '<span class="badge badge-secondary">' + item.estado + '</span>';
                        }

                        html += '<tr>';
                        html += '<td>' + item.fecha_generacion + '</td>';
                        html += '<td>RA-' + item.fecha_comunicacion.replace(/-/g, '') + '-' + item.correlativo + '</td>';
                        html += '<td>';
                        html += '<strong>' + item.documento_tipo + '-' + item.documento_serie + '-' + item.documento_correlativo + '</strong><br>';
                        html += '<small class="text-muted">' + (item.motivo_baja || 'Sin motivo') + '</small>';
                        html += '</td>';
                        html += '<td>' + estadoBadge + '</td>';
                        html += '<td>';
                        html += '<button class="btn btn-sm btn-info" onclick="verDetalle(' + item.id + ')"><i class="fas fa-eye"></i> Ver</button> ';
                        if(item.nombre_archivo_xml) {
                            html += '<button class="btn btn-sm btn-success" onclick="descargarXML(' + item.id + ')"><i class="fas fa-file-code"></i> XML</button> ';
                        }
                        if(item.nombre_archivo_cdr) {
                            html += '<button class="btn btn-sm btn-primary" onclick="descargarCDR(' + item.id + ')"><i class="fas fa-file-archive"></i> CDR</button>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#tabla_comunicaciones_bajas').html(html);
                } else {
                    $('#tabla_comunicaciones_bajas').html('<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-inbox fa-3x mb-3"></i><p>No hay comunicaciones de baja registradas</p></td></tr>');
                }
            },
            error: function() {
                $('#tabla_comunicaciones_bajas').html('<tr><td colspan="5" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error al cargar las comunicaciones</td></tr>');
            }
        });
    }

    // Abrir modal nueva comunicación
    $('#btnNuevaComunicacion').click(function() {
        $('#modalNuevaComunicacion').modal('show');
    });

    // Guardar comunicación
    $('#btnGuardarComunicacion').click(function() {
        var formData = $('#formNuevaComunicacion').serialize();

        $.ajax({
            url: base + 'sunat-fe-comunicacion-bajas/guardar',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    alert('Comunicación de baja enviada correctamente');
                    $('#modalNuevaComunicacion').modal('hide');
                    cargarComunicaciones();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error al procesar la solicitud');
            }
        });
    });

    // Cargar al inicio
    cargarComunicaciones();

    // Filtro por estado
    $('#estado_filter').change(function() {
        cargarComunicaciones();
    });
});

function verDetalle(id) {
    alert('Ver detalle de comunicación #' + id);
}

function descargarXML(id) {
    window.open(base + 'sunat-fe-comunicacion-bajas/descargar-xml/' + id, '_blank');
}

function descargarCDR(id) {
    window.open(base + 'sunat-fe-comunicacion-bajas/descargar-cdr/' + id, '_blank');
}
</script>
