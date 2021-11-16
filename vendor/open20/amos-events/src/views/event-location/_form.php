<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventLocation $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="event-location-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-location_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-8 col xs-12"><!-- name string -->
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description string -->
                <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?><!-- event_place_id string -->
                <?= $form->field($model, 'event_place_id')->widget(
                    \open20\amos\events\widgets\maps\PlaceWidget::className(), [
                        'placeAlias' => 'eventPlaces'
                    ]
                )->label(\open20\amos\events\AmosEvents::t('amosevents',"Address")); ?>

                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::a(\open20\amos\events\AmosEvents::t('amosevents', "Aggiungi ingresso"), ['/events/event-location-entrances/create', 'idLocation' => $model->id], [
                        'class' => 'btn btn-primary'
                    ]) ?>
                    <?= \open20\amos\core\views\AmosGridView::widget([
                        'dataProvider' => $dataProviderEntrance,
                        'columns' => [
                            'name',
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                'controller' => 'event-location-entrances',
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Html::a(AmosIcons::show('delete'), ['/events/event-location-entrances/delete', 'id' => $model->id], [
                                            'class' => 'btn btn-danger-inverse'
                                        ]);
                                    }


                                ]

                            ]
                        ]
                    ]) ?>
                <?php } else {
                    echo "<p>" . \open20\amos\events\AmosEvents::t('amosevents', "Per inserire un ingresso bisogna prima salvare la location") . "</p>";
                } ?>
                <?= RequiredFieldsTipWidget::widget(); ?>

                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
                <?php ActiveForm::end(); ?></div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>


    </div>
</div>
