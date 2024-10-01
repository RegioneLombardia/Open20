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
* @var open20\prodottiservizi\models\ProdottiFinalita $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Prodotti Finalita',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/frontend']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Prodotti Finalita'), 'url' => ['index']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Prodotti e Servizi', 'url' => ['/prodotti-e-servizi/prodotti-servizi']];
$this->params['forceBreadcrumbs'][] = ['label' => 'Finalità', 'url' => ['/prodotti-e-servizi/prodotti-finalita']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('amoscore', 'Crea')];
?>
<div class="prodotti-finalita-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
