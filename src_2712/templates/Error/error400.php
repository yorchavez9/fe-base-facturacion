<?php

/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;
use Cake\Error\Debugger;
?>

<?php if (!Configure::read('debug')) : ?>
    <section>
        <div class="container text-center">
            <h3 class="error-title mb-2">
                ¡Ups....! La página que buscas no existe
            </h3>
            <img src="<?= $this->Url->build("/media/error_400.webp") ?>" width="256">

            <div class="error-caption">
                Regresa a nuestra página de <a class="inicio_link" href="<?= $this->Url->build(['controller'    =>  'reportes', 'action' =>  'reportesGenerales']) ?>">inicio</a>
            </div>

            <div class="error-caption2">
                <a class="report_error_link" href="javascript:void(0)" onclick="ModalPublicidadReporteError.abrir()" >
                    ¡Ayúdanos a mejorar reportando este error!
                </a>
            </div>
            <div class="error-caption-blue">
                Y gánate un premio
            </div>

        </div>
    </section>
    <?= $this->Element('publicidad/modal_publicidad_reporte_error') ?>
<?php else : ?>
    <?php
    $this->layout = 'error';

    if (Configure::read('debug')) :
        $this->layout = 'dev_error';

        $this->assign('title', $message);
        $this->assign('templateName', 'error400.php');

        $this->start('file');
    ?>
        <?php if (!empty($error->queryString)) : ?>
            <p class="notice">
                <strong>SQL Query: </strong>
                <?= h($error->queryString) ?>
            </p>
        <?php endif; ?>
        <?php if (!empty($error->params)) : ?>
            <strong>SQL Query Params: </strong>
            <?php Debugger::dump($error->params) ?>
        <?php endif; ?>
        <?= $this->element('auto_table_warning') ?>
    <?php

        $this->end();
    endif;
    ?>
<?php endif; ?>

<style>
    .error-title {
        color: #0F44AC;
    }

    .error-caption,
    .error-caption-blue {
        font-size: 0.8rem;
        font-weight: 400;
        color: #C0C7CA;
    }

    .error-caption-blue {
        color: #8EB0F4;
    }

    .error-caption2 {
        border: 1px dashed #A5B2CB;
        display: inline-block;
        font-size: 1.7rem;
        color: #A5B2CB;
        border-radius: 5px;
        margin-top: 2vh;
    }

    .report_error_link {
        text-decoration: none !important;
        display: inline-block;
        margin-right: 5px;
        padding: 5px 10px;
        margin: auto;
        width: fit-content !important;

    }

    .error-caption2:hover {
        transform: scale(1.2);
        transition-duration: 2s;
    }

    .inicio-link {
        text-decoration: none;
    }

    .inicio-link:hover {
        transform: scale(1.1);
    }
</style>