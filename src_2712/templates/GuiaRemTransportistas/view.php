<?php
/**
 * TODO falta enlazar los items al kardex, que al dar clic me muestre el detalle del item: stock, historial (kardex)
 */


$guiaRemRemitente->motivo_traslado = (strlen($guiaRemRemitente->motivo_traslado)==2) ? $guiaRemRemitente->motivo_traslado : "0". $guiaRemRemitente->motivo_traslado;

?>

<div class="row">
    <div class="col-md-12 text-center">
        <h3>Información de la Guía de Remisión <?= $guiaRemRemitente->serie?>-<?= $guiaRemRemitente->correlativo?></h3>
    </div>
</div>

<div class="row pt-3">
    <div class="col-md-6">
        <h5>Dirección de Partida</h5>
        <p>
            <?= $guiaRemRemitente->partida_direccion?><br/>
            <?= $guiaRemRemitente->partida_ubigeo_full?>
        </p>
    </div>
    <div class="col-md-6">
        <h5>Dirección de Llegada</h5>
        <p>
            <?= $guiaRemRemitente->llegada_direccion?>
            <?= $guiaRemRemitente->llegada_ubigeo_full?>
        </p>
    </div>
</div>
<div class="row pt-3">
    <div class="col-md-6">
        <h5>Remitente</h5>
        <p>
            <?= $guiaRemRemitente->emisor_razon_social?><br/>
            <?= $guiaRemRemitente->emisor_ruc?>
        </p>
    </div>
    <div class="col-md-6">
        <h5>Destinatario</h5>
        <p>
            <?= $guiaRemRemitente->cliente_razon_social?><br/>
            <?= ($guiaRemRemitente->cliente_tipo_doc == 6) ? "RUC":"DNI"?>:
            <?= $guiaRemRemitente->cliente_num_doc?>
        </p>
    </div>
</div>
<div class="row pt-3">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>Codigo</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                </tr>
                <?php
                    $registros = json_decode($guiaRemRemitente->registros, true);
                    foreach ($registros as $reg) :
                ?>
                    <tr>
                        <td><?= $reg['item_codigo'] ?></td>
                        <td><?= $reg['item_nombre'] ?></td>
                        <td><?= $reg['item_unidad'] ?></td>
                        <td><?= $reg['cantidad'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<div class="row pt-3">
    <div class="col-md-12">
        <h5>Detalles del Transporte</h5>
        <div class="row">
            <div class="col-md-6">
                Motivo de Traslado: <?= $traslado_motivos[$guiaRemRemitente->motivo_traslado] ?><br/>
                Peso Total: <?=$guiaRemRemitente->peso_total?> <br/>
                Modo de Traslado: <?= ($guiaRemRemitente->modo_traslado == '01') ? "Transporte Público" : "Transporte Privado" ?>
            </div>
            <div class="col-md-6">
                Fecha de Emisión: <?= $guiaRemRemitente->fecha_emision->format("d/m/Y");?>
                <br/>
                Fecha de Inicio de Traslado: <?= $guiaRemRemitente->fecha_traslado->format("d/m/Y");?>
            </div>
        </div>
    </div>
</div>
<div class="row pt-3">
    <div class="col-md-12">
        <h5>Observaciones</h5>
    </div>
    <div class="col-md-12">
        <?= $guiaRemRemitente->observaciones?>
    </div>
</div>
<hr/>
<div class="row pt-3">
    <div class="col-md-12 text-center">
        <h3>Detalles de Facturación Electrónica</h3>
        <table class="table text-left">
            <tr
                style="<?= $guiaRemRemitente->estado != 'ACEPTADO' && $guiaRemRemitente->estado != 'ACTIVO'  ? 'background-color:#F8C471 ' : '' ?>"
            >
                <th>Estado</th>
                <td><?= $guiaRemRemitente->estado?></td>
            </tr>

            <tr>
                <th>Código de Error Sunat</th>
                <td><?= $guiaRemRemitente->sunat_err_code?></td>
            </tr>

            <tr>
                <th>Mensaje de Error Sunat</th>
                <td><?= $guiaRemRemitente->sunat_err_message?></td>
            </tr>
            <tr>
                <th>Numero de Ticket</th>
                <td><?= $guiaRemRemitente->sunat_num_ticket ?></td>
            </tr>
            <tr>
                <th>CDR: Código de Respuesta</th>
                <td><?= $guiaRemRemitente->sunat_cdr_code?></td>
            </tr>

            <tr>
                <th>CDR: Descripcion</th>
                <td><?= $guiaRemRemitente->sunat_cdr_description?></td>
            </tr>

            <tr>
                <th>CDR: Notas</th>
                <td><?= $guiaRemRemitente->sunat_cdr_notes ?></td>
            </tr>
            <tr>
                <th>Acciones</th>
                <td>
                    <?php if ($guiaRemRemitente->sunat_num_ticket == ''): ?>
                        <button class="btn btn-sm btn-outline-primary" onclick="View.generarXml(<?= $guiaRemRemitente->id?>)">Generar XML</button>
                        <button class="btn btn-sm btn-outline-primary" onclick="View.enviarSunat(<?= $guiaRemRemitente->id?>)">Enviar a Sunat</button>
                    <?php endif;  ?>
                    <?php if ($guiaRemRemitente->sunat_num_ticket != ''):?>
                        <button class="btn btn-sm btn-outline-primary" onclick="View.obtenerCDR(<?= $guiaRemRemitente->id?>)">Obtener CDR</button>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Descargas</th>
                <td>
                    <?= $this->Html->Link("Descargar XML", ['action' => 'descargarXml', $guiaRemRemitente->id],['class' => 'btn btn-sm btn-outline-primary'])?>
                    <?php if($guiaRemRemitente->sunat_cdr_description != ''): ?>
                    <?= $this->Html->Link("Descargar CDR", ['action' => 'descargarCdr', $guiaRemRemitente->id],['class' => 'btn btn-sm btn-outline-primary'])?>
                    <?= $this->Html->Link("Descargar PDF", ['action' => 'descargarPdfSunat', $guiaRemRemitente->id],['class' => 'btn btn-sm btn-primary', 'target' => "_blank"])?>
                    <?php else: ?>
                    <p>
                        Para descargar el CDR o la impresión, primero obtenga el CDR
                    </p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
