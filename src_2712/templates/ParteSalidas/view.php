<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <td>Almacén Origen</td>
            <td>
                <div><?= ($parteSalida->almacen_salida)?$parteSalida->almacen_salida->nombre: '' ?></div>
            </td>
            <td>Almacén Destino</td>
            <td>
                <div><?= ($parteSalida->almacen_destino)?$parteSalida->almacen_destino->nombre: '' ?></div>
            </td>
            
        </tr>
        <tr>
            <td>Usuario</td>
            <td colspan="">
                <?= ($parteSalida->usuario) ? $parteSalida->usuario->nombre : '' ?>
            </td>
            <td>Fecha</td>
            <td><?= ($parteSalida->fecha_emision!= null) ? $parteSalida->fecha_emision->format("d-m-Y") :'' ?></td>
        </tr>
        <tr>
            <td>Descripción</td>
            <td colspan="3">
                <?= $parteSalida->descripcion ?>
            </td>
        </tr>
    </table>
</div>
<div class="py-3">
    Opciones : 
    <a href="<?= $this->Url->build(['action'=> 'imprimir-pdf', $parteSalida->id]) ?>" target="_blank" class="btn btn-primary btn-sm"> <i class="fas fa-print"></i> PDF</a>
</div>
<div>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center" style="width:70px"> # </th>
                <th class="text-center" style="width:90px"> Código </th>
                <th> Descripción </th>
                <th class="text-center" style="width:80px"> Cantidad </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($parteSalida->parte_salida_registros as $registro):?>
                <tr>
                    <td class="text-center"> <?= ($registro->item)? str_pad($registro->item->id,'8','0', STR_PAD_LEFT) : '' ?> </td>
                    <td class="text-center"> <?= ($registro->item)? $registro->item->codigo : '' ?> </td>
                    <td> 
                        <?=  ($registro->item)? $registro->item->nombre : '' ?> 
                        <?php
                            echo "<small>";
                            echo $this->Html->Link(" Ver Kardex <i class='fas fa-external-link-alt fa-fw'></i>", ['controller' => 'Items', 'action' => 'kardex', $registro->item->id],['escape' => false]);
                            echo "</small>";
                        ?>
                    </td>
                    <td class="text-center"> <?= $registro->cantidad ?> </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- <div>
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <span class="badge badge-info"> Detalle del Destino -  <?= str_replace('_', ' ', $parteSalida->destino_tipo) ?></span>
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body">
                   <?php if($parteSalida->destino_tipo == 'ALMACEN_INTERNO' && $parteSalida->almacen_salida):?> 
                        <div class="row">
                            <div class="col-2 font-weight-bold text-bold">Nombre</div>
                            <div class="col"><?= $parteSalida->almacen_salida->nombre ?></div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">Correo</div>
                            <div class="col"><?= $parteSalida->almacen_salida->correo ?></div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">Ubigeo</div>
                            <div class="col"><?= $parteSalida->almacen_salida->ubigeo_dpr ?></div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">Urbanización</div>
                            <div class="col"><?= $parteSalida->almacen_salida->urbanizacion ?></div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">Dirección</div>
                            <div class="col"> <?= $parteSalida->almacen_salida->direccion ?></div>
                        </div>
                   <?php elseif($parteSalida->destino_tipo == 'CENTRO_COSTO' && $parteSalida->cuenta):?> 
                        <div class="row">
                            <div class="col-2 font-weight-bold">
                                RUC
                            </div>
                            <div class="col">
                                <?= $parteSalida->cuenta->ruc ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">
                                Razón Social
                            </div>
                            <div class="col">
                                <div><?= $parteSalida->cuenta->razon_social ?></div>
                                <div><i class="fab fa-whatsapp fa-fw" style="font-size:15px"></i> <?= $parteSalida->cuenta->whatsapp ?></div>
                                <div><i class="fas fa-phone fa-fw"></i> <?= $parteSalida->cuenta->celular ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">
                                Sede
                            </div>
                            <div class="col">
                                <div><?= $parteSalida->sede_nombre ?></div>
                                <div><?= $parteSalida->sede_direccion ?></div>
                            </div>
                        </div>
                   <?php elseif($parteSalida->destino_tipo == 'USUARIO' && $parteSalida->usuario_salida):?> 
                        <div class="row">
                            <div class="col-2 font-weight-bold">
                                Nombre
                            </div>
                            <div class="col">
                                <?= $parteSalida->usuario_salida->nombre ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">
                                Celular
                            </div>
                            <div class="col">
                                <?= $parteSalida->usuario_salida->celular ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 font-weight-bold">
                                Correo
                            </div>
                            <div class="col">
                                <?= $parteSalida->usuario_salida->correo ?>
                            </div>
                        </div>
                   <?php endif; ?> 
                </div>
            </div>
        </div>
    </div>
</div> -->