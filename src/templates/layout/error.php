<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?php
        echo $this->Html->css("/assets/bootstrap/css/bootstrap.css");
    ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
                <?= $this->Html->link(__('Back'), 'javascript:history.back()') ?>
            </div>
        </div>
    </div>
    <?php
        echo $this->Html->Script("/assets/jquery/jquery.js");
        echo $this->Html->Script("/assets/popper.js");
        echo $this->Html->Script("/assets/bootstrap/js/bootstrap.bundle.min.js");
    ?>
</body>
</html>
