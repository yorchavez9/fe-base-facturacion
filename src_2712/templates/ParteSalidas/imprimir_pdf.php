<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salida</title>
    <style>
        @page {
                margin: 1cm 1cm; 
            }

        body {
            
            font-family: sans-serif;
            font-size: 14px
        }
        .logo{
            vertical-align: top;
            display: inline-block;
            width: 25%
        }
        .div-header{
            vertical-align: top;
            display: inline-block;
            text-align: center;
            width: 48.4%
        }
        .cuadro{
            vertical-align: top;
            display: inline-block;
            text-align: center;
            border:1px solid black;
            width: 25%;
            padding-top: 15px;
            padding-bottom: 15px
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
        .col-6{
            display: inline-block;
            vertical-align:top;
            width:49%;
            background-color:;
            border:1px solid black;
        }
        .col-6-t{
            display: inline-block;
            vertical-align:top;
            width:47.62%;
            background-color:black;
            color:white;
            border:1px solid black;
            padding:5px
        }
        .col-6-c{
            display: inline-block;
            vertical-align:top;
            width:47.62%;
            padding:5px;
            border:1px solid black
        }
        .col-6-wo{
            display: inline-block;
            vertical-align:top;
            width:49.75%;
            background-color:;
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

        .box-top-right{
            background-color:red;
            color:white;
            margin-top:-19.3px;
            margin-left:-14.4px;
            margin-right:-14.4px;
            padding-left:14.4px;
            font-weight:bold
        }
            
    </style>
</head>

<body>
    <header style="padding-bottom:25px">
        <div class="logo text-center" style="">
            <img src="<?= $logo ?>" style="max-height:100px;" />
        </div>
        <div class="div-header" >
            <div class="text-bold" style="font-size:18px"> <?= $empresa->razon_social ?></div>
            RUC: <?= $empresa->ruc ?>
        </div>
        <div class="cuadro" >
            <div class="row text-center">
                Salida de Mercaderia<br>
                <?= str_pad($parte_salida->id, 8,0,STR_PAD_LEFT) ?>  
            </div>
        </div>
    </header>
    <section class="section-body">
        <div class="row">
            <div class="col-6-t text-center " >
                Direccion Partida
            </div>
            <div class="col-6-t text-center ">
                Direccion Llegada
            </div>
        </div>
        <div class="row">
            <div class="col-6-c">
                <?= ($parte_salida->almacen_salida) ?$parte_salida->almacen_salida->direccion : '&nbsp;'  ?>
            </div>
            <div class="col-6-c">
                <?= ($parte_salida->almacen_destino) ?$parte_salida->almacen_destino->direccion : '&nbsp;'  ?>
            </div>
        </div>
        <div class="row" style="margin-top:20px">
            <div class="col-6-t text-center">
                Remitente
            </div>
            <div class="col-6-t text-center">
                Destinatario
            </div>
        </div>
        <div class="row" >
            <div class="col-6-c" style="">
            <?= ($parte_salida->almacen_salida) ?$parte_salida->almacen_salida->nombre : '&nbsp;'  ?>
            </div>
            <div class="col-6-c" style="height:">
            <?= ($parte_salida->almacen_destino) ?$parte_salida->almacen_destino->nombre : '&nbsp;'  ?>
            </div>
        </div>

        <div class="row" style="margin-top:20px;background-color:black;color:white;padding:5px;width:98%">
            <div class="col text-center" style="width:10%">CODIGO</div>
            <div class="col" style="width:68%">DESCRIPCION</div>
            <div class="col text-center" style="width:10%">UNIDAD</div>
            <div class="col text-center" style="width:10%">CANTIDAD</div>
        </div>
        <div style="border:1px solid black;width:97.7%;padding:5px">
        <?php foreach($parte_salida->parte_salida_registros as $registro): ?>
            <div class="row">
                <div class="col text-center" style="width:10%">
                    <?= ($registro->item)? $registro->item->codigo :'' ?>
                </div>
                <div class="col" style="width:68%">
                    <?= ($registro->item)?$registro->item->nombre:'' ?>
                </div>
                <div class="col text-center" style="width:10%">
                    <?= ($registro->item) ? $registro->item->unidad: '' ?>
                </div>
                <div class="col text-center" style="width:10%">
                    <?= $registro->cantidad ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>