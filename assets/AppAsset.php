<?php

namespace strtob\yii2SystemStatus\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
  
    public $sourcePath = __DIR__ . '/../resources';


    // List of CSS files to be included
    public $css = [
        'css/systemStatus.css',  
    ];

    // List of JS files to be included
    public $js = [
        'js/systemStatus.js',    
    ];

    // List of dependencies (other asset bundles)
    public $depends = [
        'yii\web\YiiAsset',          
    ];
 
    public $cssOptions = [];
    public $jsOptions = [];

    // Optional: Define conditions for including the assets
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,  
    ];
}
