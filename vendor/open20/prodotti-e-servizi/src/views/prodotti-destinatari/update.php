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
* @var open20\prodottiservizi\models\ProdottiDestinatari $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Prodotti Destinatari',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/frontend']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Prodotti Destinatari'), 'url' => ['index']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Prodotti e Servizi', 'url' => ['/prodotti-e-servizi/prodotti-servizi']];
$this->params['forceBreadcrumbs'][] = ['label' => 'Destinatari', 'url' => ['/prodotti-e-servizi/prodotti-destinatari']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('amoscore', 'Aggiorna')];
?>
<div class="prodotti-destinatari-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
