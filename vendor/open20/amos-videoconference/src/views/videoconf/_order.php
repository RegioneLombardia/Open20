<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use open20\amos\videoconference\AmosVideoconference;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\videoconference\models\VideoconfSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$currentView = (isset($currentView)) ? $currentView : Yii::$app->request->getQueryParam('currentView');
$community_id = Yii::$app->request->getQueryParam('id');
?>

<div class="videoconf-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <p class="h3"><?= AmosVideoconference::t('amosvideoconference', 'Order by') ?>:</p>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'default-form2'
        ]
    ]);

    echo Html::hiddenInput("currentView", $currentView);
    echo Html::hiddenInput("VideoconfSearch[title]", Yii::$app->request->getQueryParam('VideoconfSearch')['title']);
    ?>

    <div class="col-sm-6">
        <?= $form->field($model, 'orderAttribute')->dropDownList(['videoconf.begin_date_hour' => 'Data di inizio'])
            ->label(AmosVideoconference::t('amosvideoconference', 'Order attribute')) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosVideoconference::t('amosvideoconference', 'Ascending'),
                SORT_DESC => AmosVideoconference::t('amosvideoconference', 'Descending'),
            ]
        )
            ->label(AmosVideoconference::t('amosvideoconference', 'Order type'));
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosVideoconference::t('amosvideoconference', 'Cancel'), ['index'],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosVideoconference::t('amosvideoconference', 'Sort'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>