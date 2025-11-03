<div class="parteEntradas index content">
    <?= $this->Form->create(null,[ 'method' => 'get', 'id' => 'form_filtro']); ?>
        <div class="row ">
            <div class="col-sm-12 col-md-6 col-lg-3 form-group">
                <label for="fec_inicio">Fec. Inicio</label>
                <input type="text" class="form-control form-control-sm" id="fec_inicio" name="fec_inicio" value="<?=$opt_fec_inicio?>">
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 form-group">
                <label for="fec_fin">Fec. Fin</label>
                <input type="text" class="form-control form-control-sm" id="fec_fin" name="fec_fin"  value="<?=$opt_fec_fin?>">
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2 form-group text-center d-flex align-items-end justify-content-center">
                <div>
                    <button class="mt-2 btn btn-primary btn-sm" id="buscar"> <i class="fas fa-search"></i> </button>
                    <button class="mt-2 btn btn-danger btn-sm" type="button" onclick="ParteEntrada.limpiar()"> <i class="fas fa-eraser"></i> </button>
                </div>
            </div>
        </div>
    <?= $this->Form->end(); ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id',['label' => 'Código']) ?></th>
                    <th><?= $this->Paginator->sort('usuario_id', ['label' => 'Registro']) ?></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parteEntradas as $parteEntrada): ?>
                <tr>
                    <td>
                    <?= str_pad($parteEntrada->id, 8,0,STR_PAD_LEFT) ?><br>
                    <?= $parteEntrada->descripcion ?>
                    </td>
                    <td>
                        <div>
                            <span class="badge badge-primary">Usuario</span>
                            <?= ($parteEntrada->usuario)? $parteEntrada->usuario->nombre : ''?>
                        </div>
                        <div>
                            <?= ($parteEntrada->fecha != null && $parteEntrada->fecha != '') ?$parteEntrada->fecha->format("d-m-Y") : ''  ?>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"  style="z-index:1">
                                <?php echo $this->Html->link("<i class='fas fa-eye fa-fw'></i> Detalles", ['action' => 'view', $parteEntrada->id], ['class' => 'dropdown-item-text','escape' => false]); ?>
                                <a class="dropdown-item-text" href="#" onclick="modalExportar.Init('<?=$parteEntrada->id?>', 'parte-entradas')"><i class='fas fa-print fa-fw'></i> Exportar </a>
                                <?= $this->Form->postLink("<i class='fas fa-trash-alt fa-fw'></i> Eliminar", ['action' => 'delete', $parteEntrada->id], ['confirm' => __('Seguro que desea eliminar el parte de entrada {0}?', $parteEntrada->id) , 'class' => 'dropdown-item-text','escape' => false] ) ?>
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




