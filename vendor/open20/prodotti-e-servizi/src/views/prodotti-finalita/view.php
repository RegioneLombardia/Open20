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
* @var open20\prodottiservizi\models\ProdottiFinalita $model
*/

$this->title = strip_tags($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti-e-Servizi'), 'url' => ['index']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti e Servizi'), 'url' => ['prodotti-servizi/index']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Finalità'), 'url' => ['prodotti-finalita/index']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="prodotti-finalita-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]) ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
