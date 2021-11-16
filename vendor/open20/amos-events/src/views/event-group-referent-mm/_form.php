<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/events/src/views 
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
 * @var open20\amos\events\models\EventGroupReferentMm $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="event-group-referent-mm-form col-xs-12 nop">

    <?php
    $form = ActiveForm::begin([
            'options' => [
                'id' => 'event-group-referent-mm_'.((isset($fid)) ? $fid : 0),
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
            <div class="col-md-8 col xs-12">
                <?php
                if (\Yii::$app->getUser()->can('USER_CREATE')) {
                    $append = ' canInsert';
                } else {
                    $append = NULL;
                }
                ?>
                <?=
                $form->field($model, 'user_id')->widget(Select::classname(),
                    [
                    'data' => ArrayHelper::map(open20\amos\events\models\EventGroupReferentMm::getCandidatiOperatoriAndOperators($group_id)->all(),
                        'user_id', 'nomeCognome'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'User0'.$fid,
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                        'class' => 'dynamicCreation'.$append,
                        'data-model' => 'user',
                        'data-field' => 'email',
                        'data-module' => 'admin',
                        'data-entity' => 'user',
                        'data-toggle' => 'tooltip'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        "select2:open" => "dynamicInsertOpening"
                    ]
                ])->label('Utente')
                ?>                        <?php
                if (\Yii::$app->getUser()->can('EVENTGROUPREFERENT_CREATE')) {
                    $append = ' canInsert';
                } else {
                    $append = NULL;
                }
                ?>
                <?php
                $model->exclude_from_query = 1;
                ?>
                <?=
                $form->field($model, 'exclude_from_query', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false);
                ?>
                <?php
                if (!empty($group_id)) {
                    $model->event_group_referent_id = $group_id;
                    echo $form->field($model, 'event_group_referent_id', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false);
                } else {
                    ?>

                    <?=
                    $form->field($model, 'event_group_referent_id')->widget(Select::classname(),
                        [
                        'data' => ArrayHelper::map(open20\amos\events\models\EventGroupReferent::find()->asArray()->all(),
                            'id', 'denominazione'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'id' => 'EventGroupReferent0'.$fid,
                            'multiple' => false,
                            'placeholder' => 'Seleziona ...',
                            'class' => 'dynamicCreation'.$append,
                            'data-model' => 'event_group_referent',
                            'data-field' => 'denominazione',
                            'data-module' => 'events',
                            'data-entity' => 'event-group-referent',
                            'data-toggle' => 'tooltip'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'pluginEvents' => [
                            "select2:open" => "dynamicInsertOpening"
                        ]
                    ])->label('Gruppo DG')
                    ?>
                <?php } ?>
                <?= RequiredFieldsTipWidget::widget(); ?>
                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
                <?php ActiveForm::end(); ?>
            </div>

        </div>
        <div class="clearfix"></div>

    </div>
</div>
