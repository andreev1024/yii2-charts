<?php
namespace andreev1024\yii2-charts\chartjs\assets;

use yii\web\AssetBundle;

/**
 * ChartPluginAsset
 */
class ChartAsset extends AssetBundle
{
    public $sourcePath = '@bower/chartjs';

    public function init()
    {
        $this->js = YII_DEBUG ? ['Chart.js'] : ['Chart.min.js'];
    }
}