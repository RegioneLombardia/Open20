<?php

/**
 * Lombardia Informatica S.p.A.
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

NotifyAsset::register($this);

$loggedUserIsNewsletterAdminstrator = \Yii::$app->user->can('NEWSLETTER_ADMINISTRATOR');

?>
<div class="newsletter-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'subject',
                'created_at:datetime',
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        /** @var Newsletter $model */
                        return $model->getWorkflowStatusLabel();
                    }
                ],
                'send_date_begin:datetime',
                'send_date_end:datetime',
                [
                    'label' => AmosNotify::t('amosnotify', '#total_contents'),
                    'value' => 'totalContentsCount',
                ],
                [
                    'label' => AmosNotify::t('amosnotify', '#can_be_sent') . '?',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /** @var Newsletter $model */
                        if ($model->checkAllContentsPublished()) {
                            return Html::tag('span', BaseAmosModule::t('amoscore', 'Yes'), [
                                'class' => 'newsletter-can-be-sent'
                            ]);
                        } else {
                            return Html::tag('span', BaseAmosModule::t('amoscore', 'No'), [
                                'class' => 'newsletter-cannot-be-sent',
                                'title' => AmosNotify::t('amosnotify', '#check_newsletter')
                            ]);
                        }
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{reSendNewsletter}{sendNewsletter}{sendTestNewsletter}{stopSendNewsletter}{view}{update}{delete}',
                    'beforeRenderParent' => function ($model, $key, $index, $caller) {
                        return [
                            'allContentsPublished' => $model->checkAllContentsPublished(),
                            'userCanUpdateThisNewsletter' => $model->userCanUpdateThisNewsletter(),
                        ];
                    },
                    'buttons' => [
                        'sendTestNewsletter' => function ($url, $model, $key) {
                            /** @var \open20\amos\notificationmanager\models\Newsletter $model */
                            $btn = '';
                            $beforeRenderParentRes = ($key['beforeRenderParentRes']['allContentsPublished'] &&
                                $key['beforeRenderParentRes']['userCanUpdateThisNewsletter']);
                            if ($model->isDraftNewsletter() && $beforeRenderParentRes) {
                                $btn = SendNewsletterWidget::widget([
                                    'model' => $model,
                                    'buttonType' => SendNewsletterWidget::BTN_SEND_TEST_NEWSLETTER
                                ]);
                            }
                            return $btn;
                        },
                        'sendNewsletter' => function ($url, $model, $key) {
                            /** @var \open20\amos\notificationmanager\models\Newsletter $model */
                            $btn = '';
                            $beforeRenderParentRes = ($key['beforeRenderParentRes']['allContentsPublished'] &&
                                $key['beforeRenderParentRes']['userCanUpdateThisNewsletter']);
                            if ($model->isDraftNewsletter() && $beforeRenderParentRes) {
                                $btn = SendNewsletterWidget::widget([
                                    'model' => $model,
                                    'buttonType' => SendNewsletterWidget::BTN_SEND_NEWSLETTER
                                ]);
                            }
                            return $btn;
                        },
                        'reSendNewsletter' => function ($url, $model, $key) {
                            /** @var \open20\amos\notificationmanager\models\Newsletter $model */
                            $btn = '';
                            $beforeRenderParentRes = ($key['beforeRenderParentRes']['allContentsPublished'] &&
                                $key['beforeRenderParentRes']['userCanUpdateThisNewsletter']);
                            if ($model->isSentNewsletter() && $beforeRenderParentRes) {
                                $btn = SendNewsletterWidget::widget([
                                    'model' => $model,
                                    'buttonType' => SendNewsletterWidget::BTN_RE_SEND_NEWSLETTER
                                ]);
                            }
                            return $btn;
                        },
                        'stopSendNewsletter' => function ($url, $model, $key) use ($loggedUserIsNewsletterAdminstrator) {
                            /** @var \open20\amos\notificationmanager\models\Newsletter $model */
                            $btn = '';
                            if (($model->isWaitSendNewsletter() || $model->isWaitReSendNewsletter()) &&
                                $loggedUserIsNewsletterAdminstrator && $key['beforeRenderParentRes']['allContentsPublished']
                            ) {
                                $btn = SendNewsletterWidget::widget([
                                    'model' => $model,
                                    'buttonType' => SendNewsletterWidget::BTN_STOP_SEND_NEWSLETTER
                                ]);
                            }
                            return $btn;
                        }
                    ]
                ]
            ]
        ]
    ]); ?>
</div>
