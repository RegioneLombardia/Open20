<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @open20/amos/collaborations/views/views
 */

use open20\amos\collaborations\models\CollaborationType;
use open20\amos\collaborations\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use Yii;
use yii\widgets\Pjax;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\collaborations\models\search\CollaborationProposalsSearch $model
 */

$this->title = Yii::t('amoscore', 'Collaboration Proposals');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/collaborations']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] =
    ['label' => Yii::t('amoscollaborations', 'Proposte di collaborazione'),
        'url' => ['/collaborations/collaboration-proposals/index']];
if (strpos(Url::current(), 'manage')) {
    $this->params['forceBreadcrumbs'][] =
        ['label' => Yii::t('amoscollaborations', 'Amministra')];
} elseif (strpos(Url::current(), 'to-publish')) {
    $this->params['forceBreadcrumbs'][] =
        ['label' => Yii::t('amoscollaborations', 'Da pubblicare')];
} elseif (strpos(Url::current(), 'closed')) {
    $this->params['forceBreadcrumbs'][] =
        ['label' => Yii::t('amoscollaborations', 'Chiuse')];
// } elseif (strpos(Url::current(), 'archived')) {
//     $this->params['forceBreadcrumbs'][] =
//         ['label' => Yii::t('amoscollaborations', 'Archiviate')];
} elseif (strpos(Url::current(), 'own-interest')) {
    $this->params['forceBreadcrumbs'][] =
        ['label' => Yii::t('amoscollaborations', 'Di mio interesse')];
} elseif (strpos(Url::current(), 'refused')) {
    $this->params['forceBreadcrumbs'][] =
        ['label' => Yii::t('amoscollaborations', '#many_refused')];
}
?>
<div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
</div>
<div class="collaboration-proposals-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>
    <!--< ?= $this->render('_order', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>-->

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                'title',
                [
                    'attribute' => 'category_id',
                    'value' => function ($model) {
                        return $model->getCategoryLabel()[$model->category_id];
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->getWorkflowStatusLabel()[$model->status];
                    }
                ],
                [
                    'attribute' => 'collaboration_type_id',
                    'value' => function ($model) {
                        return Yii::t('amoscollaborations', CollaborationType::findOne($model->collaboration_type_id)->name);
                    }
                ],
                [
                    'label' => Yii::t('amoscollaborations', 'Creatore'),
                    //'attribute' => 'creator'
                    'value' => function ($model) {
                        return $model->createdUserProfile->nomeCognome;
                    }
                ],
                [
                    'attribute' => 'start_date',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->start_date);
                    }
                ],
                [
                    'attribute' => 'end_date',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDate($model->end_date);
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a(
                                    AmosIcons::show('file'),
                                    $model->getFullViewUrl(),
                                    [
                                        'data-pjax' => 0,
                                        'target' => '_blank',
                                        'title' => Yii::t('amoscollaborations', '[Apri in una nuova scheda]') . ' ' . $model->title,
                                        'class' => 'btn btn-tools-secondary'
                                    ]
                                );
                            }
                    ]
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_item',
        ],
    ]); ?>

</div>
