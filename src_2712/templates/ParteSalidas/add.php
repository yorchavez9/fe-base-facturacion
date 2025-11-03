<?php
echo $this->Form->create(null, ['id' => 'formNuevoParteSalida']);
$this->Form->setTemplates(['inputContainer' => '{{content}}'])
?>
<div class="row">
    <div class="col-12 col-sm-3">
        <div class="form-group">
            <label for="">Usuario</label>
            <?= $this->Form->control('usuario', [ 'label' => false, 'class' => 'form-control form-control-sm', 'value' => $usuario_sesion['nombre'] ,'readonly' ])?>
        </div>
    </div>
    <div class="col-12 col-sm-3">
        <div class="form-group">
            <label for="">Fecha</label>
            <input type="text" name="fecha_emision" class="form-control form-control-sm" placeholder="Fecha" autocomplete="off" value="<?= date('Y-m-d') ?>" readonly >
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="">Descripcion</label>
            <input type="text" name="descripcion" class="form-control form-control-sm" placeholder="Descripcion" autocomplete="off" value="" maxlength="255">
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="d-none d-sm-block col-sm-3">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-barcode fa-fw"></i>
                    </span>
            </div>
            <input class="form-control form-control-sm ingresocodigo" name="codigo" autocomplete="off" placeholder="Código + Tecla Enter" />
        </div>
    </div>
    <div class="col-12 col-sm-9">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-keyboard fa-fw"></i>
                    </span>
            </div>
            <input id="campoBusqueda1" type="text" name="nombre_producto" class="form-control form-control-sm ingresoproducto " placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off" >
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-primary" id="basic-addon2" onclick="ParteSalida.abrirBuscadorProductos()">
                    <i class="fas fa-search fa-fw d-block d-sm-none"></i> <span class="d-none d-sm-block"><i class="fas fa-search fa-fw"></i> Buscar</span>
                </button>
            </div>
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-success" onclick="FormItems.Nuevo()">
                    <i class="fas fa-plus fa-fw fa-fw d-block d-sm-none"></i> <span class="d-none d-sm-block"><i class="fas fa-plus fa-fw fa-fw"></i> Producto</span>
                </button>
            </div>
        </div>
    </div>
</div>
<hr/>

<div class="row">
    <div class="col-12">
        <fieldset >
            <div class="row justify-content-between">
                <div class="col-md-2 align-middle">
                    <h6>Detalle</h6>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 25px;">#</th>
                        <th style="width: 150px;">Código</th>
                        <th style="width: 100px">Foto</th>
                        <th>Producto</th>
                        <th style="width: 100px;">Unidad</th>
                        <th style="width: 80px;">Cantidad</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="detalle">

                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>
<div class="text-right">
    <input type="hidden" class="detalle_items" name="items_registros" value=""/>
    <button type="submit" name="accion" value="venta" class="btn btn-sm btn-primary btn-guardar" id="btn_guardar_venta" disabled >
        Guardar
    </button>
    <button type="submit" name="imprimir" value="imprimir" class="btn btn-sm btn-info btn-guardar" id="btn_guardar_venta" disabled >
        Guardar e Imprimir
    </button>
</div>

<?= $this->Form->end();?>

<div class="modal fade" id="modalBuscadorProductos" tabindex="-1" role="dialog" aria-labelledby="modalBuscadorProductosLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalBuscadorProductosLabel">Buscador de Productos</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-3">


                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-keyboard fa-fw"></i>
                                </span>
                            </div>
                            <input id="campoBusqueda2" type="text" name="nombre_producto" class="form-control ingresoproducto "
                                   placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-primary" id="basic-addon2" onclick="ParteSalida.abrirBuscadorProductos()">
                                    <i class="fas fa-search fa-fw"></i> Buscar
                                </button>
                            </div>

                            <!-- div class="input-group-append ml-1">
                                <button type="button" class="btn btn-sm btn-primary" onclick="FormItems.Nuevo()">
                                    <i class="fas fa-plus fa-fw"></i> Producto
                                </button>
                            </div -->

                        </div>
                    </div>
                    <div class="col-12">
                        <table id="tablafiltro" class="" style="width:100%">
                            <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Stock Local</th>
                                <th>Stock Gral.</th>
                                <th>Precio Venta</th>
                                <th>Moneda</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

echo $this->Element('form_items');
echo $this->Element("global_listado_productos_vue");

