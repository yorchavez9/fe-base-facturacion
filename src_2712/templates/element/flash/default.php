<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="col-12 text-center pb-2 pt-4">
    <div class="  <?= h($class) ?> p-4 m-4" onclick="this.classList.add('hidden');"><?= $message ?></div>
</div>
