<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views
 */

/**
 * @var yii\web\View $this
 * @var open20\prodottiservizi\models\ProdottiServiziAccordion $model
 */

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Prodotti Servizi Accordion',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/prodottiservizi']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Prodotti Servizi Accordion'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="prodotti-servizi-accordion-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
