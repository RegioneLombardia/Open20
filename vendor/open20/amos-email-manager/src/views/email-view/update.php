<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-email-manager/src/views
 */
/**
 * @var yii\web\View $this
 * @var open20\amos\emailmanager\models\EmailView $model
 */

$this->title = Yii::t('amoscore', 'Aggiorna {type} di {module}', [
    'type' => $model->type,
    'module' => $model->module
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/emailmanager']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Email View'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="email-view-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
