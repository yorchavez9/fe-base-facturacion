<div class="table ">
    <div class="">
        <table class="table table-bordered " >

            <tr>
                <td width="200px">RAZÓN SOCIAL</td>
                <td colspan="5"><b><?= $cliente->razon_social ?></b></td>
            </tr>
            <tr>
                <td>NOMBRE COMERCIAL</td>
                <td colspan="5"><?= $cliente->nombre_comercial ?></td>
            </tr>
            <tr>
                <td>DOMICILIO FISCAL</td>
                <td colspan="5"><?= $cliente->domicilio_fiscal ?></td>
            </tr>
            <tr>
                <td>RUC</td>
                <td colspan="2"><?= $cliente->ruc ?></td>
                <td >UBIGEO</td>
                <td colspan="2"><?= $cliente->ubigeo_dpr ?></td>
            </tr>
            <tr>
                <td>F. CREACIÓN</td>
                <td colspan="2"><?= $cliente->created ? $cliente->created->format('Y/m/d h:m:s A') : "" ?></td>
                <td width="150px">ESTADO</td>
                <td colspan="2"><?= $cliente->estado ?></td>
            </tr>
            <tr>
                <td>CORREO</td>
                <td><?= $cliente->correo ?></td>
                <td width="150px">CELULAR</td>
                <td><?= $cliente->celular ?></td>
                <td width="150px">TELEFONO</td>
                <td><?= $cliente->telefono ?></td>

            </tr>
        </table>
        <br>

        <form id="formConsultar">
            <div class="row align-items-end">

                <div class="col-3">
                    <label>F. Inicio:</label>
                    <input type="text" name="f_inicio" class="form-control" value="<?=$f_inicio ?? ""?>" autocomplete="off" placeholder="Inicio">
                </div>

                <div class="col-3">
                    <label>F. Fin:</label>
                    <input type="text" name="f_fin" class="form-control" value="<?=$f_fin ?? ""?>" autocomplete="off" placeholder="Fin">
                </div>

                <div class="col-3">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar</button>
                </div>

            </div>
        </form>

        <br>

        <div class="card">
            <div class="card-header row justify-content-between">
                <a class="btn btn-sm btn-link py-0" data-toggle="collapse"
                   href="#tablaVentas" role="button" aria-expanded="false" aria-controls="tablaVentas">
                    Ventas
                </a>
            </div>

            <div class="collapse multi-collapse" id="tablaVentas">
                <div class="card card-body">
                    <table class="table table-striped">
                        <thead class="text text-primary font-weight-bold">
                        <tr>
                            <th style="width: 120px;">Fecha / Hora</th>
                            <th style="width: 150px;">Documento</th>
                            <th style="width: 200px;" colspan="2">Totales</th>
                            <th style="width: 150px;">Enviar a Sunat</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ventas as $v): ?>
                            <tr class="<?= ($v->total_deuda > 0) ? "warning":""?>">
                                <td>
                                    <i class="fas fa-calendar-alt fa-fw"></i> <?= $v->fecha_venta->format("d/m/Y")?><br/>
                                    <i class="fas fa-clock fa-fw"></i> <?= $v->fecha_venta->format("H:i:s")?>
                                </td>
                                <td>
                                    <?= $documento_tipos[$v->documento_tipo] ?? "" ?><br/>
                                    <?= $v->documento_serie?>-<?= $v->documento_correlativo ?>
                                </td>
                                <td>
                                    Subtotal<br/>
                                    IGV<br/>
                                    Total
                                </td>

                                <td class="text-right" style="width: 180px;">
                                    <?= $this->Number->format($v->subtotal, ['places' => 2])?> <?= $v->tipo_moneda?><br/>
                                    <?= $this->Number->format($v->igv_monto, ['places' => 2])?> <?= $v->tipo_moneda?><br/>
                                    <?= $this->Number->format($v->total, ['places' => 2])?> <?= $v->tipo_moneda?>
                                </td>
                                <td id="row_<?= $v->id ?>">
                                    <?php if ($v->documento_tipo == 'BOLETA') : ?>
                                        <i>Envío Automático Programado en Resumen Diario</i>
                                    <?php elseif ($v->documento_tipo == 'FACTURA'): ?>
                                        <?php if ($v->estado_sunat != 'ACEPTADO'): ?>
                                            <a href="javascript:void(0)" data-factura-id="<?= $v->factura_id?>" onclick="MisVentas.enviarFacturaSunat(this)">
                                                <i class="fas fa-upload fa-fw"></i> Enviar a Sunat
                                            </a>
                                        <?php else: ?>
                                            Comprobante Aceptado
                                        <?php endif; ?>
                                    <?php elseif($v->documento_tipo == 'NOTAVENTA'):?>
                                        No aplica a Nota de Venta
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <?php
                                            echo $this->Html->Link("<i class='fa fa-search fa-fw'></i> Detalles", ['action' => 'detalles', $v->id], ['class' =>'dropdown-item', 'escape' => FALSE]);

                                            if ($v->documento_tipo == '01'){
                                                echo "<a class='dropdown-item' href='javascript: void(0);' onclick='MisVentas.verificarCpe({$v->id})'><i class='fa fa-check fa-fw'></i> Verificar Factura</a>";
                                            }
                                            echo "<a class='dropdown-item' href='javascript: void(0);' data-cliente='{$v->cliente_razon_social}' data-venta-id='{$v->id}' onclick='MisVentas.eliminar(this)'><i class='fa fa-trash fa-fw'></i> Eliminar</a>";

                                            if($v->total_deuda > 0){
                                                echo $this->Html->Link("<i class='fas fa-money-bill fa-fw'></i> Pagar", ['action' => 'nuevoPago', $v->id], ['class' =>'dropdown-item', 'escape' => FALSE]);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
