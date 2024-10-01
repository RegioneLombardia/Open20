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


?>
<div class="notification-index">
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'value' => function ($model) {
                    $notification = $model->notification;
                    if ($notification) {
                        $class = $notification->class_name;
                        $modelClassname = \open20\amos\core\models\ModelsClassname::find()->andWhere(['classname' => $class])->one();
                        if ($modelClassname) {
                            return $modelClassname->label;
                        } else {
                            return $class;
                        }
                    }
                    return ' - ';
                },
                'label' => 'Contenuto'
            ],
            [
                'attribute' => 'notification_id',
                'value' => function ($model) {
                    $notification = $model->notification;
                    if ($notification) {
                        $class = $notification->class_name;
                        $record_id = $notification->content_id;
                        $contentModel = $class::findOne($record_id);
                        if ($contentModel && method_exists($contentModel, 'getTitle')) {
                            return $contentModel->getTitle();
                        }
                    }
                    return ' - ';
                },
                'label' => 'Titolo'
            ],
            [
                'value' => function ($model) {
                    $notification = $model->notification;
                    if ($notification) {
                        $modelClassname = \open20\amos\core\models\ModelsClassname::findOne($notification->models_classname_id);
                        if ($modelClassname) {
                            $Class = $modelClassname->classname;
                            $network = $Class::findOne($notification->record_id);
                            if ($network && method_exists($network, 'getTitle')) {
                                return $network->id . ' - ' . $network->getTitle();
                            }
                        }
                    }
                    return ' - ';
                },
                'label' => 'Dalla community'
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{view}{users}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $notification = $model->notification;
                        if ($notification) {
                            $class = $notification->class_name;
                            $record_id = $notification->content_id;
                            $contentModel = $class::findOne($record_id);
                            if ($contentModel) {
                                return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), $contentModel->getFullViewUrl(), [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => 'Vedi contenuto'
                                ]);
                            }
                        }
                        return '';
                    },
                    'users' => function ($url, $model, $key) {
                        return Html::a(\open20\amos\core\icons\AmosIcons::show('accounts'),
                            ['/notify/notification-schedule/users-notified','notification_id' => $model->notification_id, 'schedule_id' => $model->notification_schedule_id],
                            [
                            'class' => 'btn btn-tools-secondary',
                            'title' => 'vedi utenti'
                        ]);
                    },
                ]
            ]

        ]
    ]); ?>
    <?= Html::a('Indietro', ['/notify/notification-schedule/index'], ['class' => 'btn btn-secondary']) ?>
</div>
