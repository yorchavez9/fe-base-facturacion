<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Almacen</title>
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
                Reporte Stock Almacen: <?= $almacen->nombre?> 
            </h2>
            <h3>
                <?= $almacen->direccion ?> 
            </h3>
        </div>
    </header>
    <section class="section-body">
        <div >
            <div style="width:100%;">
                <div class="row" style="padding-top:15px;margin-bottom:15px;border-bottom:1px solid black;border-top:1px solid black">
                    <div class="col" style="width:60%"> 
                        <span class="text-bold">NOMBRE: </span>
                    </div>
                    <div class="col" style="width:10%"> 
                        <span class="text-bold">UNIDAD: </span>
                    </div>                       
                    <div class="col" style="width:10%"> 
                        <span class="text-bold">STOCK MIN. LOCAL: </span>
                    </div>
                    <div class="col" style="width:10%;"> 
                        <span class="text-bold">STOCK MIN. GLOBAL: </span>
                    </div>
                    <div class="col" style="width:8%;"> 
                        <span class="text-bold">STOCK: </span>
                    </div>
                </div>
                <?php foreach($stocks as $ss) :?>
                    <div class="row" style="">
                        <?php if($ss->item): ?>
                            <div class="col" style="width:60%;"> 
                                <?= $ss->item->nombre ?>
                            </div>
                            <div class="col" style="width:10%"> 
                                <?= $ss->item->unidad ?>                        
                            </div>                       
                            <div class="col" style="width:10%"> 
                                <?= $ss->item->stock_minimo_local ?>
                            </div>
                            <div class="col" style="width:10%;"> 
                                <?= $ss->item->stock_minimo_global ?>
                            </div>
                            <div class="col" style="width:8%;"> 
                                <?= $ss->stock ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>