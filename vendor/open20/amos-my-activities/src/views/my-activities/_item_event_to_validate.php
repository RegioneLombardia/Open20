<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\views\my-activities
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\myactivities\AmosMyActivities;
use yii\web\View;

/** @var $model \open20\amos\myactivities\basic\EventToValidate */

$js = "
$('.events-reject-btns').on('click', function(event) {
    event.preventDefault();
    var hrefValue = $(this).attr('href');
    var visibleInCalendar = confirm(\"" . AmosMyActivities::t('amosmyactivities', "Is the event still to be visible in the calendar even during the edit") . "?\");
    this.href = hrefValue + '&visibleInCalendar=' + (visibleInCalendar ? 1 : 0);
    window.location.href = this.href;
});
";
$this->registerJs($js, View::POS_READY);

?>
<div class="wrap-activity">
    <div class="col-md-1 col-xs-2 icon-plugin">
        <?= AmosIcons::show('calendar', [], 'dash') ?>
    </div>
    <?= \open20\amos\myactivities\widgets\UserRequestValidation::widget([
        'model' => $model,
        'labelKey' => AmosMyActivities::t('amosmyactivities', 'Validation event'),
    ]) ?>
    <div class="col-md-3 col-xs-12 wrap-action">
        <?php
        echo Html::a(AmosIcons::show('check') . ' ' . AmosMyActivities::t('amosmyactivities', 'Validate'),
            Yii::$app->urlManager->createUrl([
                '/events/event/validate',
                'id' => $model->id,
            ]),
            ['class' => 'btn btn-primary']
        )
        ?>
        <?php
        echo Html::a(AmosIcons::show('close') . ' ' . AmosMyActivities::t('amosmyactivities', 'Reject'),
            Yii::$app->urlManager->createUrl([
                '/events/event/reject',
                'id' => $model->id,
            ]),
            ['class' => 'btn btn-secondary events-reject-btns']
        )
        ?>
    </div>
</div>
<hr>
