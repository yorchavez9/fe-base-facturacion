<div class="container">
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="row">
                <div class="col-12 my-2" id="div_stocks">
                </div>
                <div class="col-12 my-2" id="div_prod_vend">
                </div>
            </div>
        </div>
        <div class="col-md-4" id="div_resumen_diario">
        </div>
        <div class="col-md-4" id="div_facturas_noenviadas">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12 col-md-6 justify-content-center">
            <canvas id="chartHistoricoVentasMensuales"></canvas>
        </div>
        <div class="col-sm-12 col-md-6 justify-content-center">
            <canvas id="chartHistoricoVentasDiarios"></canvas>
        </div>
    </div>
</div>



<?php
echo $this->Element("global_resumen_diario_detalles");
echo $this->Element("modal_productos_stock_minimo");
echo $this->Element("modal_productos_mas_vendidos");
echo $this->Html->ScriptBlock("var _ventas = " . json_encode($ventas) . ";");
echo $this->Html->ScriptBlock("var _compras = " . json_encode($compras) . ";");
?>