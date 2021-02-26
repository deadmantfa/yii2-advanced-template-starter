<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

/**
 * Main frontend application asset bundle.
 */
class NotificationsAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot';

    /**
     * @inheritdoc
     */
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/notifications.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        JqueryAsset::class,
        YiiAsset::class,
    ];
}
