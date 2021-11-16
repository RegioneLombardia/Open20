<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/compains/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\campains\models\PreferenceCampain $model
*/

use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Preference Campain',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>




<div class="preference-campain-update">

    <?php $form = ActiveForm::begin(['options' => [ 'class' => 'needs-validation' ]]); ?>  

    <!-- < ?= $form->errorSummary($model) ?> -->

    <!-- FORM CONTENT -->
    <?= 
        $this->render('../../../../landings/views/preference-landing/parts/_form_landing_page', [
            'model' => $model,
            'form' => $form,
            'dataProvider' => $dataProvider,
        ])
    ?>

    <?php ActiveForm::end(); ?>


</div>
