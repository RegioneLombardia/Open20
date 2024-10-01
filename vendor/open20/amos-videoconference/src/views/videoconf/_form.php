<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\videoconference\models\Videoconf;
use open20\amos\videoconference\AmosVideoconference;

/**
 * @var yii\web\View $this
 * @var open20\amos\videoconference\models\Videoconf $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<?php

$action = Yii::$app->controller->action->id;
$js = <<<JS
/*
    $(".schedule-videoconference").click(function(){
        if($(this).is(':checked')) {
           $('#date-hour-container').show();
        }
        else {
             $('#date-hour-container').hide();
             $('#_date_begin-disp').val('');
             $('#_date_begin').val('');
             $('#_date_end').val('');
             $('#_date_end-disp').val('');
        }
    });
 */
 
    var yiiAction = '{$action}';
    var minutes = '{$model->notification_before_conference}';
    $(document).ready(function() {
        disableStartHour();
        if(yiiAction === 'create') {
            $('#videoconf-start_hour-disp').val(getTimeNow());
            $('#videoconf-notification_before_conference-disp').val('00:00').change();
        } else {
            $('#videoconf-notification_before_conference-disp').val(getTimeFromMinutes(minutes)).change();
        }
    });
    
    $(document).on('change', '#videoconf-start_date-disp', function(){
        disableStartHour();
    });
    
    function disableStartHour() {
        if($('#videoconf-start_date-disp').val() != '') {
            if(yiiAction === 'create') {
                $('#videoconf-start_hour-disp').val(getTimeNow()).change();
            }
            $('#videoconf-start_hour-disp').prop('disabled', false);
        } else {
            $('#videoconf-start_hour-disp').prop('disabled', true);
        }
    }
    
    function getTimeNow() {
        const now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        hours = hours < 10 ? '0'+hours : hours;
        minutes = minutes < 10 ? '0'+minutes : minutes;

        return hours+':'+minutes;
    }
    
    function getTimeFromMinutes(totalMinutes) {
        let hours = Math.floor(totalMinutes / 60);
        let minutes = totalMinutes - hours * 60;
        hours = hours < 10 ? '0'+hours : hours;
        minutes = minutes < 10 ? '0'+minutes : minutes;
        return hours + ':' + minutes;
    }
JS;

$this->registerJs($js);

?>
<div class="videoconf-form col-xs-12 nop">

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'videoconf_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : ''),
            'enctype' => 'multipart/form-data' // important
        ]
    ]);
    ?>

    <!--   DETTAGLIO  -->
    <div class="row m-b-15">
        <div class="col-xs-12">
            <?php
            if (!$model->isNewRecord) {
                echo Html::a(\open20\amos\core\icons\AmosIcons::show('accounts') .AmosVideoconference::t('amosvideoconference','Gestione partecipanti'),
                    ['/videoconference/videoconf/participants','id' => $model->id, 'redirectUrl' => '/videoconference/videoconf/update?id='.$model->id],
                    [
                        'class' => 'btn btn-primary pull-right',
                        'title' => AmosVideoconference::t('amosvideoconference','Gestione partecipanti'),
                        'data-confirm' =>  AmosVideoconference::t('amosvideoconference','Stai per lasciare la pagina, verifica prima di aver salvato i tuoi dati. Vuoi procedere?')
                    ]);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <?php if(empty($model->title)) $model->title = \open20\amos\videoconference\AmosVideoconference::t('amosvideoconference', 'Videocall #') . date('YmdHis'); ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'options' => [
                    'maxlength' => 100,
                    'placeholder' => AmosVideoconference::t('amosvideoconference', 'Inserisci...')
                ],
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]);
            ?>
        </div>

        <?php if($model->isNewRecord || $model->status == Videoconf::STATUS_FUTURE) {?>
        
            <!--<?= $form->field($model, 'sheduledVideoconfCheckBox')->checkbox(['class' => 'schedule-videoconference'])->label(\open20\amos\videoconference\AmosVideoconference::t('amosvideoconference', 'Programma videoconferenza'));?>-->
       

            <div id="date-hour-container" class="col-sm-12 col-md-4">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <?= $form->field($model, 'start_date', ['enableAjaxValidation' => true])->widget(DateControl::class, [
                            'type' => DateControl::FORMAT_DATE,
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <?= $form->field($model, 'start_hour', ['enableAjaxValidation' => true])->widget(DateControl::class, [
                            'type' => DateControl::FORMAT_TIME,
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <?= $form->field($model, 'notification_before_conference')->widget(DateControl::class, [
                            'type' => DateControl::FORMAT_TIME,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <?= CloseSaveButtonWidget::widget(['model' => $model, 'buttonNewSaveLabel'=>Yii::t('amosevents', 'Invita partecipanti')]); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>


