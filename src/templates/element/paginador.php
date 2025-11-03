<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first(' «« Primero ') ?>
        <?= $this->Paginator->prev(' « Anterior ') ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(' Siguiente » ') ?>
        <?= $this->Paginator->last(' Último »» ') ?>
    </ul>
    <p><?= $this->Paginator->counter(__('Pagina {{page}} de {{pages}}, mostrando {{current}} item(s) de {{count}} total')) ?></p>
</div>
