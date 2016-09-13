<?php
use andreev1024\yii2-charts\chartist\Chartist;

//  default Chartist series Names
$seriesNames = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o'];
$chartInitElement = "<div class='ct-chart {$aspectRatio}' id='{$id}'></div>";
$legendHtml = '';

$buildLegendItem = function ($seriesName, $label) {
    $legendHtml = "<li>\n";
    $legendHtml .= "<span class='ct-series-{$seriesName} ct-legend-marker'></span>{$label}\n";
    $legendHtml .= "</li>\n";
    return $legendHtml;
};

if ($legendPosition) {
    $legendHtml .= "<ul class='list-unstyled'>";
    if ($type === Chartist::TYPE_LINE || $type === Chartist::TYPE_BAR) {
        foreach ($data['series'] as $key => $value) {
            if (!isset($value['data']) || !isset($value['name'])) {
                $msg = 'Chart legend error: for this chart type `series.data` and `series.name` are required.';
                throw new \yii\base\InvalidConfigException($msg);
            }
            $legendHtml .= $buildLegendItem($seriesNames[$key], $value['name']);
        }
    } elseif ($type === Chartist::TYPE_PIE) {
        if(!isset($data['labels'])) {
            $msg = 'Chart legend error: for this chart type `labels` is required.';
            throw new \yii\base\InvalidConfigException($msg);
        }
        foreach ($data['labels'] as $key => $label) {
            $legendHtml .= $buildLegendItem($seriesNames[$key], $label);
        }
    } else {
        throw new \yii\base\InvalidConfigException('This chart type do not support with legend.');
    }
    $legendHtml .= "</ul>";
}

?>
<div class='<?= $containerClass ?>'>
    <div class='row <?= ($hideChart ? '' : 'hide') ?>' id="<?= $s['id']['noDataCont'] ?>">
        <div class="col-md-12">
            <?= Yii::$app->translate->t('No data') ?>
        </div>
    </div>
    <div class='row  <?= ($hideChart ? 'hide' : '') ?>' id="<?= $s['id']['chartCont'] ?>">
        <?php if ($legendPosition === Chartist::POSITION_LEFT): ?>

            <div class="col-md-3 ct-legend">
                <?= $legendHtml ?>
            </div>
            <div class="col-md-9 ">
                <?= $chartInitElement ?>
            </div>

        <?php elseif ($legendPosition === Chartist::POSITION_RIGHT): ?>

            <div class="col-md-9">
                <?= $chartInitElement ?>
            </div>
            <div class="col-md-3 ct-legend">
                <?= $legendHtml ?>
            </div>

        <?php elseif ($legendPosition === Chartist::POSITION_TOP): ?>

            <div class="col-md-12">
                <div clas="row">
                    <div class="col-md-12 ct-legend">
                        <?= $legendHtml ?>
                    </div>
                </div>
                <div clas="row">
                    <div class="col-md-12">
                       <?= $chartInitElement ?>
                   </div>
                </div>
            </div>

        <?php elseif ($legendPosition === Chartist::POSITION_BOTTOM): ?>

            <div class="col-md-12">
                <div clas="row">
                    <div class="col-md-12">
                       <?= $chartInitElement ?>
                   </div>
                </div>
                <div clas="row">
                    <div class="col-md-12 ct-legend">
                        <?= $legendHtml ?>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <?= $chartInitElement ?>

        <?php endif; ?>
    </div>
</div>