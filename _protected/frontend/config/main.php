<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'digilib-pens',
    'charset' => 'utf-8',
    'name' => 'PENS Digital Library',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'tugas-akhir/index',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        // here you can set theme used for your frontend application 
        // - template comes with: 'default', 'slate', 'spacelab' and 'cerulean'
        'view' => [
            // RMREVIN
            'class' => '\rmrevin\yii\minify\View',
            'enableMinify' => !YII_DEBUG,
            'webPath' => '@web', // path alias to web base
            'basePath' => '@webroot', // path alias to web base
            'minifyPath' => '@webroot/assets/minify', // path alias to save minify result
            'minifyOutput' => true, // minificate result html page
            'jsPosition' => [\yii\web\View::POS_END], // positions of js files to be minified
            'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
            'expandImports' => true, // whether to change @import on content
            'compressOptions' => ['extra' => true], // options for compress
            'concatCss' => true,
            'minifyCss' => true,
            'concatJs' => false,
            'minifyJs' => true,
            'jsOptions' => [
//                'async' => 'async',
//                'defer' => 'defer',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\UserIdentity',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'rules' => [
                'tugas-akhir' => 'tugas-akhir/index',
                'tugas-akhir/index' => 'tugas-akhir/index',
                'tugas-akhir/create' => 'tugas-akhir/create',
                'tugas-akhir/view/<id:\d+>' => 'tugas-akhir/view',
                'tugas-akhir/update/<id:\d+>' => 'tugas-akhir/update',
                'tugas-akhir/delete/<id:\d+>' => 'tugas-akhir/delete',
                'tugas-akhir/<slug>' => 'tugas-akhir/slug',
//                'defaultRoute' => '/site/index',
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
