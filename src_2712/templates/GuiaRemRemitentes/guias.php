<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SunatFeGuiaremRemitente[]|\Cake\Collection\CollectionInterface $guiaRemRemitentes
 */
?>
<div class="guiaRemRemitentes index content">
    <form id="filtros_rem_remitentes" class="row">
        <div class="col-sm-6">
            <label>Filtros</label>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-store fa-fw"></i>
                            </span>
                        </div>
                        <select name="almacen" id="almacen" class="form-control form-control-sm" >
                            <option value="0">-Todos los almacenes-</option>
                            <?php   foreach ($almacenes as $almacen) { ?>
                                <option value="<?=$almacen->id?>"><?=$almacen->nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user fa-fw"></i>
                            </span>
                        </div>
                        <input name="cliente" id="cliente" class="form-control form-control-sm" placeholder="Razon Social" >
                    </div>
                </div>
            </div>

        </div>


        <div class="col-md-4 form-group">
            <label for="">Rango de fechas: </label>
            <div class="row">
                <div class="col-6">
                    <input type="text" name="fini" class="form-control form-control-sm text-center" placeholder="Inicio" value="<?=$fini?>">
                </div>
                <div class="col-6">
                    <input type="text" name="ffin" class="form-control form-control-sm text-center" placeholder="Fin" value="<?=$ffin?>">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <button class="btn btn-outline-primary btn-sm btn-block " name="buscar"> <i class="fa fa-search"></i> Buscar </button>
        </div>
    </form>

    <div class="table-responsive" style="min-height: 45vh;">
        <table class="table table table-sm">
            <thead>
            <tr class="text-primary font-weight-bold">
                <th>Venta</th>
                <th>Guía Remitente</th>
                <th>Detalles</th>
                <th class="actions">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($guiaRemRemitentes as $guiaRemRemitente): ?>
                <tr>
                    <td>
                        <i class="fa fa-fw fa-building"></i> <?= h($guiaRemRemitente->establecimiento) ?> <br>
                        <i class="fa fa-fw fa-file"></i> <?= $guiaRemRemitente->has('venta') ? $this->Html->link($guiaRemRemitente->venta->documento_serie . "-" . $guiaRemRemitente->venta->documento_correlativo, ['controller' => 'Ventas', 'action' => 'detalles', $guiaRemRemitente->venta->id]) : '' ?>
                    </td>
                    <td>
                        <i class="fa fa-fw fa-user"></i> <?= $guiaRemRemitente->cuenta ? $guiaRemRemitente->cuenta->razon_social : "" ?> <br>
                        <i class="fa fa-fw fa-id-card"></i> <?= h($guiaRemRemitente->serie) ?>-<?= str_pad("" . $guiaRemRemitente->correlativo, 8, "0", 0) ?>
                        <br>
                        <i class="fa fa-fw fa-calendar  "></i> F. Emision: <?= h($guiaRemRemitente->fecha_emision ? $guiaRemRemitente->fecha_emision->format('Y/m/d'): "") ?></td>
                    <td>
                        <i class="fa fa-fw fa-bus"></i> <?= h($guiaRemRemitente->modo_traslado == "1" ? "Transporte Público" : "Transporte Privado") ?> <br>
                        <i class="fa fa-fw fa-calendar-check"></i> Fecha: <?= h($guiaRemRemitente->fecha_traslado ? $guiaRemRemitente->fecha_traslado->format('Y/m/d') : "") ?>
                        <br>
                        <i class="fa fa-fw fa-building"></i> Desde: <?= h($guiaRemRemitente->direccion_envio) ?><br>
                        <i class="fa fa-fw fa-building"></i> Hasta: <?= h($guiaRemRemitente->direccion_llegada) ?>
                    </td>
                    <td class="actions">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <?php
                                echo $this->Form->postLink("<i class='fas fa-print fa-fw'></i>  Imprimir A4", ['action' => 'generarPdfA4', $guiaRemRemitente->id], ['class'=>'dropdown-item','escape'=>false]);
                                echo $this->Form->postLink("<i class='fas fa-trash fa-fw'></i>  Eliminar", ['action' => 'delete', $guiaRemRemitente->id], ['class'=>'dropdown-item','confirm' => __('Are you sure you want to delete # {0}?', $guiaRemRemitente->id),'escape'=>false]);
                                ?>
                            </div>
                        </div>


                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element("paginador");?>
</div>
