<?php
/**
 * @author     Andreev <andreev1024@gmail.com>
 * @copyright  2015-07-16
 */

namespace andreev1024\yii2-charts\chartist;

use andreev1024\yii2-charts\chartist\assets\ChartistAsset;
use andreev1024\yii2-charts\chartist\assets\ChartistWidgetAsset;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\JsExpression;
use Yii;

/**
 * Class Chartist is a wrapper for chartist.js.
 * Read more in the README file.
 */
class Chartist extends Widget
{
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_TOP = 'top';
    const POSITION_BOTTOM = 'bottom';

    /*
     * Chart types
     */
    const TYPE_BAR = 'Bar';
    const TYPE_LINE = 'Line';
    const TYPE_PIE = 'Pie';

    /**
     * @var mixed Legend position. Use const POSITION_LEFT etc.
     *            If set false - lagend hide.
     */
    public $legendPosition = false;

    /**
     * @var string Chart aspect ratio
     */
    public $aspectRatio = 'ct-square';

    /**
     * @var string Unique id for Chartist html element
     */
    public $id = 'chart1';

    /**
     * @var string  Name for data attribute.
     *              After create Chartist instance we save instance in data
     *              attribute. You can get instance from data attribute
     *              and manipulate him.
     */
    public $instanceDataAttr = 'chartInst';

    /**
     * @var string Type of chart
     */
    public $type = 'Line';

    /**
     * @var array   Options. Also you can dynamically create/override this
     *              prop. in $afterCreateCode.
     */
    public $options;

    /**
     * @var array   Responsive options. Also you can dynamically create/override this
     *              prop. in $afterCreateCode.
     */
    public $responsiveOptions;

    /**
     * @var array   Data for chart. Also you can dynamically create/override this
     *              prop. in $afterCreateCode.
     */
    public $data;

    /**
     * @var string Class for chart container.
     */
    public $containerClass = '';

    /**
     * @var Css for chart. These styles override default values or added new.
     */
    public $css;

    /**
     * @var string Js code which will be paste before Chartist instance init.
     */
    public $beforeCreateCode = '';

    /**
     * @var string Js code which will be paste after Chartist instance init.
     */
    public $afterCreateCode = '';


    /**
     * @var array Events with handlers (callback functions).
     */
    public $pluginEvents = [];

    /**
     * @var array
     */
    private $jsonEncode = [
        'options',
        'responsiveOptions',
        'data'
    ];

    private $hideChart = false;

    /**
     * @var array Selectors
     */
    public static $s = [
        'id' => [
            'noDataCont' => 'no-data-cont',
            'chartCont' => 'chart-cont',
        ]
    ];

    /**
     * Widget initialization
     *
     * @author Andreev <andreev1024@gmail.com>
     */
    public function init()
    {
        if (!$this->data) {
            $this->data = ['labels' => [], 'series' => []];
        } elseif (!$this->arraySumRecursive($this->data['series'])) {
            $this->hideChart = true;
            $this->data['series'] = [];
        }

        $this->type = ucfirst($this->type);
    }

    /**
     * Widget run
     *
     * @author Andreev <andreev1024@gmail.com>
     * @return string
     */
    public function run()
    {
        $this->registerCss();
        $this->registerClientScript();
        return $this->render('index', [
            'aspectRatio' => $this->aspectRatio,
            'id' => $this->id,
            'legendPosition' => $this->legendPosition,
            'data' => $this->data,
            'containerClass' => $this->containerClass,
            'type' => $this->type,
            'hideChart' => $this->hideChart,
            's' => static::$s,
        ]);
    }

    /**
     * Registers additional css
     *
     * @author Andreev <andreev1024@gmail.com>
     */
    private function registerCss()
    {
        if ($this->css) {
            $view = $this->getView();
            $view->registerCss($this->css);
        }
    }

    /**
     * Registers client script
     *
     * @author Andreev <andreev1024@gmail.com>
     */
    private function registerClientScript()
    {
        $view = $this->getView();
        ChartistAsset::register($view);
        ChartistWidgetAsset::register($view);

        $j = [];
        foreach ($this->jsonEncode as $property) {
            $j[$property] = Json::encode($this->$property);
        }

        $script =
            "
            (function(){

                var data = {$j['data']},
                    options = {$j['options']},
                    responsiveOptions = {$j['responsiveOptions']};

                {$this->beforeCreateCode}

                var chart = new Chartist.{$this->type}(
                    '#{$this->id}',
                    data,
                    options,
                    responsiveOptions
                )

                $('#{$this->id}').data('{$this->instanceDataAttr}', chart);

                {$this->afterCreateCode}
            ";

        $script .= ";\n";

        if (!empty($this->pluginEvents)) {
            foreach ($this->pluginEvents as $event => $handler) {
                $function = new JsExpression($handler);
                $script .= "chart.on('{$event}', {$function});\n";
            }
        }

        $script .= '})();';

        $view->registerJs($script);
    }

    /**
     * Array sum recursive.
     *
     * @author Andreev <andreev1024@gmail.com>
     * @param $array
     * @param int $sum
     *
     * @return int
     */
    public function arraySumRecursive($array, $sum = 0)
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                $sum += $this->arraySumRecursive($value, $sum);
            } else {
                $sum += $value;
            }
        }
        return $sum;
    }

}
