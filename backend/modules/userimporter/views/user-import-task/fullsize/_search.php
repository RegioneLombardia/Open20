<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */

use backend\modules\userimporter\Module;
use open20\amos\core\helpers\Html;
use kartik\datecontrol\DateControl;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\modules\userimporter\models\search\UserImporterTaskSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="user-import-task-search my-2 collapse" id="form-search-events">

    <?php
    $form = ActiveForm::begin([
            'action' => (isset($originAction) ? [$originAction] : ['index']),
            'method' => 'get',
            'options' => [
                'class' => 'default-form'
            ]
    ]);

    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>
    <div class="neutral-2-bg p-4">
        <div class="row">


            <div class="col-lg-12">
            <!-- name -->
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'ricerca per ' . Module::t('amosuserimporter','#name')]) ?>

            </div>

            <!-- task_date -->
            <div class="col-lg-6">
                <?= $form->field($model, 'task_date')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'layout' => '{input} {picker} ' . (($model->task_date == '') ? '' : '{remove}')
                    ]
                ]); ?>
            </div>


<?php if(Yii::$app->user->can('SPER_USER')){

?>
            <div class="col-lg-6">
                <?php
                // $form->field($model, 'eventGroupReferent')->textInput(['placeholder' => 'ricerca per ' . Module::t('amosuserimporter','#event_group_referent_id')])->label(Module::t('amosuserimporter','#event_group_referent_id'));
                ?>
            </div>

<?php }

?>
        <div class="col-12">
                <div class="float-right">
                    <?= Html::a(
                        Module::t('amosuserimporter', 'Cancel'),
                        [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                        ['class' => 'btn btn-xs']
                    ) ?>
                    <?= Html::submitButton(Module::t('amosuserimporter', 'Search'), ['class' => 'btn btn-xs btn-outline-primary']) ?>
                </div>
         </div>

                <div class="clearfix"></div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
