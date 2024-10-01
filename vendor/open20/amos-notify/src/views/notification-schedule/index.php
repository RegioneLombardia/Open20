<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\newsletter
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\views\DataProviderView;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\assets\NotifyAsset;
use open20\amos\notificationmanager\models\Newsletter;
use open20\amos\notificationmanager\widgets\SendNewsletterWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\notificationmanager\models\search\NewsletterSearch $model
 * @var string $currentView
 */

$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_search', ['model' => $modelSearch]);
?>
<div class="notification-index">
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'status',
                'label' => 'Stato'
            ],
            [
                'label' => 'Creato il',
                'value' => function ($model) {
                    if (!empty($model->created_at)) {
                        $date =  new \DateTime($model->created_at);
                        return $date->format( 'd/m/Y H:i');
                    }
                    return '';
                }
            ],
            [
                'label' => 'Terminato il',
                'value' => function ($model) {
                    if (!empty($model->ended_at)) {
                        $date = new \DateTime($model->ended_at);
                        return $date->format( 'd/m/Y H:i');
                    }
                    return '-';
                }

            ],
            [
                'label' => AmosNotify::t('amosnotify', 'Utenti'),
                'value' => function ($model) {
                    return $model->last_notified_user_id.'/'.$model->max_user_id_to_notify;
                },
            ],
            [
                'label' => AmosNotify::t('amosnotify', '#total_contents'),
                'value' => function ($model) {
                    return $model->getNotificationScheduleContents()->count();
                },
            ],

            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), ['/notify/notification-schedule/detail', 'id' => $model->id], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => 'Dettaglio invio'
                        ]);
                    },
                ]
            ]
        ]
    ]); ?>
</div>
