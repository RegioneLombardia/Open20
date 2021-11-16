<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views
 */

use backend\modules\operators\models\Operator;
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\modules\landings\models\search\PreferenceLandingSearch $model
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

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- preference_structure_id -->
    <div class="row">
        <div class="col-md-4"> <?=
            $form->field($model, 'title')->textInput(['placeholder' => 'Ricerca per titolo']) ?>

        </div>

        <?php
        $op = Operator::findOne(['user_id' => Yii::$app->user->id]);
        if (!empty($op) && (empty($op->pc_structure_id))):
        ?>
        <div class="col-md-4"> <?=
                 $form->field($model, 'preference_structure_id')->widget(\kartik\select2\Select2::className(), [
                     'data' => \yii\helpers\ArrayHelper::map(\backend\modules\operators\models\PreferenceStructure::find()->all(), 'id', 'name'),
                     'options' => ['placeholder' =>  \Yii::t("preferencelandings","Seleziona...")]
                 ])->label(\Yii::t("preferencelandings","Struttura"))?>


        </div>
        <?php
        endif;
        ?>

        <!-- status -->
        <div class="col-md-4"> <?=
            $form->field($model, 'statusSearch')->widget(\kartik\select2\Select2::className(), [
                'data' => [
                    \backend\modules\landings\models\PreferenceLanding::PREFERENCE_LANDING_STATUS_DRAFT => \Yii::t("preferencelandings", "Bozza"),
                    \backend\modules\landings\models\PreferenceLanding::PREFERENCE_LANDING_STATUS_PUBLISHED => \Yii::t("preferencelandings", "Pubblicata"),
                ],
                'options' => ['placeholder' =>  \Yii::t("preferencelandings","Seleziona...")]
            ])->label(\Yii::t("preferencelandings","Stato"))?>

        </div>
	    <div class="col-md-4">
            <?= $form->field($model, 'created_at')->widget(\kartik\datecontrol\DateControl::classname(), [
                'options' => ['layout' => '{input} {picker} ' . (($model->created_at == '') ? '' : '{remove}')]
            ])->label('Data di creazione'); ?>
	    </div>
	    <div class="col-md-4"> <?=
            $form->field($model, 'creatore')->textInput(['placeholder' => 'Ricerca per Utente creatore']) ?>

	    </div>
    </div>


<!--    <div class="row">-->
<!--        -->
<!--        <div class="col-md-4"> --><?php
//            $form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description']) ?>
<!---->
<!--        </div>-->
<!---->
<!--        <div class="col-md-4"> --><?php
//            $form->field($model, 'schedule')->textInput(['placeholder' => 'ricerca per schedule']) ?>
<!---->
<!--        </div>-->
<!--    </div>-->


    <!-- created_at --> <?php // echo $form->field($model, 'created_at') ?>

    <!-- updated_at --> <?php // echo $form->field($model, 'updated_at') ?>

    <!-- deleted_at --> <?php // echo $form->field($model, 'deleted_at') ?>

    <!-- created_by --> <?php // echo $form->field($model, 'created_by') ?>

    <!-- updated_by --> <?php // echo $form->field($model, 'updated_by') ?>

    <!-- deleted_by --> <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Yii::t('amoscore', 'Reset'),[''] ,['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
