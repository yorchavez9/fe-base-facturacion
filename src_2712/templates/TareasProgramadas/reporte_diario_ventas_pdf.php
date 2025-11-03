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
    <title>Resumen Diario</title>
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
               Resumen Diario - Ventas<br>
            </h2>
        </div>
    </header>
    <section class="section-body">
        <div>
            <div style="width:100%;">
                <div class="text-center text-bold" style="width:100%;margin-top:5px;border-top:1px solid black;border-bottom:1px solid black;letter-spacing:5px;">
                    Ventas - <?= date("d-m-Y") ?>
                </div>
                <div class="row" style=";background-color:;">
                    <div class="col" style="width:15%;background-color:;"> 
                        <div class="text-bold">FECHA: </div>
                    </div>
                    <div class="col" style="width:15%;background-color:"> 
                        <div class="text-bold">CLIENTE: </div>
                    </div>
                    <div class="col" style="width:27.5%;background-color:"> 
                        <div class="text-bold">DOCUMENTO: </div>
                    </div>
                    <div class="col text-center" style="width:10%;background-color:"> 
                        <div class="text-bold">OP. GRAV: </div>
                    </div>
                    <div class="col text-center" style="width:10%;background-color:"> 
                        <div class="text-bold">SUBTOTAL: </div>
                    </div>
                    <div class="col text-center" style="width:10%;background-color:"> 
                        <div class="text-bold">IGV: </div>
                    </div>
                    <div class="col text-center" style="width:10%;background-color:"> 
                        <div class="text-bold">TOTAL: </div>
                    </div>
                </div>
                <?php 
                    $top_gravadas = 0;
                    $tsub_total = 0;
                    $top_igv_monto = 0;
                    $total = 0;
                ?>
                <?php foreach($ventas as $venta) :?>
                    <div class="row" style=";background-color:;">
                        <div class="col" style="width:15%;background-color:;"> 
                            <?= $venta->fecha_venta->format("d/m/Y") ?>
                        </div>
                        <div class="col" style="width:15%;background-color:"> 
                            <div>
                                RUC: <?= $venta->cliente_doc_numero ?>
                            </div>
                            <div>
                                <?= $venta->cliente_razon_social ?>
                            </div>
                        </div>
                        <div class="col" style="width:27.5%;background-color:"> 
                            <div>
                                <?= $venta->documento_tipo ?>
                            </div>    
                            <div>
                                <?= $venta->documento_serie ?> - 
                                <?= $venta->documento_correlativo ?>
                            </div>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format( $venta->op_gravadas,2 ) ?>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format( $venta->subtotal,2 ) ?>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format( $venta->igv_monto,2 ) ?>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format( $venta->total, 2)  ?>
                        </div>
                    </div>
                <?php
                $top_gravadas += $venta->op_gravadas;
                $tsub_total += $venta->subtotal;
                $top_igv_monto += $venta->igv_monto;
                $total += $venta->total;

                endforeach; ?>
               <div class="row" style="margin-top:5px;background-color:;">
                        <div class="col" style="width:15%;background-color:;"> 
                        </div>
                        <div class="col" style="width:15%;background-color:"> 
                        </div>
                        <div class="col" style="width:27.5%;background-color:"> 
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format($top_gravadas, 2) ?>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format($tsub_total, 2) ?>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format($top_igv_monto, 2) ?>
                        </div>
                        <div class="col text-center" style="width:10%;background-color:"> 
                            <?= number_format($total,2) ?>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</body>
</html>