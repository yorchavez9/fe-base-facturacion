<form method="POST">
    <div class="row">
        <div class="col-12">
            <label>Cabecera de Venta:</label>
            <textarea name="cabecera" class="form-control"><?=$doc_cabecera?></textarea>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <label>Pie de Venta:</label>
            <textarea name="pie" class="form-control"><?=$doc_pie?></textarea>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-primary mr-2" id="btnLimpiar"><i class="fa fa-eraser"></i> Limpiar</button>
            <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
</form>

