<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\site-management-element
 * @category   CategoryName
 */

use amos\sitemanagement\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementElement $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Element'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-element-view col-xs-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'template_id',
            'title',
            'description:ntext',
            'elem_order',
            [
                'attribute' => 'created_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            [
                'attribute' => 'deleted_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            'created_by',
            'updated_by',
            'deleted_by',
        ],
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'),  (\Yii::$app->request->referrer ?: \Yii::$app->session->get('previousUrl')), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
