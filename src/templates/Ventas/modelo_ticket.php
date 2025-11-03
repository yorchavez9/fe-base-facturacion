<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Ticket</title>
    <style type="text/css" >
        @page { margin: 0cm 0cm; }
        html {margin: 0px; font-family: 'Helvetica'; }
        body { margin: 10pt;text-align: center;}
        header *{
            font-size: 8pt;
            margin: 0pt;
            font-weight: normal;
            text-align: center; text-transform: uppercase;}
        header img { width: 100%;}
        header h1{ font-weight: bolder; }
        header h2{ font-weight: bolder; }
        header h3{ }
        header h5{ font-weight: bolder; padding: 10pt; }
        header h6{ font-size: 7pt; font-weight: bolder;}

        .der { text-align: right;}
        .codigo { vertical-align: top; font-weight: bolder; }
        .sup {vertical-align: top;text-align: left}
        .codigo_qr {text-align: center; padding: 10pt 0pt;}
        .codigo_qr img { width: 50%;}
        .info_pie { font-size: 8pt; }
        table { width: 100%; margin: auto; border-collapse: collapse;}
        table.items thead{ font-weight: bolder; font-size: 8pt; border-bottom: 1px gray solid;; }
        table.items tbody{ font-size: 7pt; border-bottom: 1px black solid;}
        table.items tbody tr td { padding: 0px;}
        table.datoscliente { font-size: 8pt;}
        table.resumen tr td { font-weight: normal; font-size: 8pt; padding: 0px; text-transform: uppercase;}
        table.resumen .bolder { font-weight: bolder;}

        .anulado{
            color:#AD2001;
            font-weight: 800;
        }
    </style>
</head>
<body>
<header>
    <img src="<?= $logo ?>"/>
    <h1><?= $razon_social ?></h1>
    <h2>RUC: <?= $ruc ?></h2>

    <h5>
        VENTA<br/>
        <?= $venta->documento_serie . "-" . $venta->documento_correlativo;?>
    </h5>

    <div style='font-size: 0.85; text-align: left'>
        <?= $doc_cabecera ?>
    </div>

</header>
<table class="datoscliente">
    <tr>
        <th style="width: 20%;" class="sup">CLIENTE</th>
        <th style="width: 5%;" class="sup">:</th>
        <td><?= $venta->cliente_razon_social ?></td>
    </tr>
    <tr>
        <th class="sup">
            <?= ($venta->cliente_doc_tipo == '6') ? 'RUC' : 'DNI'?>
        </th>
        <th class="sup">:</th>
        <td><?= $venta->cliente_doc_numero ?></td>
    </tr>
    <tr>
        <th class="sup">FECHA</th>
        <th class="sup">:</th>
        <td><?= $venta->fecha_venta->format("d/m/Y H:i:s") ?></td>
    </tr>
</table>
<br/>
<table class="items">
    <thead>
    <tr>
        <th>Código</th>
        <th>Descripción</th>
        <th>Cant.</th>
        <th style="width: 20%;">P. Unit</th>
        <th style="width: 20%;">Importe</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($venta->venta_registros as $reg): ?>
        <tr>
            <td class="codigo"><?= $reg->item_codigo ?></td>
            <td colspan="4" ><?= $reg->item_nombre ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><?= $this->Number->Format($reg->cantidad,['places' => 2]) ?></td>
            <td class="der"><?= $this->Number->Format($reg->precio_uventa,['places' => 2]) ?></td>
            <td class="der"><?= $this->Number->Format($reg->precio_total,['places' => 2]) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p><?= $venta->comentarios ?></p>

<br/>
<table class="resumen">
    <!--
    <tr>
        <td style="width: 70%">Op. Gravadas</td>
        <td><?= $venta->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($venta->op_gravadas, ['places' => 2])?></td>
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
        <td>SUB TOTAL</td>
        <td><?= $venta->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($venta->subtotal, ['places' => 2])?></td>
    </tr>
    <tr>
        <td>I.G.V.</td>
        <td><?= $venta->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($venta->igv_monto, ['places' => 2])?></td>
    </tr>

    <tr>
        <td class="bolder">TOTAL VENTA</td>
        <td class="bolder"><?= $venta->tipo_moneda?></td>
        <td class="bolder der"><?= $this->Number->Format($venta->total, ['places' => 2])?></td>
    </tr>
</table>

<div style="font-size:0.7em; text-align:left">
    <div>
        <h5>Adelanto y Saldo</h5>
        Adelanto de <?= ($venta->tipo_moneda == 'PEN') ? "S/": "$" ?> <?php echo $this->Number->Format($pagos[0]->monto, ['places' => 2]); ?><br/>
        Saldo de <?= ($venta->tipo_moneda == 'PEN') ? "S/": "$" ?> <?= $this->Number->Format($venta->total_deuda, ['places' => 2]); ?>
    </div>

    <br/>

    <?= $doc_pie_nv ?>
</div>

<br/>
<br/>
<br/>
</body>
</html>
