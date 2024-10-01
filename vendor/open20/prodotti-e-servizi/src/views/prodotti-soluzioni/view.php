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
use open20\prodottiservizi\Module;

/**
* @var yii\web\View $this
* @var open20\prodottiservizi\models\ProdottiSoluzioni $model
*/

$this->title = strip_tags($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti-e-Servizi'), 'url' => ['index']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti e Servizi'), 'url' => ['prodotti-servizi/index']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Soluzioni'), 'url' => ['prodotti-soluzioni/index']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="prodotti-soluzioni-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'icon' => [
                'label' => Module::t('prodottiservizi', 'Icon'),
                'format' => 'html',
                'value' => function($model) {
                    return '<span class="mdi mdi-' . $model->icon . '"></span>';
                }
            ]
        ],
    ]) ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
