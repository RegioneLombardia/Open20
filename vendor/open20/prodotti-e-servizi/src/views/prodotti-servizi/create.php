<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views 
 */
/**
* @var yii\web\View $this
* @var open20\prodottiservizi\models\ProdottiServizi $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Prodotti Servizi',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/index']];

$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti e Servizi'), 'url' => ['index']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="prodotti-servizi-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'solutions' => $solutions,
        'finalita' => $finalita,
        'destinatari' => $destinatari,
        'dataProviderAccordion' => $dataProviderAccordion,
        'currentViewAccordion' => $currentViewAccordion
    ]) ?>

</div>
