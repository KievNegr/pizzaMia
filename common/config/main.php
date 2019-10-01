<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@page' => '/frontend/web/images/page',
        '@avatar' => '/frontend/web/images/avatar',
        '@category' => '/frontend/web/images/category',
        '@goods' => '/frontend/web/images/goods',
        '@thumbs' => '/frontend/web/images/goods/thumbs',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'name' => 'Pizza Mia',
];
