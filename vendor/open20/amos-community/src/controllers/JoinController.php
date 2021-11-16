<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\controllers;

use open20\amos\community\AmosCommunity;
use open20\amos\community\assets\AmosCommunityAsset;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\community\rbac\UpdateOwnNetworkCommunity;
use open20\amos\core\controllers\CrudController;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class JoinController extends CrudController
{
    /**
     * @var string $layout
     */
//    public $layout = 'main';
//    public $layout = 'room';
    public $layout = 'view_network';

    /**
     * @inheritdoc
     */
    public function init()
    {

        $this->setModelObj(AmosCommunity::instance()->createModel('Community'));
        $this->setModelSearch(new CommunitySearch());

        $this->setAvailableViews([]);

        AmosCommunityAsset::register(Yii::$app->view);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     * @return mixed
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge([],
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'index',
                                'live-chat',
                            ],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'remove'
                            ],
                            'roles' => ['COMMUNITY_MEMBER', 'COMMUNITY_READER', 'AMMINISTRATORE_COMMUNITY', 'BASIC_USER']
                        ],
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);
        return $behaviors;
    }

    public function actionIndex($layout = null, $id = null, $subscribe = null, $urlRedirect = null)
    {
        /** @var  $model Community*/
        $model = $this->findModel($id);

        $userCommunity = CommunityUserMm::findOne(['user_id' => Yii::$app->user->id, 'community_id' => $id]);

        if($subscribe == 1 && $model->community_type_id == 1 && empty($userCommunity)){
            $module = \Yii::$app->getModule('community');
            if($module) {
                $module->createCommunityUser($model->id, CommunityUserMm::STATUS_ACTIVE, CommunityUserMm::ROLE_PARTICIPANT, \Yii::$app->user->id);
                $userCommunity = CommunityUserMm::findOne(['user_id' => Yii::$app->user->id, 'community_id' => $id]);
            }
        }



        /**
         * If The User is not subscribed to community
         */
        if (empty($model)) {
            return $this->redirect(['/dashboard']);
        }
        if (empty($userCommunity)) {
            if ($model->community_type_id == 2) {

                $this->addFlash('danger',
                    AmosCommunity::t('amosadmin', 'You Can\'t access a community you are not a member of'));
                return $this->redirect(['/community/community/view', 'id' => $id]);
            } else if ($model->community_type_id == 3) {

                return $this->redirect(['/dashboard']);
            }
        }


        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->setCwhScopeInSession([
                'community' => $id,
            ],
                [
                    'mm_name' => 'community_user_mm',
                    'entity_id_field' => 'community_id',
                    'entity_id' => $id
                ]);
        }

        if($subscribe == 1 && !empty($urlRedirect)){
            return $this->redirect($urlRedirect);
        }


        $this->setListsBreadcrumbs($model);
        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return string
     */
    public function actionRemove()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->resetCwhScopeInSession();
        }
        return;
    }

    /**
     * @return string
     */
    public function actionLiveChat() {
        return $this->render('live-chat');
    }

    /**
     * Used to set page title and breadcrumbs.
     *
     * @param Community $model Page title (ie. Created by, ...)
     */
    private function setListsBreadcrumbs($model)
    {
        if ($model->context != Community::className()) {
            $contextModel                            = Yii::createObject($model->context);
            $callingModel                            = $contextModel::findOne(['community_id' => $model->id]);
//            $createRedirectUrlParams = [
//                $callingModel->getPluginModule() . '/' . $callingModel->getPluginController() . '/' . $callingModel->getRedirectAction(),
//                'id' => $callingModel->id,
//            ];
//            $redirectUrl = Yii::$app->urlManager->createUrl($createRedirectUrlParams);
            Yii::$app->view->params['breadcrumbs'][] = [
                'label' => $model->name,
                'url' => Url::previous(),
                'remove_action' => '/community/join/remove'
            ];
        } else {
            Yii::$app->view->params['breadcrumbs'][] = [
                'label' => AmosCommunity::t('amoscommunity', 'Community'),
                'url' => \yii\helpers\Url::to('/community'),
                'remove_action' => '/community/join/remove'
            ];
        }
        Yii::$app->view->params['breadcrumbs'][] = AmosCommunity::t('amoscommunity', "Dashboard");
    }
}