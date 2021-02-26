<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

/**
 * @var Role $model
 * @var $this               yii\web\View
 * @var $unassignedItems    string[]
 */
$this->title = Yii::t('usuario', 'Create new role');
$this->params['breadcrumbs'][] = $this->title;

use Da\User\Model\Role; ?>

<?php $this->beginContent('@backend/views/user/shared/admin_layout.php') ?>

<?= $this->render(
    '/role/_form',
    [
        'model' => $model,
        'unassignedItems' => $unassignedItems,
    ]
) ?>

<?php $this->endContent() ?>
