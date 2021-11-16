<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;

use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventSearch $model
 * @var string $currentView
 */


/** @var AmosEvents $eventsModule */
$eventsModule = Yii::$app->getModule(AmosEvents::getModuleName());
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$userProfile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);

?>

<div class="event-index mt-3">
    <?php

    echo $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->session->get('previousUrl')]);
    //    echo $this->render('_order', ['model' => $model,'originAction' => Yii::$app->session->get('previousUrl')]);
//$dataProvider->pagination->pageSize = 5;

    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'title',
                'event_type_id' => [
                    'attribute' => 'event_type_id',
                    'label' => $model->getAttributeLabel('eventType'),
                    'value' => 'eventType.title'
                ],
                'status' => [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->getWorkflowBaseStatusLabel();
                    }
                ],
                'begin_date_hour:datetime',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{community}{register}{unsubscribe}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('Landing', \open20\amos\events\utility\EventsUtility::getUrlLanding($model), [
                                'class' => 'btn btn-secondary btn-xs mx-1',
                                'title' => 'Vai alla landing',
                                'target' => 'blank'
                            ]);
                        },
                        'community' => function ($url, $model) {
                            if (\open20\amos\events\utility\EventsUtility::isEventCommunityEnabled($model)) {

                                $dg = \open20\amos\events\utility\EventsUtility::getCurrentDg();
                                //if is an operator subscribe as as staff directly
                                if (\Yii::$app->user->can('SUPER_USER_EVENT') || (\Yii::$app->user->can('EVENT_DG_OPERATOR') && $dg && $model->event_group_referent_id == $dg->id)) {
                                    $url = ['/events/event-dashboard/join-community', 'id' => $model->id];
                                    $canViewCommunity = true;
                                } else {
                                    $url = ['/community/join', 'id' => $model->community_id];
                                    $canViewCommunity = \open20\amos\events\utility\EventsUtility::canViewCommunityEvent($model);
                                }
                                if ($canViewCommunity) {
                                    return Html::a('Community', $url, ['class' => 'btn btn-secondary btn-xs mx-1', 'title' => AmosEvents::t('amosevents', "Val alla Community")]);
                                }
                            }
                        },
                        'register' => function ($url, $model) use ($userProfile) {
                            if (\Yii::$app->controller->action->id == 'my-invitations') {
                                //                            \open20\amos\events\models\EventInvitationSent::find()
                                //                                ->andWhere(['user_id' => \Yii::$app->user->id])
                                return Html::a('Accetta Invito', [
                                    '/events/event/event-signup', 'eid' => $model->id, 'pName' => $userProfile->nome,
                                    'pSurname' => $userProfile->cognome, 'pEmail' => $userProfile->user->email
                                ], ['class' => 'btn btn-secondary btn-xs', 'title' => AmosEvents::t('amosevents', "Accetta Invito")]);
                            }
                        },

                        'unsubscribe' => function ($url, $model) use ($userProfile) {
                            if (\Yii::$app->controller->action->id == 'my-events') {
                                $member = \open20\amos\community\models\CommunityUserMm::find()
                                    ->andWhere(['community_id' => $model->community_id])
                                    ->andWhere(['user_id' => \Yii::$app->user->id])
                                    ->andWhere(['status' => \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE])->one();
                                if ($member) {
                                    //                            \open20\amos\events\models\EventInvitationSent::find()
                                    //                                ->andWhere(['user_id' => \Yii::$app->user->id])
                                    return Html::a(AmosEvents::t('amosevents', "Disiscriviti"), [
                                        '/events/event-dashboard/unsubscribe', 'id' => $model->id
                                    ], ['class' => 'btn btn-danger btn-xs mx-1', 'title' => AmosEvents::t('amosevents', "Disiscriviti")]);
                                }
                            }
                        }
                    ]
                ]
            ],
        ],
        'listView' => [
            'itemView' => '_itemListEvent',
            'options' => [
                'class' => 'event-card-content',
            ],
            'itemsContainerOptions' => [
                'class' => "row variable-gutters",
                "role" => "listbox",
                "data-role" => "list-view",
            ],
            'itemOptions' => [
                'class' => "col-md-4 col-sm-6 my-4",
            ],

        ]
    ]); ?>
</div>