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
            font-size: 12px;
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

        .documento {
            border: 3px solid #000;
            text-align: center;
            font-weight: bold;
            height: 107px;
            padding-top: 8px;
        }
        .documento p {
            margin: 10px;
            font-size: 1.2rem;
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
            border-top: none;
            border-bottom: none;
        }
        .producto th {
            padding: .3rem .7rem;
            vertical-align: middle;
            text-align: center;
        }
        .producto .cabe{
            text-align: left;
        }
        .producto .total {
            text-align: right; }

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
            border: 1px solid black;
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
        .codigo_qr { padding: 2em 0 2em 0;}
        .codigo_qr img { max-width: 250px;}
        .der { text-align: right;}
        .table {border-collapse: collapse; border-spacing: 0px;}

        .table td{ border: 1px gray solid; padding: 5px; vertical-align: top; }
        .table th{ border: 1px gray solid; background: black; color:white; padding: 5px }
        .table_info td{
            padding: 5px;
        }
        .text-bold{
            font-weight: bold;
        }

        footer {
            position: fixed; 
            bottom: 0px; 
            left: 15px; 
            right: 0px;
            height: 150px; 
        }
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
        <td width="25%">
            <div>
                <img style='width:100%;' src="<?= $logo ?>"><br/>
            </div>
        </td>
        <td>
            <div style='font-size:15px; padding-top: 5px;'>
                <?= $razon_social ?>
            </div>
            <div style="padding-top:20px">
                Fecha de Emisión: <?= $guia->fecha_emision->format("d/m/Y");?>
            </div>
        </td>
        <td width="35%">
            <div class="documento">
                <p style="font-size: 12pt;">RUC N°<?= $ruc ?></p>
                <h3>
                    <?php
                    echo "Guía de Remisión Electrónica<br/>";
                    echo "REMITENTE<br/>";
                    echo $guia->serie . "-" . $guia->correlativo;
                    ?>
                </h3>
            </div>
        </td>
    </tr>
</table>
<br>
<table class="table_info" border=""  style="margin-bottom:10px;border-collapse:collapse">
    <tr>
        <td>
            <div>
                <label class="text-bold">
                    Fecha de inicio de traslado: 
                </label>
                <?= $guia->fecha_traslado->format("d/m/Y");?>
            </div>
        </td>
        <td>
            <div>
                <label class="text-bold">
                    Punto de Partida:
                </label>
                <?= $guia->partida_direccion ?> - <?= $guia->partida_ubigeo_full ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <label class="text-bold">
                    Motivo del Traslado: 
                </label>
                <?= $traslado_motivos[$guia->motivo_traslado]?>
            </div>
        </td>
        <td>
            <div>
                <label class="text-bold">
                    Punto de Llegada:
                </label>
                <?= $guia->llegada_direccion ?> - <?= $guia->llegada_ubigeo_full ?>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <label class="text-bold">
                Datos del Destinatario: 
            </label>
            <?= $guia->cliente_razon_social ?> - REGISTRO ÚNICO DE CONTRIBUYENTES N° <?= $guia->cliente_num_doc ?>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <div>
                <label class="text-bold">
                    Documentos Relacionados:
                </label>
                Factura Nº: <?= $guia->doc_adicional_serie ?> - <?= $guia->doc_adicional_correlativo ?>  RUC Nº: <?= $guia->doc_adicional_emisor ?>
            </div>
        </td>
    </tr>
</table>
<div style="font-size:12px;padding-bottom:10px" class="text-bold">
    Bienes por Transportar:
</div>
<div class="">
    <table class="producto" border="1">
        <thead>
            <tr>
                <th class="cabe" style="width:18%">CODIGO</th>
                <th class="cabe">DESCRIPCIÓN</th>
                <th class="cabe" style="width:10%">UNIDAD</th>
                <th class="cabe" style="width:10%">CANTIDAD</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $items = json_decode($guia->registros, true);
            foreach ($items as $reg): ?>
            <tr>
                <td class="codigo"><?= $reg['item_codigo'] ?></td>
                <td><?= $reg['item_nombre'] ?></td>
                <td><?= $reg['item_unidad'] ?></td>
                <td style="text-align: right"><?= $this->Number->format($reg['cantidad'],['places' => 2]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div style="padding-bottom: 10px;">
    <div style="padding-top:10px">
        <label class="text-bold">Unidad de Medida del Peso Bruto:</label> <?=$guia->peso_unidad?>
    </div>
    <div>
        <label class="text-bold">Peso Bruto total de la carga:</label> <?=$guia->peso_total //formato decimal ?> 
    </div>
</div>

<div style="padding-bottom: 10px;">
    <label class="text-bold">Datos del traslado</label>

    <div>
        <label class="text-bold">Modalidad de Traslado:</label> <?= ($guia->modo_traslado == '01') ? "Transporte Público" : "Transporte Privado"?>
    </div>
    <!-- <div>
        Indicador de transbordo programado: 
    </div> -->
    <div>
        <label class="text-bold">Indicador de traslado en vehículos de categoría M1 o L:</label> <?= ($guia->transp_vehiculo_m1_l1 == '1') ? "SI" : "NO"?>
    </div>
</div>

<?php if  ($guia->modo_traslado == '01'): ?>
<div>
    <div>
       <label class="text-bold">Tranporte Doc:</label> <?= $guia->transp_num_doc ?>
    </div>
    <div>
       <label class="text-bold">Tranporte Razon Social:</label> <?= $guia->transp_razon_social ?>
    </div>
    <div>
        <label class="text-bold">Tranporte Código MTC:</label> <?= $guia->transp_codigo_mtc ?>
    </div>
</div>
<?php else: ?>
<div>
    <div>
        <label class="text-bold">Datos de los vehículos</label>
        <div style="padding-left:20px">
            <?php
                $vehiculos = json_decode($guia->transp_privado_vehiculos, true);
                foreach ($vehiculos as $reg): ?>
                <li>
                    <div>
                        <label style="width: 150px" >
                            <?= $reg['tipo'] == 'principal' ? 'Principal' : 'Secundario' ?>
                        </label>
                        -
                        <span>
                            Número de Placa: <?= $reg['placa'] ?>
                        </span>
                    </div>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
    <div>
        <label class="text-bold">Datos de los conductores</label>
        <div style="padding-left:20px">
            <?php
                $conductores = json_decode($guia->transp_privado_conductores, true);
                foreach ($conductores as $reg): ?>
                <li>
                    <div>
                        <div>
                            <?= $reg['nombres'] ?> <?= $reg['apellidos'] ?> - DOCUMENTO NACIONAL DE IDENTIDAD N° <?= $reg['documento'] ?>
                        </div>
                        <div>
                            <label class="text-bold">Número de lincencia de conducir:</label>  <?= $reg['licencia'] ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<br>
<div>
    <label class="text-bold">Observaciones:</label>
    <div>
        <?= $guia->observaciones?>
    </div>
</div>



<div style="clear: both;" ></div>
<!-- <?=$doc_pie?> -->

<footer>
    <div style="width: 150px; float: left;">
        <div class="">
            <img style='width:100%;'  src="<?= $qr_sunat ?>"/>
        </div>
    </div>
</footer>

</body>
</html>
