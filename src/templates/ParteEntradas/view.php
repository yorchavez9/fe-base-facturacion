<div class="table-responsive">
    <table class="table table-bordered ">
        <tr>
            <td style="width:150px">Almacen Ingreso</td>
            <td colspan="2"><?= ($parteEntrada->almacen) ? $parteEntrada->almacen->nombre : '' ?></td>
            <td>Direccion</td>
            <td colspan="2"><?= ($parteEntrada->almacen)?$parteEntrada->almacen->direccion : '' ?></td>
        </tr>
        <tr>
            <td>Parte Salida</td>
            <td style="width:100px"><?= ($parteEntrada->parte_salida) ? str_pad($parteEntrada->parte_salida->id,'8','0', STR_PAD_LEFT)  : 'NO' ?></td>
            <td style="max-width:200px">Fecha</td>
            <td><?= ($parteEntrada->fecha != null) ?$parteEntrada->fecha->format("d-m-Y") : '' ?></td>
            <td>Usuario</td>
            <td><?= ($parteEntrada->usuario)? $parteEntrada->usuario->nombre : '' ?></td>
        </tr>
        <tr>
            <td>Descripcion</td>
            <td colspan="5">
                <?= $parteEntrada->descripcion ?>
            </td>
        </tr>
    </table>
</div>
<div class="py-3">
    Opciones : 
    <a href="<?= $this->Url->build(['action'=> 'imprimir-pdf', $parteEntrada->id]) ?>" target="_blank" class="btn btn-primary btn-sm"> <i class="fas fa-print"></i> PDF</a>
</div>

<div>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center" style="width:70px"># </th>
                <th class="text-center" style="width:90px"> Código </th>
                <th> Descripción </th>
                <th class="text-center" style="width:80px"> Cantidad </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($parteEntrada->parte_entrada_registros as $registro):?>
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