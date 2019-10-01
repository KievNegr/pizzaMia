<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/datepicker.css',
        'https://fonts.googleapis.com/css?family=Roboto:300,400,500|Comfortaa:400,700|Open+Sans+Condensed:300|PT+Sans+Narrow&amp;subset=cyrillic,cyrillic-ext',
        'css/fontawesome/css/all.css',
        'css/style.css',
        'css/datepicker.css',
    ];

    public $js = [
        'js/jquery.maskedinput.min.js',
        'js/jquery-ui.js',
        'js/datepicker.js',
        'js/datepicker.en.js',
        'js/script.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
