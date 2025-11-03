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
            font-size: 2rem;
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
            height: 350px;
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

        .anulado{
            color:#AD2001;
            font-weight: 800;
        }
        .comment {
            font-style: italic;
            font-size: 10px;
        }
    </style>
</head>

<body>
<table class="cabe">
    <tr>
        <td width="25%">
            <img style='width:100%;' src="<?= $logo ?>"><br/>
        </td>
        <td>
            <div style='font-size:1.2em; padding: 10pt;'>
                <?= $doc_cabecera;  ?>
            </div>
        </td>
        <td width="35%">
            <div class="factura">
                <p style="font-size: 12pt;">R.U.C. <?= $ruc ?></p>
                <h2>VENTA</H2>
                <h2>
                    <?php echo $venta->documento_serie . "-" . $venta->documento_correlativo; ?>
                </h2>
            </div>
        </td>
    </tr>
</table>
<br>
<table class="cliente">
    <tr>
        <td width="100"><b>Señor(ES) :</b></td>
        <td colspan="3"><?= $venta->cliente_razon_social?></td>
    </tr>
    <tr>
        <td><b>Dirección :</b></td>
        <td colspan="3"><?= $venta->cliente_domicilio_fiscal?></td>
    </tr>
    <tr>
        <td><b><?= ($venta->cliente_doc_tipo == '6') ? 'RUC' : 'DNI'?> :</b></td>
        <td><?= $venta->cliente_doc_numero?></td>
        <td><b>Moneda :</b></td>
        <td><?= $venta->tipo_moneda?></td>
    </tr>
    <tr>
        <td><b>Fecha y Hora OS :</b></td>
        <td><?= $venta->fecha_venta->format("d/m/Y H:i:s")?></td>
        <td><b>Usuario </b> </td>
        <td><?=$usuario->nombre?></td>
    </tr>
</table>
<br/>
<div class="producto_content">
    <table class="producto">
        <thead>
        <tr>
            <th class="cabe">CODIGO</th>
            <th class="cabe">DESCRIPCIÓN</th>
            <th class="cabe">UND.</th>
            <th class="cabe">CANT.</th>
            <th class="cabe">P. UNIT</th>
            <th class="cabe">VALOR VENTA</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($venta->venta_registros as $reg): ?>
            <tr>
                <td class="codigo"><?= $reg->item_codigo ?></td>
                <td>
                    <?= $reg->item_nombre ?>
                    <div class="comment">
                        <?= $reg->item_comentario ?>
                    </div>
                </td>
                <td><?= $reg->item_unidad ?></td>
                <td><?= $this->Number->Format($reg->cantidad,['places' => 2]) ?></td>
                <td class="der"><?= $this->Number->Format($reg->precio_uventa,['places' => 2]) ?></td>
                <td class="der"><?= $this->Number->Format($reg->precio_total,['places' => 2]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<br/>

<?php if ($venta->comentarios != ''): ?>
    <br>
    <div style="border:1px gray solid; padding:10px">
        <b>Comentarios</b>
        <p><?= $venta->comentarios ?></p>
    </div>
    <br>
<?php endif; ?>

<div style="width: 30%; float: left;">
    <?= $doc_pie_nv ?>
</div>
<div style="width: 68%; float: right; text-align: right;">
    <br/>
    <table style="width: 100%;">
    <!--
        <tr>
            <td style="width: 70%">Op. Gravadas</td>
            <td style="width: 10%;"><?= $venta->tipo_moneda?></td>
            <td style="width: 20%" class="der"><?= $this->Number->Format($venta->op_gravadas, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>Op. Gratuitas</td>
            <td><?= $venta->tipo_moneda?></td>
            <td class="der"><?= $this->Number->Format($venta->op_gratuitas, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>Op. Exoneradas</td>
            <td><?= $venta->tipo_moneda?></td>
            <td class="der"><?= $this->Number->Format($venta->op_exoneradas, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>Op. Inafectas</td>
            <td><?= $venta->tipo_moneda?></td>
            <td class="der"><?= $this->Number->Format($venta->op_inafectas, ['places' => 2])?></td>
        </tr>
    -->
        <tr>
            <td>I.G.V.</td>
            <td><?= $venta->tipo_moneda?></td>
            <td class="der"><?= $this->Number->Format($venta->igv_monto, ['places' => 2])?></td>
        </tr>
        <tr>
            <td>SUB TOTAL</td>
            <td><?= $venta->tipo_moneda?></td>
            <td class="der"><?= $this->Number->Format($venta->subtotal, ['places' => 2])?></td>
        </tr>
        <tr>
            <td class="bolder">TOTAL VENTA</td>
            <td class="bolder"><?= $venta->tipo_moneda?></td>
            <td class="bolder der"><?= $this->Number->Format($venta->total, ['places' => 2])?></td>
        </tr>
    </table>
    <br/>
    <div>
        <h5>Adelanto y Saldo</h5>
        Adelanto de <?= ($venta->tipo_moneda == 'PEN') ? "S/": "$" ?> <?php echo $this->Number->Format($pagos[0]->monto, ['places' => 2]); ?><br/>
        Saldo de <?= ($venta->tipo_moneda == 'PEN') ? "S/": "$" ?> <?= $this->Number->Format($venta->total_deuda, ['places' => 2]); ?>
    </div>
</div>


</body>
</html>
