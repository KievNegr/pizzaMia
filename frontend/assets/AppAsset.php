<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:300,400,500|Comfortaa:400,700|PT+Sans+Narrow&amp;subset=cyrillic,cyrillic-ext',
        'css/fontawesome/css/all.css',
        'css/style.css?version=1.11',
        'css/mediaquery.css?version=1.12',
        'css/datepicker.css?version=1.01',
        'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css',
    ];
    
    public $js = [
        'js/jquery.maskedinput.min.js',
        'js/jquery-ui.js',
        'js/datepicker.js?version=1.01',
        'js/datepicker.en.js',
        'js/script.js?version=1.08',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
