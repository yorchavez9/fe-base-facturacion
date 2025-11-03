<?php
/**
* @author Dylan Ale
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen</title>
    <style>
        @page {
                margin: 1cm 1cm; 
            }

        body {
            
            font-family: sans-serif;
            font-size: 10px
        }
        .div-header{
            text-align: center;
        }
        .section-body{
        }
       
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        
        .text-bold{
            font-weight: bold;
        }
        .text-upper{
            text-transform: uppercase;
        }
        .row{
            display: block;
        }
        .col{
            display: inline-block;
            vertical-align:top;
        }
        .div-col{
            display: inline-block;
            width: auto
        }
        footer {
            position: fixed;
            bottom: 2cm;
            left: 0cm;
            right: 0cm;
            text-align: center;
            z-index: -1;
            padding-left: 1.5cm ;
            padding-right: 1.5cm ;
        }
            
    </style>
</head>

<body>
    <header>
        <div class="div-header">
            <h2>
               Resumen ventas<br>
            </h2>
        </div>
    </header>
    <section class="section-body">
        <div>
            <div style="width:100%;">
            <?php if($respuesta->data == 'check-message'): ?>
                <div class="col" style="width:100%;vertical-align:middle"> 
                    <?php if(is_string($respuesta->message)): ?>
                        <?= $respuesta->message ?>
                    <?php else: ?>
                        <div class="row" style="min-height:10px;margin-top:5px;">
                            <?php foreach($respuesta->message as $key => $message): ?>
                                <div class="col" style="width:20%;vertical-align:middle"> <?= $key?> </div>
                                <div class="col" style="width:78%;vertical-align:middle"> <?= $message?> </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row" style="min-height:10px;margin-top:5px;">
                    <div class="col" style="width:20%;vertical-align:middle"> <?= $respuesta->message ?> </div>
                    <div class="col" style="width:780%;vertical-align:middle"> <?= $respuesta->data ?> </div>
                </div>
            <?php endif; ?>
            
            <?php if(isset($resumen)) :?>
                <div class="row" style="min-height:10px;margin-top:5px;">
                    <div class="col text-bold" style="width:80%;vertical-align:middle;font-size:15px"> 
                        <?= $resumen->emisor_ruc ?>  <?= $resumen->emisor_razon_social ?>
                    </div>
                </div>
                <div class="row" style="min-height:10px;margin-top:5px;background-color:">
                    <div class="col" style="width:10%;vertical-align:middle">
                        <span class="text-bold">Correlativo: </span><br>
                        <?= $resumen->correlativo ?>
                    </div>
                    <div class="col" style="width:25%;vertical-align:middle">
                        <span class="text-bold">Nombre Unico: </span><br>
                        <?= $resumen->nombre_unico ?>
                    </div>
                    <div class="col" style="width:15%;vertical-align:middle">
                        <span class="text-bold">Ticket: </span><br>
                        <?= $resumen->ticket ?>
                    </div>
                    <div class="col" style="width:33%;vertical-align:middle;word-break: break-all;">
                        <span class="text-bold">XML y .ZIP </span><br>
                        <?= $resumen->nombre_archivo_xml ?><br>
                        <?= $resumen->nombre_archivo_cdr ?>
                    </div>
                    <div class="col" style="width:15%;background-color:"> 
                        <div class="text-bold">Fecha de generación:</div>
                        <div> <?= ($resumen->fecha_generacion != '' && $resumen->fecha_generacion != null) ?$resumen->fecha_generacion->format("d-m-Y") : '' ?> </div>
                    </div>
                </div>
                <div style="width:100%;vertical-align:middle">
                    <span class="text-bold">Codigo Error: </span>
                    <?= $resumen->sunat_err_code ?>
                </div>
                <div style="width:100%;vertical-align:middle">
                    <span class="text-bold">Mensaje Error: </span>
                    <?= $resumen->sunat_err_message ?>
                </div>
                <div style="width:100%;vertical-align:middle">
                    <span class="text-bold">Codigo CDR: </span>
                    <?= $resumen->sunat_cdr_code ?>
                </div>
                <div style="width:100%;vertical-align:middle">
                    <span class="text-bold">Notas CRD: </span>
                    <?= $resumen->sunat_cdr_notes ?>
                </div>
                <div style="width:100%;vertical-align:middle">
                    <span class="text-bold">Descripcion CRD: </span>
                    <?= $resumen->sunat_cdr_description ?>
                </div>
                <div style="width:100%;vertical-align:middle">
                    <span class="text-bold">Mensaje Estado: </span>
                    <?= $resumen->estado_mensaje ?>
                </div>
                <?php foreach($resumen->sunat_fe_facturas as $factura) :?>
                    <div class="text-center text-bold" style="width:100%;margin-top:5px;border-top:1px solid black;border-bottom:1px solid black;letter-spacing:5px;">
                        Ventas - Boletas Afectadas
                    </div>
                    <?php if($factura->venta) :?>
                        <div class="row" style=";background-color:;">
                            <div class="col" style="width:15%;background-color:;"> 
                                <div class="text-bold">Estado: </div>
                                <?= $factura->venta->estado ?>
                            </div>
                            <div class="col" style="width:15%;background-color:"> 
                                <div class="text-bold">Estado SUNAT: </div>
                                <div> <?= $factura->venta->estado_sunat ?></div>
                            </div>
                            <div class="col" style="width:20%;background-color:"> 
                                <div class="text-bold">Serie / Correlativo: </div>
                                <div>
                                    <?= $factura->venta->documento_serie ?> - 
                                    <?= $factura->venta->documento_correlativo ?>
                                </div>
                            </div>
                            <div class="col" style="width:38%;background-color:"> 
                                <div class="text-bold">Cliente: </div>
                                <div>
                                    <?= $factura->venta->cliente_doc_numero ?>
                                </div>
                                <div>
                                    <?= $factura->venta->cliente_razon_social ?>
                                </div>
                            </div>
                            <div class="col" style="width:10%;text-align:right;background-color:"> 
                                <div class="text-bold">Total: </div>
                                <div><?= number_format( $factura->venta->total, 2)  ?> </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php /*
                    <div class="text-center" style="width:100%;padding-top:5px;border-top:1px dotted black;letter-spacing:5px">
                        Detalle sunat de esta Venta
                    </div>
                    <div style="width:100%;padding-bottom:15px;">     
                        <div class="<?= ($factura->sunat_cdr_notes != null && $factura->sunat_cdr_notes != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CDR Notes:</span> <?= $factura->sunat_cdr_notes ?></div>
                        <div class="<?= ($factura->sunat_cdr_description != null && $venta->sunat_cdr_description != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CDR Descripción:</span> <?= $factura->sunat_cdr_description ?></div>
                        <div class="<?= ($factura->sunat_cdr_code != null && $factura->sunat_cdr_code != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CDR Código:</span> <?= $factura->sunat_cdr_code ?></div>
                        <div class="<?= ($factura->sunat_err_code != null && $factura->sunat_err_code != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CODIGO Error:</span> <?= $factura->sunat_err_code ?></div>
                        <div class="<?= ($factura->sunat_err_message != null && $factura->sunat_err_message != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">NOTAS Error:</span> <?= $factura->sunat_err_message ?></div>
                    </div>
                    */?>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>