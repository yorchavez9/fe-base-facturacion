<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso</title>
    <style>
        @page {
                margin: 1cm 1cm; 
            }

        body {
            
            font-family: sans-serif;
            font-size: 12px
        }
        .logo{
            vertical-align: top;
            display: inline-block;
            width: 30%
        }
        .div-header{
            vertical-align: top;
            display: inline-block;
            text-align: center;
            width: 69%
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
        table{
            width:100%;
            border-collapse:collapse;
        }
        table td{
            padding-left:2px;
        }
        .bg-red{
            background-color:red;
            color:white;
            font-weight:bold
        }
    </style>
</head>

<body>
    <header class="text-center " >
        
        <div class="logo text-center" style="border-right: 1px solid black;">
            <img src="<?= $logo ?>" style="max-height:100px;" />
        </div>
        <div class="div-header"  style="font-size:18px">
            <h2>
                Ingreso
            </h2>
        </div> 
        <div class="row" style="margin-bottom:10px"> 
            <div class="col text-right" style="width:83%;color:grey;padding-top:3px;padding-right:15px;padding-right:2%">
                FECHA
            </div>
            <div class="col" style="width:13%;border: 1px solid black; padding:2px"> 
                <?= ($parte_entrada->fecha != '' && $parte_entrada->fecha != null) ?$parte_entrada->fecha->format("d-m-Y")  : '&nbsp;'  ?> 
            </div>
        </div>
    </header>
    <section class="section-body">
        <table border="1">
            <tr>
                <td colspan="4" class="text-center">
                    REFERENCIA
                </td>
            </tr>
            <tr>
                <td style="width:15%">Con destino: </td>
                <td style="width:60%"> <?= ($parte_entrada->almacen)? $parte_entrada->almacen->nombre : '' ?> </td>
                <td style="width:10%"> Pedido Nº:</td>
                <td style="width:15%">  <?= str_pad($parte_entrada->id, 8,0,STR_PAD_LEFT) ?></td>
            </tr>
            
        </table>
    <br>
        <table border="1">
            <tr class="text-bold text-center"> 
                <td style="width:5%"> Nº </td>
                <td style="width:12%"> CODIGO </td>
                <td style="width:8%"> CANTIDAD </td>
                <td style="width:8%"> UNIDAD </td>
                <td style="width:67%"> DESCRIPCION</td>
            </tr>
            <?php foreach($parte_entrada->parte_entrada_registros as $i => $registro) :?>
                <tr> 
                    <td class="text-center" style="width:5%"> <?= $i+1 ?> </td>
                    <td class="text-center" style="width:12%"> <?= ($registro->item) ? $registro->item->codigo : ''; ?> </td>
                    <td class="text-center" style="width:8%"> <?= $registro->cantidad ?> </td>
                    <td class="text-center" style="width:8%"> <?= ($registro->item) ? $registro->item->unidad : ''; ?> </td>
                    <td style="width:67%"> <?= ($registro->item) ? $registro->item->nombre: '' ?> </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <br>
        <table border="1" style="width:30%">
            <tr>
                <td style="padding-left:5px"> <?= ($parte_entrada->usuario) ? $parte_entrada->usuario->nombre : '' ?> </td>
            </tr>
            <tr class="text-center text-bold">
                <td style="width:33%">Elaborado por</td>
            </tr>
        </table>
    </section>
</body>
</html>