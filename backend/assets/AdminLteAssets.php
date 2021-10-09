<?php

namespace backend\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle as BaseAdminLteAsset;
use yii\web\YiiAsset;


/*
 * @internal
 */

class AdminLteAssets extends BaseAdminLteAsset
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $css = [
        '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap',
        'css/adminlte.min.css',
    ];
    public $js = [
        'js/adminlte.min.js'
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
    ];
}
