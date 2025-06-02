<?php
/**
 * -----------------------------------------------------------------------------
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * -----------------------------------------------------------------------------
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use Yii;

// set @themes alias so we do not have to update baseUrl every time we change themes

/**
 * -----------------------------------------------------------------------------
 * @author Qiang Xue <qiang.xue@gmail.com>
 *
 * @since 2.0
 * -----------------------------------------------------------------------------
 */
class GetShitDoneAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@themes';
    public $sourcePath = '@bower/get-shit-done';

    public $css = [
//        'css/site.css',
        'bootstrap3/css/bootstrap.css',
        'assets/css/gsdk.css',
        'assets/css/demo.css',
        'bootstrap3/css/font-awesome.css'
    ];
    public $js = [
//        'bootstrap3/js/bootstrap.js',
//        'assets/js/gsdk-checkbox.js',
//        'assets/js/gsdk-radio.js',
        'assets/js/gsdk-bootstrapswitch.js',
        'assets/js/get-shit-done.js',
//        'jquery/jquery-1.10.2.js',
//        'assets/js/jquery-ui-1.10.4.custom.min.js',
        'assets/js/custom.js',
    ];

    public $depends = [
    ];
}

