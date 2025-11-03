<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Representación Impresa</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            margin: .8cm .8cm .5cm .8cm;
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%; }

        img {
            width: 100%; }

        .cabe .logo {
            width: 450px;
            padding-right: 1rem;
        }

        .factura {
            border: 3px solid #000;
            text-align: center;
            font-weight: bold;
            height: 107px;
            padding-top: 10px;
        }
        .factura p {
            margin: 10px;
            font-size: 1.5rem;
            vertical-align:top;
        }

        .producto_content{
            /*height: 350px;*/
            border-left: 1px solid black !important;
            border-right: 1px solid black !important;
            border-bottom: 1px solid black !important;
        }

        .producto {
            border-collapse: collapse;
        }

        .producto td{
            padding: .3rem .7rem;
            vertical-align: middle;
        }
        .producto th {
            padding: .3rem .7rem;
            vertical-align: middle;
            text-align: center;
        }
        .producto .cabe{
            background: black;
            color: white;
        }
        .producto .total {
            text-align: right; }
        .cliente{
            border-collapse: collapse;
        }
        .cliente td, cliente th{
            padding: .1rem .7rem;
            border: .1px solid gray;
            vertical-align: top;
        }
        .info{
            border-collapse: collapse;
        }
        .info td, info th{
            padding: .1rem 0;
            vertical-align: top;
        }
        .info__titulo{
            border-bottom: 1px solid gray;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .producto_pago{
            border: 1px solid transparent;
            border-collapse: collapse;
            border-top: 1px solid black;
        }
        .producto_pago th{
            padding: .3rem .7rem;
            border: 1px solid black !important;
        }
        .producto_pago td{
            padding: .3rem .7rem;
        }
        .direccion{
            font-weight: bold;
            font-size: 10px;
        }
        .codigo_qr { padding: 2em;}
        .codigo_qr img { max-width: 100px;}
        .der { text-align: right;}
    </style>
</head>

<?php if (isset($membrete_ruta) && $membrete_ruta != "") : ?>
    <body style="background: url(<?= $membrete_ruta ?>);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;" >
<?php else : ?>
    <body>
<?php endif; ?>

<table class="cabe">
    <tr>
        <td class="logo" style="width:25%">
            <img style='margin:top:2em; width:220px;' src="<?= $logo ?>"><br/>
        </td>
        <td>
            <div style='font-size:1.2em;'>
                <?= $doc_cab_coti ?>
            </div>
        </td>
        <td style="width:30%;">
            <div class="factura">
                <p style="font-size: 12pt;">R.U.C. <?= $ruc ?></p>
                <h1>PROFORMA</h1>
                <h3>
                    <?= str_pad($cotizacion->id, 8, '0', STR_PAD_LEFT); ?>
                </g3>
            </div>
        </td>
    </tr>
</table>
<br>
<table class="cliente">
    <tr>
        <td width="100"><b>Señor(ES) :</b></td>
        <td colspan="3"><?= $cotizacion->cliente_razon_social?></td>
    </tr>
    <tr>
        <td><b>Dirección :</b></td>
        <td colspan="3"><?= $cotizacion->cliente_domicilio_fiscal?></td>
    </tr>
    <tr>
        <td><b><?= ($cotizacion->cliente_doc_tipo == '6') ? 'RUC' : 'DNI'?> :</b></td>
        <td><?= $cotizacion->cliente_doc_numero?></td>
        <td><b>Moneda :</b></td>
        <td><?= $cotizacion->tipo_moneda?></td>
    </tr>
    <tr>
        <td><b>Fecha Proforma :</b></td>
        <td><?= $cotizacion->fecha_cotizacion->format("d/m/Y H:i:s")?></td>
        <td><b>Usuario :</b></td>
        <td><?= $usuario->nombre ?></td>
    </tr>
    <tr>
        <td><b>Validez Comercial :</b></td>
        <td colspan="3">
            <?= $cotizacion->fecha_poranular->format("d/m/Y") ?> ( <?= $cotizacion->dias_vencimiento ?> días )
        </td>
    </tr>
</table>
<br/>
<div class="producto_content">
    <table class="producto">
        <thead>
        <tr>
            <th class="cabe">CODIGO</th>

            <?php
            if(isset($allow_foto_coti) && $allow_foto_coti == 1):
            ?>
                <th class="cabe">FOTO</th>
            <?php
            endif;
            ?>

            <th class="cabe">DESCRIPCIÓN</th>
            <th class="cabe">UND.</th>
            <th class="cabe">CANT.</th>
            <th class="cabe">P. UNIT</th>
            <th class="cabe">VALOR VENTA</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $registros = json_decode($items);
        foreach ($registros as $reg): ?>
            <tr>
                <td class="codigo"><?= $reg->item_codigo ?></td>

                <?php
                if(isset($allow_foto_coti) && $allow_foto_coti == 1)
                {
                    echo "<td>";
                    if($reg->item->img_ruta != null && $reg->item->img_ruta !== ''){
                        echo "<img src='{$base}{$reg->item->img_ruta}' class='img-fluid' width='64px' height='64px' style='border-radius: 10px;max-width: 64px !Important;max-height: 64px !Important;' >";
                    }else{
                        echo "<img src='{$base}media/iconos/placeholder_items.png' class='img-fluid' width='64px' height='64px' style='border-radius: 10px;max-width: 64px !Important;max-height: 64px !Important;' >";
                    }
                    echo "</td>";
                }
                 ?>
                <td><?= $reg->item_nombre ?></td>
                <td><?= $reg->item_unidad ?></td>
                <td><?= $this->Number->Format($reg->cantidad,['places' => 2]) ?></td>
                <td class="der"><?= $this->Number->Format($reg->precio_uventa,['places' => 2]) ?></td>
                <td class="der"><?= $this->Number->Format($reg->precio_total,['places' => 2]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($cotizacion->comentarios != ''): ?>
    <br>
    <div style="border:1px gray solid; padding:10px">
        <b>Comentarios</b>
        <p><?= $cotizacion->comentarios ?></p>
    </div>
    <br>
<?php endif; ?>


<div style="width: 49%; float: left; text-align: left;">
    <?= $doc_pie_coti ?>
</div>

<div style="width: 49%; float: right; text-align: right;">
    <br/>
    <table style="width: 100%;">
    <!--
        <tr>
            <td style="width: 70%">Op. Gravadas</td>
            <td style="width: 10%;"><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td style="width: 20%" class="der"><?= $this->Number->Format($cotizacion->op_gravadas, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>Op. Gratuitas</td>
            <td><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td class="der"><?= $this->Number->Format($cotizacion->op_gratuitas, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>Op. Exoneradas</td>
            <td><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td class="der"><?= $this->Number->Format($cotizacion->op_exoneradas, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>Op. Inafectas</td>
            <td><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td class="der"><?= $this->Number->Format($cotizacion->op_inafectas, ['places' => 2])?></td>
        </tr>
    -->
        <tr>
            <td>I.G.V.</td>
            <td><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td class="der"><?= $this->Number->Format($cotizacion->igv_monto, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>SUB TOTAL</td>
            <td><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td class="der"><?= $this->Number->Format($cotizacion->subtotal, ['places' => 2])?></td>
        </tr>
        <tr>
            <td class="bolder">TOTAL PROFORMA</td>
            <td class="bolder"><?= $cotizacion->tipo_moneda == 'USD'?'$':'S/' ?></td>
            <td class="bolder der"><?= $this->Number->Format($cotizacion->total, ['places' => 2])?></td>
        </tr>
    </table>
</div>
<div style="clear: both;" ></div>

<br>


</body>
</html>
