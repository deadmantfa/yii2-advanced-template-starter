<?php

namespace backend\assets;

use yii\web\AssetBundle;

/*
 * @internal
 */
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $css = [
        'icheck-bootstrap/icheck-bootstrap.min.css',
        // more plugin CSS here
    ];
    public $js = [
        // more plugin Js here
        'moment/moment.min.js'
    ];
    public $depends = [
        AdminLteAssets::class,
    ];
}
