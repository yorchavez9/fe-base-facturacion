
<form>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group mb-0">
                <label class="control-label">Rango de Fechas:</label>
                <div class="form-group row mb-0">
                    <div class="col-md-6 mb-3">
                        <input class="form-control form-control-sm text-center fecha" name="fecha_ini" value="<?= $fini ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input class="form-control form-control-sm text-center fecha" name="fecha_fin" value="<?= $ffin ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-5">
            <div class="form-group">
                <label>Cliente:</label>
                <input type="text" name="opt_nombres" value="<?=$opt_nombres?>" class="form-control form-control-sm" placeholder="- Todos -">
            </div>
        </div>
        <div class="col-sm-12 col-md-2 d-flex align-items-end mb-3">
            <button type="submit" class="btn btn-sm btn-primary mx-1">
                Consultar
            </button>
        </div>
    </div>
</form>

<div class="cotizaciones index content">
    <div class="table-responsive" style="min-height: 45vh;">
        <table class="table table-hover table-striped table-sm">
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', 'Nro') ?></th>
                <th><?= $this->Paginator->sort('usuario_id') ?></th>
                <th><?= $this->Paginator->sort('cuenta_id', 'Cliente') ?></th>
                <th style="width: 120px;" ><?= $this->Paginator->sort('subtotal') ?></th>
                <th style="width: 120px;" ><?= $this->Paginator->sort('total') ?></th>
                <th><?= $this->Paginator->sort('fecha_cotizacion') ?></th>
                <th><?= $this->Paginator->sort('fecha_poranular', ['label' => 'Fec. Vencimiento']) ?></th>
                <th class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cotizaciones as $cotizacione): ?>
                <tr>
                    <td><?= str_pad("".$cotizacione->id, 8, "0", 0) ?></td>
                    <td><?= $cotizacione->usuario ? $cotizacione->usuario->nombre : '---' ?></td>
                    <td><?= $cotizacione->cliente_razon_social ?? "" ?></td>
                    <td>S/ <?= $this->Number->precision($cotizacione->subtotal, 2) ?></td>
                    <td>S/ <?= $this->Number->precision($cotizacione->total, 2) ?></td>
                    <td><?= h($cotizacione->fecha_cotizacion ? $cotizacione->fecha_cotizacion->format('d-m-Y') : "") ?></td>
                    <td><?= h($cotizacione->fecha_poranular ? $cotizacione->fecha_poranular->format('d-m-Y') : "") ?></td>
                    <td class="actions">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a style="color: black;" class="dropdown-item" href="#" onclick='ModalDescargarEnviar.AbrirDesdeLink(event, this)' data-cpe-id="<?=$cotizacione->id?>" data-cpe-tipo="COTIZACION" >
                                <i class="fas fa-download fa-fw"></i> Descargar y Enviar
                                <i class="fab fa-whatsapp fa-fw"></i> ó <i class="fas fa-envelope fa-fw"></i>
                            </a>

                            <?= $this->Html->link('<i class="fa fa-fw fa-print"></i> Imprimir A4', "javascript:void(0)", ['class' =>'dropdown-item', 'escape' => false, 'onclick' => "indexCotizaciones.imprimirCotizacion({$cotizacione->id},'a4')"]) ?>
                            <?= $this->Html->link('<i class="fa fa-fw fa-print"></i> Imprimir Ticket', "javascript:void(0)", ['class' =>'dropdown-item', 'escape' => false, 'onclick' => "indexCotizaciones.imprimirCotizacion({$cotizacione->id},'ticket')"]) ?>

                            <?php if ($whatsapp_active == "1") { ?>
<!--                                <a class="dropdown-item" href='javascript:FormCotizacionesWhatsapp.NuevoMensaje(<?//=$cotizacione->id?>,<?//=$cotizacione->cliente_id?>,"<?//=$cotizacione->cliente_persona_tipo?>","<?//= $cotizacione->cliente_razon_social?>")'> <i class="fab fa-whatsapp fa-fw"></i> Whatsapp</a>-->
                            <?php } ?>

                            <?= $this->Html->link('<i class="fa fa-fw fa-edit"></i> Editar', ['action' => 'nueva', '?' => ['coti_id' => $cotizacione->id]], ['class' =>'dropdown-item', 'escape' => false]) ?>
                            <?php

                                    echo $this->Html->link('<i class="fa fa-fw fa-arrow-right"></i> Generar Venta', ['controller' => 'Ventas', 'action' => 'nuevaVenta', '?' => ['coti_id' => $cotizacione->id]], ['class' =>'dropdown-item', 'escape' => false]) ;

                                    echo $this->Form->postLink('<i class="fa fa-fw fa-trash"></i> Eliminar', ['action' => 'delete', $cotizacione->id], ['confirm' => __('Está seguro que desea eliminar el registro # {0}?', $cotizacione->id),'class' =>'dropdown-item', 'escape' => false]) ;
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element("paginador");?>
</div>
<?php
    echo $this->Element('form_mensaje_cotizaciones_whatsapp');
    echo $this->Element('modal_descargar_enviar');
