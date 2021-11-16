<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    retecomuni\frontend\views\site\parts
 * @category   CategoryName
 */

/**
 * @var \open20\amos\events\models\Event $model
 */
use retecomuni\frontend\assets\AppAsset;
use yii\helpers\Html;

$appAsset = AppAsset::register($this);

$likEventDetail = '/events/event/view?id=' . $model->id;

$url = $appAsset->baseUrl . '/img/generic.jpg';
if (!is_null($model->eventLogo)) {
    $url = $model->getEventsImageUrl('original', false);
}
$format = Yii::$app->getFormatter();
if ($model->begin_date_hour && $model->end_date_hour) {
    $beginDate = $format->asDate($model->begin_date_hour, 'full');
    $endDate = $format->asDate($model->end_date_hour, 'full');
    $beginTime = $format->asTime($model->begin_date_hour, 'HH:mm');
    $endTime = $format->asTime($model->end_date_hour, 'HH:mm');
    if ($beginDate == $endDate) {
        $date = ucfirst($beginDate); //.' dalle '.$beginTime .' alle '.$endTime;
    } else {
        $date = 'Da ' . $beginDate . /* ' alle '. $beginTime . */
                ' a ' . $endDate; //. ' alle '.$endTime;
    }
} else if ($model->begin_date_hour) {
    $beginDate = $format->asDate($model->begin_date_hour, 'full');
    $date = ucfirst($beginDate);
} else if ($model->end_date_hour) {
    $endDate = $format->asDate($model->end_date_hour, 'full');
    $date = ucfirst($endDate);
}
?>


<div class="col-md-8 col-xs-12 nop">
    <div id="event-<?= $model->id ?>" class="event">


        <div class="container-event">

            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->eventLogo)) {
                $url = $model->eventLogo->getUrl('original', false, true);
            }
            ?>
            <div class="wrap-img">
            <?=
            Html::img($url, [
                'alt' => $model->getAttributeLabel('eventLogo'),
                'class' => 'img-responsive'
            ]);
            ?>
            </div>
            <div class="abstract">
                <h2 class="box-widget-subtitle">
                    <a href="<?= $likEventDetail ?>" title="<?= $model->getTitle() ?>" data-pjax="0">
                        <?= $model->getTitle() ?>
                    </a>
                </h2>
                <h3 class="box-widget-subtitle">
                <?= $model->summary ?>
                </h3>
                <div class="container-date col-xs-12 nop">
                    <div class="col-xs-10 nop">
<!--                <span class="am am-chevron-right"></span>-->
<!--                <span class="am am-chevron-right"></span>-->
                        <span class="date"><?= $date ?>
                        </span>
                    </div>
                    <div class="col-xs-2 nop">
                        <span class="dash dash-calendar"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>