<div class="row mb-3">
    <div class="col-md-12">
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-excel"></i> Detalle de Comunicación de Baja</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-building"></i> Información del Emisor</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">RUC:</td>
                                <td><strong><?= $comunicacion['emisor_ruc'] ?? 'N/A' ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Razón Social:</td>
                                <td><?= $comunicacion['emisor_razon_social'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nombre Comercial:</td>
                                <td><?= $comunicacion['emisor_nombre_comercial'] ?? 'N/A' ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-calendar"></i> Fechas</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="50%">Fecha Generación:</td>
                                <td><strong><?= $comunicacion['fecha_generacion'] ?? 'N/A' ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fecha Comunicación:</td>
                                <td><strong><?= $comunicacion['fecha_comunicacion'] ?? 'N/A' ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Creado:</td>
                                <td><?= isset($comunicacion['created']) ? date('d/m/Y H:i', strtotime($comunicacion['created'])) : 'N/A' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary"><i class="fas fa-file-invoice"></i> Documento Anulado</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="20%">Tipo Documento:</td>
                                <td>
                                    <?php
                                    $tipos = ['01' => 'Factura', '03' => 'Boleta', '07' => 'Nota de Crédito', '08' => 'Nota de Débito'];
                                    echo $tipos[$comunicacion['documento_tipo']] ?? $comunicacion['documento_tipo'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Serie - Correlativo:</td>
                                <td><strong class="text-dark"><?= $comunicacion['documento_serie'] ?>-<?= $comunicacion['documento_correlativo'] ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Motivo de Baja:</td>
                                <td><?= $comunicacion['motivo_baja'] ?? 'Sin motivo especificado' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary"><i class="fas fa-info-circle"></i> Información Técnica</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" width="20%">Identificador:</td>
                                <td><code>RA-<?= str_replace('-', '', $comunicacion['fecha_comunicacion']) ?>-<?= $comunicacion['correlativo'] ?></code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nombre Único:</td>
                                <td><code><?= $comunicacion['nombre_unico'] ?? 'N/A' ?></code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Ticket SUNAT:</td>
                                <td><code><?= $comunicacion['ticket'] ?: 'No generado' ?></code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Archivo XML:</td>
                                <td><?= $comunicacion['nombre_archivo_xml'] ?: 'No generado' ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Archivo CDR:</td>
                                <td><?= $comunicacion['nombre_archivo_cdr'] ?: 'No recibido' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-signal"></i> Estado SUNAT</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <?php
                    $badgeClass = 'secondary';
                    switch($comunicacion['estado']) {
                        case 'ENVIADO':
                            $badgeClass = 'info';
                            break;
                        case 'ACEPTADO':
                            $badgeClass = 'success';
                            break;
                        case 'RECHAZADO':
                            $badgeClass = 'danger';
                            break;
                    }
                    ?>
                    <span class="badge badge-<?= $badgeClass ?> p-3" style="font-size: 1.2rem;">
                        <?= $comunicacion['estado'] ?>
                    </span>
                </div>

                <?php if (!empty($comunicacion['sunat_err_code']) || !empty($comunicacion['sunat_err_message'])): ?>
                <div class="alert alert-warning">
                    <strong>Error SUNAT:</strong><br>
                    <small>
                        <strong>Código:</strong> <?= $comunicacion['sunat_err_code'] ?? 'N/A' ?><br>
                        <strong>Mensaje:</strong> <?= $comunicacion['sunat_err_message'] ?? 'N/A' ?>
                    </small>
                </div>
                <?php endif; ?>

                <?php if (!empty($comunicacion['sunat_cdr_code']) || !empty($comunicacion['sunat_cdr_description'])): ?>
                <div class="alert alert-info">
                    <strong>Respuesta CDR:</strong><br>
                    <small>
                        <strong>Código:</strong> <?= $comunicacion['sunat_cdr_code'] ?? 'N/A' ?><br>
                        <strong>Descripción:</strong> <?= $comunicacion['sunat_cdr_description'] ?? 'N/A' ?>
                    </small>
                </div>
                <?php endif; ?>

                <?php if (!empty($comunicacion['sunat_cdr_notes'])): ?>
                <div class="alert alert-secondary">
                    <strong>Notas CDR:</strong><br>
                    <small><?= $comunicacion['sunat_cdr_notes'] ?></small>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="fas fa-download"></i> Archivos</h6>
            </div>
            <div class="card-body">
                <?php if ($comunicacion['nombre_archivo_xml']): ?>
                <a href="<?= $this->Url->build(['action' => 'descargar-xml', $comunicacion['id']]) ?>" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-file-code"></i> Descargar XML
                </a>
                <?php endif; ?>

                <?php if ($comunicacion['nombre_archivo_cdr']): ?>
                <a href="<?= $this->Url->build(['action' => 'descargar-cdr', $comunicacion['id']]) ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-file-archive"></i> Descargar CDR
                </a>
                <?php endif; ?>

                <?php if (!$comunicacion['nombre_archivo_xml'] && !$comunicacion['nombre_archivo_cdr']): ?>
                <p class="text-muted text-center mb-0">
                    <i class="fas fa-inbox"></i><br>
                    No hay archivos disponibles
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
