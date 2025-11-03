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
        .sup {vertical-align: top;}
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
    </style>
</head>
<body>
<header>
    <img src="<?= $logo ?>"/>
    <h1><?= $razon_social ?></h1>
    <h2>RUC: <?= $ruc ?></h2>
    <h3><?= $domicilio_fiscal ?></h3>
    <h5>
        Proforma - <?= str_pad($cotizacion->id, 8, '0', STR_PAD_LEFT); ?>
    </h5>
    <div style='font-size: 0.65em; text-align:left;'>
        <?= $doc_cab_coti ?>
    </div>
</header>
<br/>
<table class="datoscliente">
    <tr>
        <th style="width: 20%;" class="sup">CLIENTE</th>
        <th style="width: 5%;" class="sup">:</th>
        <td><?= $cotizacion->cliente_razon_social ?></td>
    </tr>
    <tr>
        <th class="sup">
            <?= ($cotizacion->cliente_doc_tipo == '6') ? 'RUC' : 'DNI'?>
        </th>
        <th class="sup">:</th>
        <td><?= $cotizacion->cliente_doc_numero ?></td>
    </tr>
    <tr>
        <th class="sup">FECHA</th>
        <th class="sup">:</th>
        <td><?= $cotizacion->fecha_cotizacion->format("d/m/Y H:i:s") ?></td>
    </tr>
    <tr>
        <th class="sup">VENCIMIENTO</th>
        <th class="sup">:</th>
        <td><?= $cotizacion->fecha_poranular->format("d/m/Y H:i:s") ?></td>
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
    <?php
    $registros = json_decode($items);
    foreach ($registros as $reg): ?>
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

<p><?= $cotizacion->comentarios ?></p>

<br/>
<table class="resumen">
    <!--
    <tr>
        <td style="width: 70%">Op. Gravadas</td>
        <td><?= $cotizacion->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($cotizacion->op_gravadas, ['places' => 2])?></td>
    </tr>
    <tr>
        <td>Op. Gratuitas</td>
        <td><?= $cotizacion->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($cotizacion->op_gratuitas, ['places' => 2])?></td>
    </tr>
    <tr>
        <td>Op. Exoneradas</td>
        <td><?= $cotizacion->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($cotizacion->op_exoneradas, ['places' => 2])?></td>
    </tr>
    <tr>
        <td>Op. Inafectas</td>
        <td><?= $cotizacion->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($cotizacion->op_inafectas, ['places' => 2])?></td>
    </tr>
    -->
    <tr>
        <td>SUB TOTAL</td>
        <td><?= $cotizacion->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($cotizacion->subtotal, ['places' => 2])?></td>
    </tr>
    <tr>
        <td>I.G.V.</td>
        <td><?= $cotizacion->tipo_moneda?></td>
        <td class="der"><?= $this->Number->Format($cotizacion->igv_monto, ['places' => 2])?></td>
    </tr>

    <tr>
        <td class="bolder">TOTAL PROFORMA</td>
        <td class="bolder"><?= $cotizacion->tipo_moneda?></td>
        <td class="bolder der"><?= $this->Number->Format($cotizacion->total, ['places' => 2])?></td>
    </tr>
</table>


<br/>
<div class="info_pie" style='font-size: 0.65em; text-align:left;'>
    <?= $doc_pie_coti ?>
</div>
<br/>
<br/>
<br/>
</body>
</html>
