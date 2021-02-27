<?php


namespace backend\assets;

use yii\web\AssetBundle;

class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $css = [
        '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap',
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
