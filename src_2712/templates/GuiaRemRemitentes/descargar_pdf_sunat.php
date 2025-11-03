<div class="text-center">
    <?php if ($status): ?>
        <div class="my-3">
            <h1>Descargar Guía de remision desde SUNAT</h1>
            <p>
                Por favor, haga clic en el siguiente enlace
            </p>
            <h4>
                <a href="<?= $url; ?>" target="_blank">
                    <i class='fas fa-download fa-fw'></i>
                    Haz clic aqui para descargar de Sunat
                </a>
            </h4>
            <br>
            <p>
                Si el enlace no funciona, copie y pegue en su navegador:<br />
                <h4>
                    <?= $url ?><br />
                </h4>

            </p>
            <div class="alert alert-warning h6" role="alert">
                <strong>Importante:</strong> Si el enlace no carga, por favor, espere unos momentos e intente nuevamente.
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            <?= $url ?>
        </div>
            <a href="<?= $this->Url->build(['action' => 'index'])?>">
                <i class="fas fa-arrow-circle-left"></i>
                Guias de Remision
            </a>
    <?php endif; ?>
</div>