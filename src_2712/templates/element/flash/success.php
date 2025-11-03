<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="col-12 text-center pb-2 pt-4">
    <div class="alert alert-success" onclick="this.classList.add('hidden')"><?= $message ?></div>
</div>
