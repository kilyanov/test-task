<?php
namespace app\modules\swagger\assets;

use yii\web\AssetBundle;

class SwaggerUiAsset extends AssetBundle
{
    public $sourcePath = '@bower/swagger-ui/dist';

    public $js = [
        'swagger-ui-bundle.js',
        'swagger-ui-bundle.js.map',
        'swagger-ui-standalone-preset.js',
        'swagger-ui-standalone-preset.js.map',
    ];

    public $css = [
        'swagger-ui.css',
    ];
}