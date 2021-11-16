<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */

use open20\amos\core\helpers\Html;
use yii\bootstrap4\ActiveForm;
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
use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\layout\utility\AmosIconsBi;



/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventContainer $model
 * @var yii\widgets\ActiveForm $form
 */

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);


if (!$model->isNewRecord) {
    $idContainer = $model->id;
    $js = <<<JS
$('#luya-template-id').click(function(){
     var value = $("input[name='EventContainer[luya_template_id]']:checked"). val();
         $.ajax({
               url: '/events/event-dashboard/get-preview-landing',
               type: 'get',
               data: {
                    'id': $idContainer
                    'nav_id' : value
               },
               success: function (data) {
                  $('#preview-template').html(data);
               }
           });
});
JS;
//    $this->registerJs($js);
}

?>
<div class="event-container-form col-xs-12 nop">
    <?php
    if (empty($model->event_group_referent_id)) {
        $currentGroup = \open20\amos\events\utility\EventsUtility::getCurrentDg();
        $query = \open20\amos\events\models\Event::find()->andWhere(['event_group_referent_id' => $currentGroup->id]);
    } else {
        $query = \open20\amos\events\models\Event::find()->andWhere(['event_group_referent_id' => $model->event_group_referent_id]);
    }
    ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-container_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-md-8 col xs-12"><!-- title string -->
            <?php
            $beginDate = ($model->begin_date) ? new \DateTime($model->begin_date) : null;
            $endDate = ($model->end_date) ? new \DateTime($model->end_date) : null;
            ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- description string -->
            <?= $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className()) ?><!-- luya_template_id integer -->
           <p><strong><?= AmosEvents::t('amosevents', 'Begin Date').'</strong>: '. ($beginDate ? $beginDate->format('d-m-Y h:i:s') : '')?></p>
           <p><strong><?= AmosEvents::t('amosevents', 'End Date').'</strong>: '. ($endDate ? $endDate->format('d-m-Y h:i:s') : '')?></p>
            <?=
            $form->field($model, 'publish_on_prl')->widget(\kartik\widgets\SwitchInput::className(), [
                'options' => ['id' => 'switch-settings'],
                'pluginOptions' => [
                    //'size' => 'mini',
                    //                    'onText' => AmosIcons::show('check-circle'),
                    //                    'offText' => AmosIcons::show('circle-o')
                    'onText' => 'Si',
                    'offText' => 'No'
                ],
            ])->label(AmosEvents::t('amosevents', 'Publish on PRL'));
            ?>
            <p><strong><?= AmosEvents::t('amosevents', "Modifica url landing page: ") ?></strong></p>
            <div class="row">
                <div class="col-lg mb-3">
                    <?= \Yii::$app->params['platform']['frontendUrl'] . '/it/' ?>
                </div>
                <div class="col-lg mb-3">
                    <?= $form->field($model, 'url')->label(false) ?>
                </div>
            </div>
            <?= $form->field($model, 'eventsConnected')->widget(\kartik\select2\Select2::className(), [
                'data' => ArrayHelper::map($query->all(), 'id', 'title'),
                'options' => [
                    'placeholder' => AmosEvents::t('amosevents', "Select...")
                ],
                'pluginOptions' => [
                    'multiple' => true
                ]
            ])->label(AmosEvents::t('amosevents', 'Events')) ?>
            <div class="col-md-8">
                <div class="input-color-landing">
                    <?= $form->field($model, 'luya_template_id')
                        ->radioList($arrayTemplates, ['id' => 'luya-template-id'])
                        ->label("<strong>" . \open20\amos\events\AmosEvents::t('amosevents', "Scegli il Template") . "</strong>") ?>
                </div>
                <div id="preview-template">
                    <?php
                    if (!empty($model->luya_template_id)) {
//                        echo $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_preview_landing', ['model' => $model, 'eventLanding' => $eventLanding, 'htmlTemplate' => $htmlTemplate]);
                        //                        pr($htmlTemplate[$eventLanding->luya_template_id]);
                        //                        echo $htmlTemplate[$eventLanding->luya_template_id];
                    }
                    ?>
                </div>
            </div>
            <div class="d-none"></div>

            <?php if (!$model->isNewRecord) { ?>
                <?php
                $max = \open20\amos\events\models\Event::find()->andWhere(['event_container_id' => $model->id])->max("event.event_container_order");
                $min = \open20\amos\events\models\Event::find()->andWhere(['event_container_id' => $model->id])->min("event.event_container_order");
                $containerModel = $model;
                ?>
                <div class="col-md-12">
                    <h5><?= AmosEvents::t('amosevents', "Ordina eventi") ?></h5>
                    <?= \open20\amos\core\views\AmosGridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'label' => 'Ordina',
                                'value' => function ($model) use ($max, $min, $containerModel, $spriteAsset) {

                                    $buttons = '';
                                    if ($model->event_container_order != $min) {
                                        $buttons .= Html::a(
                                            AmosIcons::show('long-arrow-up'),
                                            [
                                                '/events/event-container/order', 'id' => $model->id,
                                                'event_container_id' => $containerModel->id,
                                                'direction' => 'up',
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-icon',
                                                'data-toggle' => 'tooltip',
                                                'title' => AmosEvents::t('amosevents', 'Sposta sopra'),
                                                'data-confirm' => AmosEvents::t('amosevents', "Salvare i dati prima di effettuare l'operazione")
                                            ]
                                        );
                                    }
                                    if ($model->event_container_order != $max) {
                                        $buttons .= Html::a(
                                            AmosIcons::show('long-arrow-down'),
                                            [
                                                '/events/event-container/order', 'id' => $model->id,
                                                'event_container_id' => $containerModel->id,
                                                'direction' => 'down'
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-icon',
                                                'data-toggle' => 'tooltip',
                                                'title' => AmosEvents::t('amosevents', 'Sposta sotto'),
                                                'data-confirm' => AmosEvents::t('amosevents', "Salvare i dati prima di effettuare l'operazione")

                                            ]
                                        );
                                    }
                                    return $buttons;
                                },
                                'format' => 'raw'
                            ],
                            'title',
                            [
                                    'class' => \yii\grid\ActionColumn::className(),
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function($url, $model){
                                        $url = ['/events/event-dashboard/view',  'id' => $model->id];
                                        return Html::a(AmosIconsBi::show('ic_edit'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' =>  AmosEvents::t('amosevents','Evento'),
                                            'data-confirm' => 'Prima di lasciare la pagina assicurati di aver salvato i dati']);

                                    }
                                    ]
                            ]
                        ]
                    ]) ?>
                </div>
            <?php } ?>

            <div class="buttons">
                <?= \yii\helpers\Html::a(AmosEvents::t('amosevents', 'Back'), ['/events/event-container/index'], ['class' => 'btn btn-secondary']) ?>
                <?= \yii\helpers\Html::submitButton(\open20\amos\events\AmosEvents::t('amosevents', 'Save'), [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

        </div>
        <div class="col-md-4">
            <div class="col-md-12">
                <?=
                $form->field($model, 'mainImage')->widget(\open20\amos\attachments\components\CropInput::classname(),
                    [
                        'jcropOptions' => ['aspectRatio' => '1.7']
                    ])->label(AmosEvents::t('amosevents', 'Immagine'))
                ?>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
