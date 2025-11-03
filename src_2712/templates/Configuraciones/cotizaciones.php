<form method="POST">
    <div class="row">
        <div class="col-12">
            <label>Cabecera de Cotizaciones:</label>
            <textarea name="cab" class="form-control"><?=$doc_cab?></textarea>
        </div>
    </div>
    <br>
    <div class="row">

        <div class="col-12">
            <label>Pie de Cotizaciones:</label>
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

