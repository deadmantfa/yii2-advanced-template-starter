<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use dmstr\adminlte\widgets\Alert;

/**
 * @var $module Da\User\Module
 */
?>

<?php if ($module->enableFlashMessages): ?>
    <div class="row">
        <div class="col-xs-12">
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <?php if (in_array($type, ['success', 'danger', 'warning', 'info'], true)): ?>
                    <?= Alert::widget() ?>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
