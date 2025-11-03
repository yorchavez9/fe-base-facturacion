<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Venta - <?= $venta->documento_serie ?> <?= $venta->documento_correlativo ?></title>
    <style>
        @page {
                margin: 1cm 1cm; 
            }

        body {
            
            font-family: sans-serif;
            font-size: 12px
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
            width: auto;
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
        .text-sub{
        }
            
    </style>
</head>

<body>
    <header>
        <div class="div-header">
            <h2>
                Error Venta - <?= $venta->documento_serie ?> <?= $venta->documento_correlativo ?>
            </h2>
        </div>
    </header>
    <section class="section-body">
        <div >
            <div style="width:100%;">
                <div class="row" style="padding-top:5px;border-bottom:1px solid black;border-top:1px solid black;background-color:">                    
                    <div class="col" style="width:15%"> 
                        <span class="text-bold">ESTADO </span>
                    </div>
                    <div class="col" style="width:25%;"> 
                        <span class="text-bold">SUNAT ESTADO </span>
                    </div>
                    <div class="col" style="width:20%;"> 
                        <span class="text-bold">DOCUMENTO</span>
                    </div>
                    <div class="col" style="width:20%;"> 
                        <span class="text-bold">CLIENTE </span>
                    </div>
                    <div class="col" style="width:15%;"> 
                        <span class="text-bold">MONTO </span>
                    </div>
                </div>
                <div class="row" style="min-height:10px;background-color:">
                    <div class="col" style="width:15%;background-color:"> 
                        <div><?= $venta->estado ?></div>
                    </div>
                    <div class="col" style="width:25%;background-color:"> 
                        <div> <?= $venta->estado_sunat ?></div>
                    </div>
                    <div class="col" style="width:20%;background-color:"> 
                        <div>
                            <?= $venta->documento_tipo ?>
                        </div>
                        <div>
                            <?= $venta->documento_serie ?>
                        </div>
                        <div>
                            <?= $venta->documento_correlativo ?>
                        </div>
                    </div>
                    <div class="col" style="width:20%;background-color:"> 
                        <div>
                            <?= $venta->cliente_doc_numero ?>
                        </div>
                        <div>
                            <?= $venta->cliente_razon_social ?>
                        </div>
                    </div>
                    <div class="col" style="width:15%;"> 
                        <div><?= $venta->total ?> </div>
                    </div>
                </div>


                <div class="row" style="padding-top:5px;margin-bottom:5px;border-bottom:1px solid black;border-top:1px solid black">                    
                    <div class="col" style="width:15%"> 
                        <span class="text-bold">CODIGO</span>
                    </div>
                    <div class="col" style="width:50%;"> 
                        <span class="text-bold">NOMBRE </span>
                    </div>
                    <div class="col" style="width:10%;"> 
                        <span class="text-bold">UNIDAD</span>
                    </div>
                    <div class="col text-center" style="width:10%;"> 
                        <span class="text-bold">CANTIDAD </span>
                    </div>
                    <div class="col" style="width:12%;text-align:right"> 
                        <span class="text-bold">TOTAL </span>
                    </div>
                </div>
                <?php foreach($venta->venta_registros as $reg) :?>
                    <div class="row" style="min-height:10px;padding-top:5px;">
                        <div class="col" style="width:15%"> 
                            <div><?= $reg->item_codigo ?></div>
                        </div>
                        <div class="col" style="width:50%;"> 
                            <div><?= $reg->item_nombre ?></div>
                        </div>
                        <div class="col" style="width:10%;"> 
                            <div><?= $reg->item_unidad ?></div>
                        </div>
                        <div class="col text-center" style="width:10%;"> 
                            <div><?= $reg->cantidad ?></div>
                        </div>
                        <div class="col" style="width:12%;text-align:right"> 
                            <div><?= $reg->precio_total ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row" style="padding-top:5px;margin-bottom:5px;border-bottom:1px solid black;border-top:1px solid black">
                    <div class="col" style="width:15%;"> 
                        <span class="text-bold">ESTADO </span>
                    </div>
                    <div class="col" style="width:20%;"> 
                        <span class="text-bold">DETALLE</span>
                    </div>
                    <div class="col" style="width:45%;"> 
                        <span class="text-bold">CLIENTE </span>
                    </div>
                    <div class="col" style="width:18%;text-align:right"> 
                        <span class="text-bold">TOTAL</span>
                    </div>
                </div>
                <?php if($venta->sunat_fe_factura) :?>
                    <div class="row" style="min-height:10px;padding-top:5px;">                        
                        <div class="col" style="width:15%;background-color:"> 
                            <div><?= $venta->sunat_fe_factura->sunat_estado ?></div>
                        </div>
                        <div class="col" style="width:20%;background-color:"> 
                            <div><?= $venta->sunat_fe_factura->serie ?></div>
                            <div><?= $venta->sunat_fe_factura->correlativo ?></div>
                        </div>
                        <div class="col" style="width:45%;"> 
                            <div>RUC: <?= $venta->sunat_fe_factura->cliente_num_doc ?></div>
                            <div>RAZON SOCIAL:<?= $venta->sunat_fe_factura->cliente_razon_social ?></div>
                        </div>
                        <div class="col" style="width:18%;text-align:right"> 
                            <div><?= number_format($venta->sunat_fe_factura->mto_imp_venta, 2) ?></div>
                        </div>
                    </div>
                    <div style="width:100%">     
                        <div class="<?= ($venta->sunat_fe_factura->sunat_cdr_notes != null && $venta->sunat_fe_factura->sunat_cdr_notes != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CDR NOTES:</span> <?= $venta->sunat_fe_factura->sunat_cdr_notes ?></div>
                        <div class="<?= ($venta->sunat_fe_factura->sunat_cdr_description != null && $venta->sunat_fe_factura->sunat_cdr_description != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CDR DESCRIPCIÓN:</span> <?= $venta->sunat_fe_factura->sunat_cdr_description ?></div>
                        <div class="<?= ($venta->sunat_fe_factura->sunat_cdr_code != null && $venta->sunat_fe_factura->sunat_cdr_code != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CDR CODIGO:</span> <?= $venta->sunat_fe_factura->sunat_cdr_code ?></div>
                        <div class="<?= ($venta->sunat_fe_factura->sunat_err_code != null && $venta->sunat_fe_factura->sunat_err_code != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">CODIGO ERROR:</span> <?= $venta->sunat_fe_factura->sunat_err_code ?></div>
                        <div class="<?= ($venta->sunat_fe_factura->sunat_err_message != null && $venta->sunat_fe_factura->sunat_err_message != '') ? 'text-sub' : '' ?>" style="padding-bottom:5px"><span class="text-bold">NOTAS ERROR:</span> <?= $venta->sunat_fe_factura->sunat_err_message ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>