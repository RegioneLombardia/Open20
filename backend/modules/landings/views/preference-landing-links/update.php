<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\landings\models\PreferenceLandingLink $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Preference Landing Link',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Landing Link'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="preference-landing-link-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
