This is Yii2 wrapper for [Chart.js](https://github.com/nnnick/Chart.js).

Usage
-----
The following chart types are supported: 

- TYPE_LINE: Line chart
- TYPE_BAR: Bar chart
- TYPE_RADAR: Radar chart
- TYPE_POLAR_AREA: Polar area chart
- TYPE_PIE: Pie chart
- TYPE_DOUGHNUT: Doughnut chart

The following example is using the 'Line' type of chart.

```
<?= Chart::widget([
    'type' => Chart::TYPE_LINE,
    'htmlOptions' => [
        'id' => 'chart', 
        'width' => 800,
        'height' => 400
    ],
    'labels' => ['January', 'February', 'March', 'April', 'May'],
    'datasets' => [
        [
            'label' => 'First point',
            'data' => [50, 60, 40, 50, 20]
        ],
        [
            'label' => 'Second point',
            'data' => [60, 20, 40, 50, 60]
        ]
    ],
    'chartOptions' => [
        'datasetFill' => false
    ]
]);
?>
```

**type** is the type of chart to display.

**htmlOptions** is HTML attributes for canvas tag where display the chart.

**chartOptions** is the attributes of the chart. 
You can view all attributes from page [Chart.js Documentation](http://www.chartjs.org/docs/)

**labels** is the values on X axis of the chart. 
You don't need to use this option with Polar area chart, Pie chart, Doughnut chart.

**datasets** are the configuration options and data to display on the chart.

- **label** is the name of point on chart.

- **data** is the value(s) of point on chart.

- **options** is the configuration data for a point.
This wrapper creates it automatically but if you want, you can set data for it.

The following example is using datasets configuration options for 'Line' chart

```
    'datasets' => [
        [
            'label' => 'First point',
            'data' => [50, 60, 40, 50, 20],
            'options' => [
                'fillColor' => 'rgb(151,187,205)',
                'strokeColor' => 'rgb(151,187,205)',
                'pointColor' => 'rgb(151,187,205)',
                'pointStrokeColor' => '#fff',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgb(151,187,205)',
            ]
        ],
        [
            'label' => 'Second point',
            'data' => [60, 20, 40, 50, 60],
            'options' => [
                'fillColor' => 'rgb(161,177,205)',
                'strokeColor' => 'rgb(161,177,205)',
                'pointColor' => 'rgb(161,177,205)',
                'pointStrokeColor' => '#fff',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgb(161,177,205)',
            ]
        ]
    ]
```