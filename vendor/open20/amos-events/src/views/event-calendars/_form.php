<?php
/**
 * Aria S.p.A.
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
use \open20\amos\events\AmosEvents;
use \open20\amos\core\views\AmosGridView;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventCalendars $model
 * @var yii\widgets\ActiveForm $form
 */

$this->registerJs("
            $('#event-calendars-date_start" . ((isset($fid)) ? $fid : 0) . "').change(function(){
        if($('#event-calendars-date_start" . ((isset($fid)) ? $fid : 0) . "').val() == ''){
        $('#event-calendars-date_start" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date');
        } else {
        if($('#event-calendars-date_start" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
        $('#event-calendars-date_start" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
        initDPRemove('event-calendars-date_start" . ((isset($fid)) ? $fid : 0) . "-disp');
        }
        }
        });
            $('#event-calendars-date_end" . ((isset($fid)) ? $fid : 0) . "').change(function(){
        if($('#event-calendars-date_end" . ((isset($fid)) ? $fid : 0) . "').val() == ''){
        $('#event-calendars-date_end" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date');
        } else {
        if($('#event-calendars-date_end" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
        $('#event-calendars-date_end" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
        initDPRemove('event-calendars-date_end" . ((isset($fid)) ? $fid : 0) . "-disp');
        }
        }
        });
        ", yii\web\View::POS_READY);


$hasUserBookedSlot = $model->hasUserBookedSlot(\Yii::$app->user->id);
?>
<div class="event-calendars-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-calendars_' . ((isset($fid)) ? $fid : 0),
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
            <div class="col-md-12 col xs-12">

                <?php if ($model->event_id) { ?>
                    <div class="col-xs-12 nop m-t-20">
                        <p>
                            <strong><?= \open20\amos\events\AmosEvents::t('amosevents', 'Event') ?></strong>: <?= $event->title ?>
                        </p>
                    </div>
                <?php } else { ?>
                    <?= $form->field($model, 'event_id')->widget(Select::classname(), [
                        'data' => ArrayHelper::map(\open20\amos\events\models\Event::find()->asArray()->all(), 'id', 'title'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'id' => 'Event0' . $fid,
                            'multiple' => false,
                            'placeholder' => 'Seleziona ...',
                            'data-model' => 'event',
                            'data-field' => 'title',
                            'data-module' => 'amosevents',
                            'data-entity' => 'event',
                            'data-toggle' => 'tooltip'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label(\open20\amos\events\AmosEvents::t('amosevents', 'Event'));
                }
                ?>
                <?php if (\Yii::$app->user->can('ADMIN')) { ?>
                    <div class="col-xs-6">
                        <?= $form->field($model, 'classname')->widget(\kartik\select2\Select2::className(), [
                            'data' => \open20\amos\events\models\EventCalendars::getAvailableModel(),
                            'options' => ['placeholder' => 'Select...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],

                        ]) ?></div><!-- description text -->
                    <div class="col-xs-6">
                        <?= $form->field($model, 'record_id')->textInput(['maxlength' => true]) ?><!-- description text -->
                    </div>
                <?php } ?>
                <!-- title string -->
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- description text -->
                <?= $form->field($model, 'description')->textarea(['rows' => 5]); ?>
                <?= $form->field($model, 'short_description')->textarea(['rows' => 3]); ?>
                <?php if(empty($model->max_participant) ){
                    $model->max_participant = 10;
                }?>
                <?= $form->field($model, 'max_participant')->textInput(['maxlength' => true]) ?><!-- description text -->


                <div class="col-xs-6 nop">
                    <?= $form->field($model, 'group') ?>
                </div>

                <!--                <div class="col-xs-6">-->
                <!--                    --><?php //$form->field($model, 'ecosystem') ?>
                <!--                </div>-->


                <div class="col-xs-12 nop">
                    <div class="col-xs-6 nop">
                        <?php
                        $partner = '';
                        $userProfilePartner = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $model->partner_user_id])->one();
                        if (!empty($userProfilePartner)) {
                            $partner = $userProfilePartner->getNomeCognome();
                        }
                        echo $form->field($model, 'partner_user_id')->widget(\kartik\select2\Select2::className(), [
                                'data' => (!empty($model->partner_user_id) ? [$model->partner_user_id => $partner] : []),
                                'options' => ['placeholder' => AmosEvents::t('amosevents', 'Cerca ...')],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::to(['/admin/user-profile-ajax/ajax-user-list']),
                                        'dataType' => 'json',
                                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ],
                            ]
                        )->hint(AmosEvents::t('amosevents', 'This user will receive the emails for the calendar'));
                        ?>
                    </div>
                </div>


                <div class="row nop">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'date_start')->widget(DateControl::classname(), [
                            'options' => [
                                'id' => lcfirst(Inflector::id2camel(\yii\helpers\StringHelper::basename($model->className()), '_')) . '-date_start' . ((isset($fid)) ? $fid : 0),
                                'layout' => '{input} {picker} ' . (($model->date_start == '') ? '' : '{remove}')]
                        ]); ?><!-- date_end date -->
                    </div>

                    <!--                    <div class="col-xs-6">-->
                    <!--                        --><?php //echo $form->field($model, 'date_end')->widget(DateControl::classname(), [
                    //                            'options' => [
                    //                                'id' => lcfirst(Inflector::id2camel(\yii\helpers\StringHelper::basename($model->className()), '_')) . '-date_end' . ((isset($fid)) ? $fid : 0),
                    //                                'layout' => '{input} {picker} ' . (($model->date_end == '') ? '' : '{remove}')]
                    //                        ]); ?>
                    <!--                    </div>-->
                </div>

                <div class="row nop">
                    <div class="col-xs-12">
                        <h3><?= AmosEvents::t('amosevents', 'Fascia oraria') ?></h3>
                    </div>
                    <div class="col-xs-4">
                        <?= $form->field($model, 'hour_start')->widget(DateControl::classname(), [
                            'type' => DateControl::FORMAT_TIME
                        ]) ?><!-- hour_end datetime -->
                    </div>

                    <div class="col-xs-4">
                        <?= $form->field($model, 'hour_end')->widget(DateControl::classname(), [
                            'type' => DateControl::FORMAT_TIME
                        ]) ?><!-- slot_duration integer -->
                    </div>

                    <div class="col-xs-2">
                        <?= $form->field($model, 'slot_duration')
                            ->textInput()
                            ->hint(AmosEvents::t('amosevents', 'Duration in minutes')) ?>
                    </div>
                    <div class="col-xs-2">
                        <?= $form->field($model, 'break_time')
                            ->textInput()
                            ->label(AmosEvents::t('amosevents', 'Durata pausa'))
                            ->hint(AmosEvents::t('amosevents', 'Duration in minutes')) ?>
                    </div>
                </div>

                <?php if (!$model->isNewRecord) { ?>
                    <div class="col-xs-12">
                        <h3><?= AmosEvents::t('amosevents', 'Slots') ?></h3>
                        <?= AmosGridView::widget([
                            'dataProvider' => $dataProviderSlots,
                            'formatter' => [
                                'class' => 'yii\i18n\Formatter',
                                'nullDisplay' => '-'
                            ],
                            'columns' => [
                                [
                                    'attribute' => 'date',
                                    'format' => 'date'
                                ],
                                [
                                    'attribute' => 'hour_start',
                                    'format' => 'time'
                                ],
                                [
                                    'attribute' => 'hour_end',
                                    'value' => function ($model) {
                                        return $model->getEndHourWithPause();
                                    },
                                ],
                                [
                                    'value' => function($model){
                                        $available = $model->eventCalendars->max_participant - $model->getEventCalendarsSlotsBooked()->count();
                                        if($available == 0){
                                            return AmosEvents::t('amsoevents', 'Posti esauriti');
                                        }
                                        return $available;

                                    },
                                    'label' => AmosEvents::t('amsoevents', 'Posti disponibili')
                                ],
                                [
                                    'attribute' => 'user.userProfile.nomeCognome',
                                ],
                                [
                                    'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                    'controller' => 'event-calendars-slots',
                                    'template' => '{users}{unbook}{book}{delete}',
                                    'buttons' => [
                                        'users' => function ($url, $model) use ($canViewUser) {
                                            if ($canViewUser) {
                                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('accounts'),
                                                    ['/events/event-calendars-slots/booked-users', 'id' => $model->id, 'url' => \Yii::$app->getView()->params['urlget']],
                                                    [
                                                        'class' => 'btn btn-primary', //                                            'data-toggle' => 'modal',
//                                            'data-target' => '#modal-extra-info',
                                                        'title' => AmosEvents::t('amosevents', "Mostra prenotazioni"),
                                                    ]);
                                            }
                                        },
                                        'book' => function ($url, $model) use ($hasUserBookedSlot) {
                                            if ($model->canBook()) {
                                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('calendar-check-o',
                                                    [], 'dash'),
                                                    ['/events/event-calendars-slots/book-slot', 'id' => $model->id, 'url' => \Yii::$app->getView()->params['urlget']],
                                                    [
                                                        'class' => 'btn btn-primary button-book-slot',
                                                        'data-key' => $model->id,
//                                            'data-toggle' => 'modal',
//                                            'data-target' => '#modal-extra-info',
                                                        'title' => AmosEvents::t('amosevents', "Prenota appuntamento"),
                                                    ]);
                                            }
                                        },

                                        'unbook' => function ($url, $model) {
//                                            if (!empty($model->user_id) && ($model->user->id == \Yii::$app->user->id || \Yii::$app->user->can('ADMIN'))) {
                                            if ($model->isBookedByUser(\Yii::$app->user->id)) {
                                                return \yii\helpers\Html::a(AmosIcons::show('close'), ['/events/event-calendars-slots/unbook-slot', 'id' => $model->id], [
                                                    'class' => 'btn btn-primary',
                                                    'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di voler annullare l'appuntamento?"),
                                                    'title' => AmosEvents::t('amosevents', "Annulla appuntamento"),
                                                ]);
                                            }
                                        },
                                        'delete' => function ($url, $model) {
                                            $count = $model->getEventCalendarsSlotsBooked()->count();

                                            if ($count == 0) {
                                                return \yii\helpers\Html::a(AmosIcons::show('delete'), $url . '&redirectUrl=' . urlencode(\Yii::$app->request->absoluteUrl), [
                                                    'class' => 'btn btn-danger-inverse',
                                                    'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di eliminare questo slot?"),
                                                    'title' => AmosEvents::t('amosevents', "Elimina slot"),
                                                ], true);
                                            }
                                        }
                                    ]
                                ]
                            ]
                        ]) ?>
                    </div>
                <?php } ?>

                <?= RequiredFieldsTipWidget::widget(); ?>

                <?= CloseSaveButtonWidget::widget([
                    'model' => $model,
                    'urlClose' => '/events/event/view?id=' . $model->event_id . '#tab-calendars'
                ]); ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>

<?php echo $this->render('_modal_additional_info');
