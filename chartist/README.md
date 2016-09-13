This Yii2 widget is wrapper for [Chartist.js](https://github.com/gionkunz/chartist-js).

CONFIGURATION
-------------
 * This module use *.scss files. It's means that if changed Chartist/our styles (scss files)
  we must manualy compile new style.css class.
 * Because this widget is only wrapper you must use Chartist.js documentation.
 * On a page you can place multiple charts. For this you must specify **Id** for each 
 chart.
 * $beforeCreateCode and $afterCreateCode property will allow you to add some 
 custom js code. Order in which they are will be placed on the page:
 
        {beforeCreateCode js block}
        {create new Chartist}
        {afterCreateCode js block}
        
 * When you configure widget and want to give js callback functions you must use
 Yii2 \yii\web\JsExpression (see e.g. below)
 
        <?= Chartist::widget([
            'id' => 'myFirstChart',
            'aspectRatio' => 'ct-perfect-fifth',
            'data' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'series' => [
                                [5, 4, 3, 7, 5, 10],
                            ],
            ],
            'options' => [
                'showPoint' => false,
                'lineSmooth' => false,
                'axisY' => [
                    'offset' => 60,
                    'labelInterpolationFnc' => new \yii\web\JsExpression("function(value){
                        return '$' + value + 'm';
                    }")
                ]
            ],
            'pluginEvents' => [
                'draw' => 'function() {console.log("event is trggered!")}'
            ]
        ]) ?>


MAINTAINERS
-----------
* Andreev <andreev1024@gmail.com>