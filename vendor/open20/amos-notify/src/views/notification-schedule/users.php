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

echo $this->render('_search_users', ['model' => $modelSearch]);
$notification = \open20\amos\notificationmanager\models\Notification::findOne(\Yii::$app->request->get('notification_id'));
$title = '';
$plugin = '';
if ($notification) {
    $class = $notification->class_name;
    $record_id = $notification->content_id;
    $contentModel = $class::findOne($record_id);
    $class = $notification->class_name;
    $modelClassname = \open20\amos\core\models\ModelsClassname::find()->andWhere(['classname' => $class])->one();
    if ($modelClassname) {
        $plugin = $modelClassname->label;
    }
    if ($contentModel && method_exists($contentModel, 'getTitle')) {
        $title = $contentModel->getTitle();
    }

}
?>
<h5><?= \Yii::t('app', 'Utenti che hanno ricevuto la notifica del contenuto') . '<br>' . $plugin . ' - ' . $title ?></h5>
<div class="notification-index">
    <?= \open20\amos\core\views\AmosGridView::widget(['dataProvider' => $dataProvider,
        'columns' => [['value' => function ($model) {
            return $model->userProfile->nome;
        },
            'label' => 'Nome'],
            ['value' => function ($model) {
                return $model->userProfile->cognome;
            },
                'label' => 'Cognome'],
            ['value' => function ($model) {
                return $model->email;
            },
                'label' => 'Email'],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{email}{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $url = [\open20\amos\admin\AmosAdmin::getModuleName() . '/user-profile/view', 'id' => $model->userProfile->id];
                        return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), $url, ['class' => 'btn btn-tools-secondary',
                            'title' => 'Vedi profilo utente']);
                    },
                    'email' => function ($url, $model, $key) use ($schedule) {
                        if ($schedule) {
                            $endedAtTimestamp = null;
                            $createdAtTimestamp = strtotime($schedule->created_at);
                            if (!empty($schedule->ended_at)) {
                                $endedAtTimestamp = strtotime($schedule->ended_at);
                            }
                            $query = \open20\amos\emailmanager\models\EmailSpool::find()
                                ->andWhere(['to_address' => $model->email])
                                ->andWhere(['OR', ['subject' => 'Riepilogo aggiornamenti dalla Piattaforma'], ['subject' => 'Summary of Platform Updates']])
                                ->andWhere(['>=', 'created_at', $createdAtTimestamp]);
                            if (!empty($endedAtTimestamp)) {
                                $query->andWhere(['<=', 'created_at', $endedAtTimestamp]);
                            }

                            $spool = $query->one();
                            if ($spool) {
                                $url = ['/email/spool/view', 'id' => $spool->id];
                                return Html::a(\open20\amos\core\icons\AmosIcons::show('email'), $url, ['class' => 'btn btn-tools-secondary',
                                    'title' => 'Vedi email']);
                            };
                        }
                        return '';
                    }
                ]
            ]
        ]
    ]); ?>
    <?= Html::a('Indietro', ['/notify/notification-schedule/detail', 'id' => $schedule_id], ['class' => 'btn btn-secondary']) ?>
</div>
