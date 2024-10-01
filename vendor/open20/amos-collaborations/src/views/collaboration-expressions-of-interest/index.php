<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\views\collaboration-expressions-of-interest
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\collaborations\models\search\CollaborationExpressionsOfInterestSearch $model
 * @var string $currentView
 */

$this->title = \Yii::t('amoscollaborations', 'Manifestazioni di interesse');
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'),  'url' => ['/collaborations/collaboration-proposals/own-interest']];
$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Manifestazioni di interesse')];
?>

<div class="<?= Yii::$app->controller->id ?>-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'proposal_title',
                [
                    'label' => \Yii::t('amoscollaborations', 'Manifestante'),
                    'value' => function ($model) {
                        return $model->proposer_first_name . ' ' . $model->proposer_last_name;
                    }
                ],
                'proposer_email',
                'company',
                'manifestazioneinteresse',
                'country',
                [
                    'label' => \Yii::t('amoscollaborations', 'Data manifestazione'),
                    'format' => 'datetime',
                    'attribute' => 'created_at'
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{collaboration_proposal}{view}{update}{delete}',
                    'buttons' => [
                        'collaboration_proposal' => function ($url, $model) {
                            return Html::a(
                                AmosIcons::show('file'),
                                $model->collaborationProposals->getFullViewUrl(),
                                [
                                    'data-pjax' => 0,
                                    'target' => '_blank',
                                    'title' => \Yii::t('amoscollaborations', '[Apri in una nuova scheda] Proposta di collaborazione'),
                                    'class' => 'btn btn-tools-secondary'
                                ]
                            );
                        },
                        'view' => function ($url, $model) {
                            return Html::a(
                                AmosIcons::show('assignment'),
                                $model->getFullViewUrl(),
                                [
                                    'data-pjax' => 0,
                                    'target' => '_blank',
                                    'title' => \Yii::t('amoscollaborations', '[Apri in una nuova scheda] Manifestazione di interesse'),
                                    'class' => 'btn btn-tools-secondary'
                                ]
                            );
                        },
                    ]
                ],
            ]
        ],
        'listView' => [
            'itemView' => '_item'
        ]
    ]); ?>
</div>
