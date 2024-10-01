<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
 * @var yii\web\View $this
 * @var open20\prodottiservizi\models\ProdottiServiziAccordion $model
 */

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/prodottiservizi']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Prodotti Servizi Accordion'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prodotti-servizi-accordion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description',
            'is_visible',
            'position',
            'prodotto_id',
        ],
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>