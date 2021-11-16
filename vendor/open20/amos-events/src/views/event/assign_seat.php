<?php
/** @var $modelForm \open20\amos\events\models\FormAssignSeat
 * @var $model \open20\amos\events\models\Event
 */

use open20\amos\events\AmosEvents;

if(!empty($eventCompanion)){
    $this->title = AmosEvents::t('amosevents', 'Assegna posto ad utente') . ' ' . $eventCompanion->nome. ' '.$eventCompanion->cognome;
}else {
    $this->title = AmosEvents::t('amosevents', 'Assegna posto ad utente') . ' ' . $user->userProfile->nomeCognome;
}

$form = \open20\amos\core\forms\ActiveForm::begin(); ?>
    <h3><?= AmosEvents::t('amosevents', 'Totale posti da assegnare:') . ' ' . $n_seats_to_assign ?></h3>
    <div class="col-xs-3">
        <?php echo $form->field($modelForm, 'sector')->widget(\kartik\select2\Select2::className(), [
            'data' => \yii\helpers\ArrayHelper::map($model->getSectors(), 'sector', 'sector'),
            'options' => [
                'id' => 'sector_id',
                'placeholder' => AmosEvents::t('amosevents', 'Select...')]
        ])->label(AmosEvents::t('amosevents', 'Settore'));
        ?>
    </div>
    <div class="col-xs-3">
        <?php echo \yii\helpers\Html::hiddenInput('event_id', $model->id, ['id' => 'event_id']) ?>
        <?php echo $form->field($modelForm, 'row')->widget(\kartik\depdrop\DepDrop::className(), [
            'options' => ['id' => 'row_id'],
            'pluginOptions' => [
                'depends' => ['sector_id'],
                'placeholder' => 'Select...',
                'url' => \yii\helpers\Url::to(['get-rows-ajax']),
                'params' => ['event_id']

            ]
        ])->label(AmosEvents::t('amosevents', 'Fila'));
        ?>
    </div>
    <div class="col-xs-3">
        <?php echo $form->field($modelForm, 'seat')->widget(\kartik\depdrop\DepDrop::className(), [
            'options' => ['id' => 'seat_id'],
            'pluginOptions' => [
                'depends' => ['row_id'],
                'placeholder' => 'Select...',
                'url' => \yii\helpers\Url::to(['get-seats-ajax']),
                'params' => ['event_id', 'sector_id']
            ]
        ])->label(AmosEvents::t('amosevents', 'Posto'));
        ?>
    </div>
    <div class="col-xs-12">
        <?= \open20\amos\core\forms\CloseSaveButtonWidget::widget([
            'model' => $model
        ]) ?>
    </div>

<?php
\open20\amos\core\forms\ActiveForm::end();
?>