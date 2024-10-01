<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-sedi
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\organizzazioni\Module;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\organizzazioni\models\ProfiloSedi $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Module::t('amosorganizzazioni', 'Profilo Sedi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="profilo-sedi-view col-xs-12">
    <div class="row">
        <div class="col-xs-12 m-t-5 m-b-5">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'description:ntext',
                    [
                        'attribute' => 'profiloSediType.name',
                        'label' => $model->getAttributeLabel('profiloSediType')
                    ],
                    'addressField:raw',
                    'phone',
                    'fax',
                    'email:email',
                    'pec:email',
                    [
                        'attribute' => 'profilo.name',
                        'label' => $model->getAttributeLabel('profilo')
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::a(Module::t('amoscore', 'Chiudi'), Yii::$app->getUser()->getReturnUrl(), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
