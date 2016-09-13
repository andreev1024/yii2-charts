<?php
/**
 * @author     Andreev <andreev1024@gmail.com>
 * @copyright  2015-08-24
 */
namespace andreev1024\yii2-charts\chartist\assets;

use yii\web\AssetBundle;

class ChartistAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $this->sourcePath = '@bower/chartist';
        $this->js = ['dist/chartist.js'];
        $this->depends = [
            'yii\web\JqueryAsset'
        ];
    }
}
