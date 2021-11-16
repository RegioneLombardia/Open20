<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/compains/views 
 */

use backend\modules\operators\models\Operator;
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\modules\campains\models\search\PreferenceCampainSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="preference-campain-search collapse mb-5 neutral-2-bg p-4" id="form-search-events">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') 
                ?>

    <!-- titolo -->
    <div class="row">
        <div class="col-md-4"> <?=
          $form->field($model, 'titolo')->textInput(['placeholder' => 'ricerca per titolo']) ?>

        </div>
	    <div class="col-md-4"> <?=
            $form->field($model, 'creatore')->textInput(['placeholder' => 'ricerca per Utente creatore']) ?>

        </div>
        <?php
        $op = Operator::findOne(['user_id' => Yii::$app->user->id]);
        if (!empty($op) && (empty($op->pc_structure_id))):
        ?>
	    <div class="col-md-4">
            <?php
            echo $form->field($model, 'struttura')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\modules\operators\models\PreferenceStructure::find()->all(), 'id', 'name'),
                'options' => [
                    'multiple' => false,
                    'placeholder' => 'Ricerca per struttura'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>
        <?php
        endif;
        ?>
	    <div class="col-md-4">
            <?= $form->field($model, 'created_at')->widget(\kartik\datecontrol\DateControl::classname(), [
                'options' => ['layout' => '{input} {picker} ' . (($model->created_at == '') ? '' : '{remove}')]
            ])->label('Data di creazione'); ?>
	    </div>

        <!-- status -->
        <div class="col-md-4">
            <?php
            echo $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\cornernote\workflow\manager\models\Status::find()->andWhere(['workflow_id' => \backend\modules\campains\models\PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW])->all(), 'id', 'label'),
                'options' => [
                        'multiple' => false,
                    'placeholder' => 'Ricerca per stato'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Stato'); ?>

        </div>
    </div>

    <div class="clearfix">

        <div class="float-right">
            <?= Html::a(Yii::t('amoscore', 'Reset'),[''] ,['class' => 'btn btn-xs']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-xs btn-outline-primary']) ?>
        </div>

    </div>



    <?php ActiveForm::end(); ?>
</div>