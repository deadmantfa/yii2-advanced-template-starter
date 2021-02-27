<?php

namespace backend\assets;

use dmstr\adminlte\web\FontAwesomeAsset;
use webzop\notifications\WebNotificationsAsset;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
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
        'css/glyphs.scss',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        '//unpkg.com/default-passive-events@2.0.0/dist/index.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        AdminLtePluginAsset::class,
        FontAwesomeAsset::class,
        WebNotificationsAsset::class
    ];
}
