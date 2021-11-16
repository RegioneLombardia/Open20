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

/** @var $model \open20\amos\myactivities\basic\CommunityToValidate */

?>
<div class="wrap-activity">
    <div class="col-md-1 col-xs-2 icon-plugin">
        <?= AmosIcons::show('group', [], 'dash') ?>
    </div>
    <?= \open20\amos\myactivities\widgets\UserRequestValidation::widget([
        'model' => $model,
        'labelKey' => AmosMyActivities::t('amosmyactivities', 'Community Validation'),
    ]) ?>
    <div class="col-md-3 col-xs-12 wrap-action">
        <?php
        echo Html::a(AmosIcons::show('check') . ' ' . AmosMyActivities::t('amosmyactivities', 'Validate'),
            Yii::$app->urlManager->createUrl([
                '/community/community/publish',
                'id' => $model->id,
                'redirectWizard' => 0,
            ]),
            ['class' => 'btn btn-primary']
        )
        ?>

        <?php
        echo Html::a(AmosIcons::show('close') . ' ' . AmosMyActivities::t('amosmyactivities', 'Reject'),
            Yii::$app->urlManager->createUrl([
                '/community/community/reject',
                'id' => $model->id,
            ]),
            ['class' => 'btn btn-secondary']
        )
        ?>
    </div>
</div>
<hr>
