<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\operators\models\AgidPerson $model
*/


// $this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/person']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Persona'), 'url' => ['index']];
// //$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');


$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Agid Person',
]);
$this->params['breadcrumbs'][] = ['label' => \Yii::$app->session->get('previousTitle'), 'url' => \Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="agid-person-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
