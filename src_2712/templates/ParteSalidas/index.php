<div class="parteSalidas index content">
    <?= $this->Form->create(null,[ 'method' => 'get', 'id' => 'form_filtro']); ?>
        <div class="row ">
            <div class="col-sm-12 col-md-4 form-group">
                <label for="fec_inicio">Fec. Inicio</label>
                <input type="text" class="form-control form-control-sm" id="fec_inicio" name="fec_inicio" value="<?=$opt_fec_inicio?>">
            </div>
            <div class="col-sm-12 col-md-4 form-group">
                <label for="fec_fin">Fec. Fin</label>
                <input type="text" class="form-control form-control-sm" id="fec_fin" name="fec_fin"  value="<?=$opt_fec_fin?>">
            </div>
            <div class="col-sm-12 col-md-4 form-group d-flex align-items-end justify-content-center">
                <div>
                    <button class="mt-2 btn btn-primary btn-sm" id="buscar"> <i class="fas fa-search"></i> </button>
                    <button class="mt-2 btn btn-danger btn-sm" type="button" onclick="ParteSalida.limpiar()"> <i class="fas fa-eraser"></i> </button>
                </div>
            </div>
        </div>
    <?= $this->Form->end(); ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th style="max-width: 100px;min-width: 100px" ><?= $this->Paginator->sort('id',['label' => 'Código']) ?></th>
                    <th><?= $this->Paginator->sort('fecha_emision') ?></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parteSalidas as $parteSalida): ?>
                <tr>
                    <td style="max-width: 100px;min-width: 100px" >
                    <?= str_pad($parteSalida->id, 8,0,STR_PAD_LEFT) ?>
                        <div>
                            <span class="badge badge-dark">descripcion</span>
                        </div>
                        <div>
                            <?= $parteSalida->descripcion == '' ? 'Sin Descripcion' : $parteSalida->descripcion ?>
                        </div>
                    </td>
                    <td><?= ($parteSalida->fecha_emision != null && $parteSalida->fecha_emision != '') ?$parteSalida->fecha_emision->format("d-m-Y") : ''  ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"  style="z-index:500">
                                <?php echo $this->Html->link("<i class='fas fa-eye fa-fw'></i> Detalles", ['action' => 'view', $parteSalida->id], ['class' => 'dropdown-item-text','escape' => false]); ?>
                                    <a class="dropdown-item-text" href="#" onclick="modalExportar.Init('<?=$parteSalida->id?>', 'parte-salidas')"><i class='fas fa-print fa-fw'></i> Exportar </a>
                                    <?= $this->Form->postLink("<i class='fas fa-trash-alt fa-fw'></i> Eliminar", ['action' => 'delete', $parteSalida->id], ['confirm' => __('Seguro que desea eliminar el parte de salida {0}?', $parteSalida->id) , 'class' => 'dropdown-item-text','escape' => false] ) ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $this->Element('paginador') ?>
    </div>
</div>
<?= $this->Element('modal_exportar') ?>
<?php
    echo $this->Html->scriptBlock(" var sede_id = undefined" );
    if(isset($opt_sede) && $opt_sede != ''){
        echo $this->Html->scriptBlock(" sede_id = " . $opt_sede );
    }
?>




