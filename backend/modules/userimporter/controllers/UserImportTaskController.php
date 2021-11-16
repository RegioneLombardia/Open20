<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\userimporter\controllers 
 */

namespace backend\modules\userimporter\controllers;

use backend\modules\userimporter\controllers\base\UserImportTaskController as BaseUserImportTaskController;
use backend\modules\userimporter\models\UserImportTask;
use backend\modules\userimporter\Module;
use open20\amos\core\user\User;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class UserImporterTaskController 
 * This is the class for controller "UserImporterTaskController".
 * @package backend\modules\userimporter\controllers 
 */
class UserImportTaskController extends BaseUserImportTaskController
{

    /**
     * 
     */
    public function init()
    {
        parent::init();
        Yii::$app->params['bsVersion']                     = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        \Yii::$app->getView()->params['bi-menu-sidebar']   = static::getSidebar();
    }

    /**
     *
     * @return array
     */
    protected static function getSidebar()
    {
        $menu = [
            [
                'mainMenu' => [
                    'label' => Module::t('amosuserimporter', 'Dashboard'),
                    'icon' => '/sprite/material-sprite.svg#ic_dashboard',
                    'activeTargetAction' => 'index',
                    'titleLink' => Module::t('amosuserimporter',
                        'Importazione Utenti'),
                    'url' => '/userimporter/user-import-task/index'
                ],
            ],
            [
                'mainMenu' => [
                    'label' => Module::t('amosuserimporter', 'Importa'),
                    'icon' => '/sprite/material-sprite.svg#ic_input',
                    'activeTargetAction' => 'create',
                    'titleLink' => Module::t('amosuserimporter', 'Importa'),
                    'url' => '/userimporter/user-import-task/create'
                ],
            ],
        ];

        return $menu;
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'actions' => ['optout', 'disable-user'],
                                'allow' => true,
                                'roles' => ['?', '@']
                            ],     [
                                'actions' => ['statistics'],
                                'allow' => true,
                                'roles' => ['@']
                            ],
                        ],
                    ],
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'request-information' => ['post', 'get']
                        ]
                    ]
        ]);

        return $behaviors;
    }

    /**
     * 
     * @param string $token
     */
    public function actionOptout($token)
    {
        Yii::$app->params['bsVersion']                     = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
        $appName                                           = \Yii::$app->name;
        $user                                              = User::find()->andWhere(new Expression("MD5(CONCAT(user.id, '".$appName."', user.username)) = '".$token."'"))->one();
        if (empty($user)) {
            return $this->render('security-message',
                    [
                        'title_message' => Module::t('amosuserimporter', 'Errore'),
                        'result_message' => Module::t('amosuserimporter',
                            '#invalid_token')
            ]);
        }

        return $this->render('disable_user',
                [
                    'model' => $user,
                    'token' => $token
        ]);
    }

    /**
     *
     * @param type $token
     * @return type
     */
    public function actionDisableUser($token)
    {
        Yii::$app->params['bsVersion']                     = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
        $appName                                           = \Yii::$app->name;
        $user                                              = User::find()->andWhere(new Expression("MD5(CONCAT(user.id, '".$appName."', user.username)) = '".$token."'"))->one();
        if (empty($user)) {
            return $this->render('security-message',
                    [
                        'title_message' => Module::t('amosuserimporter', 'Errore'),
                        'result_message' => Module::t('amosuserimporter',
                            '#invalid_token')
            ]);
        } else {
            $userProfile = $user->userProfile;
            $userProfile->attivo = 0;
            $user->status = 0;
            $user->save(false);
            $userProfile->save(false);
        }
        $this->redirect(Yii::$app->params['platform'] ['backendUrl']);
    }

    /**
     * @param $userTaskListId
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStatistics($userTaskListId)
    {
        $userTask = UserImportTask::find()->andWhere(['id' => $userTaskListId])->one();
        if (empty($userTask)) {
            throw new NotFoundHttpException("Pagina non trovata");
        }

        $idMessage = $userTask->mailup_message_id;
        $idList = $userTask->mailup_list_id;
        $moduleNewsletter = \Yii::$app->getModule('newsletter');
        if ($moduleNewsletter && $idMessage && $idList) {
            $mailServiceClassname = $moduleNewsletter->mail_service_driver;
            $mailService = new $mailServiceClassname();

            $lists = [];
            $listsHistory = [];
            $listsRecipients = [];
            $queryParams = \Yii::$app->request->queryParams;

            $decodedOpened = $mailService->getStatisticOpened($idMessage, true, $queryParams);
            $decodedClicks = $mailService->getStatisticClicks($idMessage, true, $queryParams);
            $decodedBounces = $mailService->getStatisticBounces($idMessage, true, $queryParams);
            $decodedUnsubscribed = $mailService->getStatisticUnsubscribed($idMessage, true, $queryParams);
            $decodedClickedLinks = $mailService->getStatisticClickedLinks($idMessage, true, $queryParams);
            $decodedRecipients = $mailService->getEmailRecipients($idMessage, $queryParams);


            if ($idList) {
                $decodedHistory = $mailService->getEmailSendHistory($idList, $idMessage, $queryParams);
            }


            $counts = [
                'idmessage' => $idMessage,
                'opened' => $decodedOpened,
                'clicks' => $decodedClicks,
                'bounces' => $decodedBounces,
                'unsubscribed' => $decodedUnsubscribed,
            ];

            if (!empty($decodedClickedLinks->Items)) {
                $lists = $decodedClickedLinks->Items;
            }

            if (!empty($decodedHistory->Items)) {
                $listsHistory = $decodedHistory->Items;
            }

            if (!empty($decodedRecipients->Items)) {
                $listsRecipients = $decodedRecipients->Items;
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $lists
            ]);
            $dataProviderHistory = new ArrayDataProvider([
                'allModels' => $listsHistory
            ]);

            $dataProviderRecipients = new ArrayDataProvider([
                'allModels' => $listsRecipients
            ]);

            $pagination = $this->setPaginationMailup($mailService, $decodedRecipients);


            return $this->render('statistics', [
                'dataProvider' => $dataProvider,
                'dataProviderHistory' => $dataProviderHistory,
                'dataProviderRecipients' => $dataProviderRecipients,
                'counts' => $counts,
                'pagination' => $pagination,
                'model' => $userTask,
            ]);

        }
        throw new NotFoundHttpException("Pagina non trovata");

    }
	
    /**
     * @param $mailService
     * @param $decoded
     * @return \yii\data\Pagination
     */
    public function setPaginationMailup($mailService, $decoded)
    {
        $currentPage = 0;
        $totElement = 0;;

        $paginationConfig = $mailService->getPaginationConfigs();
        $pagination = new \yii\data\Pagination();

        if (!empty($paginationConfig['totalCount'])) {
            $totalCount = $paginationConfig['totalCount'];
            if (property_exists($decoded, $totalCount)) {
                $totElement = $decoded->$totalCount;
                $pagination->totalCount = $totElement;
            }
        }

        if (!empty($paginationConfig['pageParam'])) {
            $pageParam = $paginationConfig['pageParam'];
            if (property_exists($decoded, $pageParam)) {
                $currentPage = $decoded->$pageParam;
                $pagination->pageParam = $pageParam;
                $pagination->setPage($currentPage);
            }

        }

        return $pagination;
    }

}