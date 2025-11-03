<div>
    <legend> Configuracion para importar/exportar contactos </legend>
    <div>
        <form method="POST" enctype="multipart/form-data">
            <div>
                Para importar seleccione el mismo archivo que se exportó, o en su defecto un archivo .txt con codigo en formato JSON, de una sola linea.
            </div>
            <div class="py-3 d-flex items-align-center">
                <a class="btn btn-primary" href="<?= $this->Url->build(['action' => 'descargarRegistrosContactosJson']); ?>" target="_blank">
                    <i class="fas fa-upload fa-fw"></i>
                    Exportar contactos
                </a>
                <div class="col">
                    <input type="file" name="archivo" class="form-control" accept=".txt" required>
                </div>
                <div class="px-2">
                    <button class="btn btn-info">
                        <i class="fas fa-download fa-fw"></i>
                        Importar contactos
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>