<?php

namespace open20\amos\community\widgets\graphics;

use open20\amos\community\AmosCommunity;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\notificationmanager\models\NotificationconfNetwork;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class WidgetGraphicsCommunityPartecipants extends WidgetGraphic
{
    /**
     * @var int $pageSize
     */
    public $pageSize = 20;

    /**
     * @var int $maxButtonCount
     */
    public $maxButtonCount = 5;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('MY_PARTECIPANTS_GRAPHIC');
        $this->setLabel(AmosCommunity::t('amoscommunity', 'Partecipants'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'View the list of partecipants'));
    }

    /**
     * @return string
     */
    public function getHtml()
    {


        $viewPath     = '@vendor/open20/amos-community/src/widgets/graphics/views/';
        $viewToRender = $viewPath.'partecipants';

        if (is_null(Yii::$app->getModule('layout'))) {
            $viewToRender .= '_old';
        }
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {

                $communityModule = Yii::$app->getModule(AmosCommunity::getModuleName());
                $model           = $communityModule->createModel('Community');
                $model           = $model->findOne($scope['community']);


                $query = $model->getCommunityUserMms();

                $query
                    ->innerJoin('user_profile up', 'community_user_mm.user_id = up.user_id')
                    ->andWhere(['up.attivo' => 1]);

                if (!empty(\Yii::$app->params['platformConfigurations']) && !empty(\Yii::$app->params['platformConfigurations']['guestUserId'])) {
                    $notificationConf = \open20\amos\notificationmanager\models\NotificationconfNetwork::find()
                        ->andWhere(['record_id' => $scope['community']])
                        ->andWhere(['email' => 0])
                        ->select('user_id');
                    $query->andWhere(['!=', 'user_profile.user_id', \Yii::$app->params['platformConfigurations']['guestUserId']])
                        ->andWhere(['not in', 'user_profile.user_id', $notificationConf]);
                }

                $query->innerJoin(NotificationconfNetwork::tableName(),
                    \open20\amos\community\models\CommunityUserMm::tableName().'.community_id = '.NotificationconfNetwork::tableName().'.record_id');
                $query->innerJoin(ModelsClassname::tableName(),
                    NotificationconfNetwork::tableName().'.models_classname_id = '.ModelsClassname::tableName().'.id');
                $query->andWhere(['notificationconf_network.record_id' => $model->id]);
                $query->andWhere(['!=', 'notificationconf_network.email', NotificationsConfOpt::EMAIL_OFF]);
                $query->andWhere(['models_classname.classname' => \open20\amos\community\models\Community::className()]);

                $query->distinct()->orderBy('role ASC, id DESC');

                $dp_params = [
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => $this->pageSize,
                        'defaultPageSize' => $this->pageSize,
                    ],
                ];

                //set the data provider
                $dataProvider = new ActiveDataProvider($dp_params);
                return $this->render(
                        $viewToRender,
                        [
                        'partecipantsList' => $dataProvider,
                        'widget' => $this,
                        'toRefreshSectionId' => 'widgetGraphicPartecipants',
                        'maxButtonCount' => $this->maxButtonCount,
                        'dataProviderViewWidgetConf' => [
                            'dataProvider' => $dataProvider,
                            'currentView' => [
                                'name' => 'icon'
                            ],
                            'iconView' => [
                                'itemView' => '_icon_partecipant',
                                'options' => [
                                    'class' => 'list-items-wrapper'
                                ],
                                'containerOptions' => [
                                    'class' => 'list-items'
                                ],
                                'itemOptions' => [
                                    'class' => 'list-item'
                                ],
                            ],
                        ],
                        ]
                );
            }
        }
        return "";
    }
}