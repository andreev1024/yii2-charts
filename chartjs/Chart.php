<?php
namespace andreev1024\yii2-charts\chartjs;

use andreev1024\yii2-charts\chartjs\assets\ChartAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Chart use chartjs to draw a needed chart with 6 different types.
 */
class Chart extends Widget
{
    /**
     * @var string display type is line chart
     */
    const TYPE_LINE = 'Line';
    /**
     * @var string display type is bar chart
     */
    const TYPE_BAR = 'Bar';
    /**
     * @var string display type is radar chart
     */
    const TYPE_RADAR = 'Radar';
    /**
     * @var string display type is polar area chart
     */
    const TYPE_POLAR_AREA = 'PolarArea';
    /**
     * @var string display type is pie chart
     */
    const TYPE_PIE = 'Pie';
    /**
     * @var string display type is doughnut chart
     */
    const TYPE_DOUGHNUT = 'Doughnut';
    /**
     * @var string the type of chart to display
     */
    public $type;
    /**
     * @var array HTML attributes for canvas tag where display the chart
     * Example:
     * [
     *    'id' => 'chart',
     *    'width' => 400,
     *    'height' => 400
     * ]
     */
    public $htmlOptions = [];
    /**
     * @var array the labels of X axis on the line chart or bar chart or radar chart.
     */
    public $labels = [];
    /**
     * @var array the configuration options and data to display on the chart
     * - label: the label of one point
     * - data: the value of one point, it's an array or string
     * - options: the options of one point, they're different with every kind of chart
     * Example:
     * [
     *    'label' => 'First point',
     *    'data' => [50, 50],
     *    'options' => [
     *      'fillColor' => 'rgb(151,187,205)',
     *      'strokeColor' => 'rgb(151,187,205)',
     *      'pointColor' => 'rgb(151,187,205)',
     *      'pointStrokeColor' => '#fff',
     *      'pointHighlightFill' => '#fff',
     *      'pointHighlightStroke' => 'rgb(151,187,205)',
     *    ]
     * ]
     */
    public $datasets = [];
    /**
     * @var array the attributes of a chart.
     * - scaleShowGridLines: whether grid lines are shown across the chart
     * - scaleGridLineColor: colour of the grid lines
     * - scaleGridLineWidth: width of the grid lines
     * - scaleShowHorizontalLines: whether to show horizontal lines (except X axis)
     * - scaleShowVerticalLines: whether to show vertical lines (except Y axis)
     * - bezierCurve: whether the line is curved between points
     * - bezierCurveTension: tension of the bezier curve between points
     * - pointDot: whether to show a dot for each point
     * - pointDotRadius: radius of each point dot in pixels
     * - pointDotStrokeWidth: pixel width of point dot stroke
     * - pointHitDetectionRadius: amount extra to add to the radius to cater for hit detection outside the drawn point
     * - datasetStroke: whether to show a stroke for datasets
     * - datasetStrokeWidth: pixel width of dataset stroke
     * - datasetFill: whether to fill the dataset with a colour
     * - legendTemplate: a legend template
     */
    public $chartOptions = [];
    
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        
        if ($this->type === null) {
            $this->type = self::TYPE_LINE;
        }
        
        if (!empty($this->datasets)) {
            $points = [];
            
            foreach ($this->datasets as $item) {
                $points[] = $this->generatePoint($item);
            }
            
            $this->datasets = $points;
        }
    }

    /**
     * Renders the widget.
     * This method will register the javascript and output HTML
     */
    public function run()
    {
        $this->renderHtml();
        $this->registerAssets();
    }

    /**
     * Renders the canvas tag for the chart.
     */
    protected function renderHtml()
    {
        if (!isset($this->htmlOptions['id']) || empty($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = strtolower($this->type) . 'Chart';
        }
        
        if (!isset($this->htmlOptions['width']) || empty($this->htmlOptions['width'])) {
            $this->htmlOptions['width'] = 400;
        }
        
        if (!isset($this->htmlOptions['height']) || empty($this->htmlOptions['height'])) {
            $this->htmlOptions['height'] = 400;
        }
        
        echo Html::tag('canvas', '', $this->htmlOptions);
    }
    
    /**
     * Registers the needed javascript
     */
    protected function registerAssets()
    {
        //needed attributes for chart
        $id = $this->htmlOptions['id'];
        $type = $this->type;
        $chartOptions = !empty($this->chartOptions) ? Json::encode($this->chartOptions) : '{}';
        
        if (!empty($this->labels) && !empty($this->datasets)) {
            $data = [
                'labels' => $this->labels,
                'datasets' => $this->datasets
            ];
            
            if ($this->type === self::TYPE_POLAR_AREA 
                || $this->type === self::TYPE_PIE 
                || $this->type === self::TYPE_DOUGHNUT) {
                $data = $this->datasets;
            }
            
            $data = Json::encode($data);
        } else {
            $data = '{}';
        }
        
        //get current view and add chart javascript into it
        $view = $this->getView();
        ChartAsset::register($view);

        $view->registerJs(";
            var content = document.getElementById('{$id}').getContext('2d');
            var chart = new Chart(content).{$type}({$data}, {$chartOptions});
        ");
    }
    
    /**
     * Generate color options for each point on chart
     * @return array the options of one point
     */
    protected function generatePointOptions()
    {   
        if ($this->type === self::TYPE_POLAR_AREA 
            || $this->type === self::TYPE_PIE 
            || $this->type === self::TYPE_DOUGHNUT) {
            return [
                'color' => '#' . dechex(rand(0,16777215)),
                'highlight' => '#' . dechex(rand(0,16777215)),
            ];
        }
        elseif ($this->type === self::TYPE_LINE || $this->type === self::TYPE_RADAR) {
            $pointColor = 'rgb(' . rand(128,255) . ',' . rand(128,255) . ',' . rand(128,255) . ')';
            $lineColor = '#' . dechex(rand(0,16777215));
            
            return [
                'fillColor' => $pointColor,
                'strokeColor' => $pointColor,
                'pointColor' => $pointColor,
                'pointStrokeColor' => $lineColor,
                'pointHighlightFill' => $lineColor,
                'pointHighlightStroke' => $pointColor,
            ];
        }
        elseif ($this->type === self::TYPE_BAR) {
            $color = 'rgb(' . rand(128,255) . ',' . rand(128,255) . ',' . rand(128,255) . ')';
            
            return [
                'fillColor' => $color,
                'strokeColor' => $color,
                'highlightFill' => $color,
                'highlightStroke' => $color,
            ];
        }
        else {
            return [];
        }
    }
    
    /**
     * Generate data and options for one point on chart
     * @param array $data
     * @return array
     */
    protected function generatePoint($data)
    {
        if ($this->type === self::TYPE_POLAR_AREA 
            || $this->type === self::TYPE_PIE 
            || $this->type === self::TYPE_DOUGHNUT) {
            $detail = [
                'label' => isset($data['label']) ? $data['label'] : '',
                'value' => isset($data['data']) ? $data['data'] : ''
            ];
        }
        else {
            $detail = [
                'label' => isset($data['label']) ? $data['label'] : '',
                'data' => isset($data['data']) ? $data['data'] : []
            ];
        }
        
        if (isset($data['options'])) {
            $options = $data['options'];
        } else {
            $options = $this->generatePointOptions();
        }
        
        return array_merge($detail, $options);
    }
}