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

$this->title = Yii::t('prodottiservizi', 'Aggiorna', [
    'modelClass' => 'Prodotti Servizi',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/index']];

$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti e Servizi'), 'url' => ['index']];
$this->params['forceBreadcrumbs'][] = ['label' => \open20\prodottiservizi\utility\ProdottiServiziUtility::getAmbitoLabel($model->scope), 'url' => ['index', 'ProdottiServiziSearch[scope]' => $model->scope]];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="prodotti-servizi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
        'solutions' => $solutions,
        'destinatari' => $destinatari,
        'finalita' => $finalita,
        'dataProviderAccordion' => $dataProviderAccordion,
        'currentViewAccordion' => $currentViewAccordion
    ]) ?>

</div>
