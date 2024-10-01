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
* @var open20\prodottiservizi\models\ProdottiSoluzioni $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Prodotti Soluzioni',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/frontend']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Prodotti Soluzioni'), 'url' => ['index']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Prodotti e Servizi', 'url' => ['/prodotti-e-servizi/prodotti-servizi']];
$this->params['forceBreadcrumbs'][] = ['label' => 'Soluzioni', 'url' => ['/prodotti-e-servizi/prodotti-soluzioni']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('amoscore', 'Crea')];
?>
<div class="prodotti-soluzioni-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
