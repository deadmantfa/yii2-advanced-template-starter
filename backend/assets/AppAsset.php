<?php

namespace backend\assets;

use dmstr\adminlte\web\FontAwesomeAsset;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/glyphs.scss',
    ];
    public $js = [
    ];
    public $depends = [
        AdminLtePluginAsset::class,
        FontAwesomeAsset::class
    ];
}
