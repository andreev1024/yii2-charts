<?php
/**
 * @author     Andreev <andreev1024@gmail.com>
 * @copyright  2015-08-24
 */
namespace andreev1024\yii2-charts\chartist\assets;

use yii\web\AssetBundle;

class ChartistWidgetAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__;

        if (!file_exists(__DIR__ . '/css/style.css')) {
            throw new \Exception('Please, compile css files (see Readme)');
        }

        $this->css = ['css/style.css'];

        $this->depends = [
            'yii\bootstrap\BootstrapAsset',
        ];
    }
}
