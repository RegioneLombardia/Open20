<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\controllers
 */

namespace open20\amos\collaborations\controllers;

use open20\amos\collaborations\Module;
use open20\amos\collaborations\models\CollaborationMarketKeywords;
use open20\amos\collaborations\models\CollaborationTargetCountries;
use open20\amos\collaborations\models\CollaborationTechnologyKeywords;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\assets\CollaborationsAsset;
use open20\amos\core\models\ModelsClassname;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationConfContent;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class CollaborationProposalsController
 * This is the class for controller "CollaborationProposalsController".
 * @package open20\amos\collaborations\controllers
 */
class CollaborationProposalsController extends \open20\amos\collaborations\controllers\base\CollaborationProposalsController
{

    /**
     * init
     * @inheritdoc
     */
    public function init()
    {
        CollaborationsAsset::register(Yii::$app->view);


        parent::init();
    }

    /**
     * Behaviors
     * @return array
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className()
                    ],
                    'rules' => [
                        [
                            // COLLABORATIONS ADMINISTRATOR
                            'allow' => true,
                            'actions' => [
                                'manage',
                                'created-by-me',
                                'to-publish',
                                'import',
                                'insert-notification-conf-contents',
                                'refused',
                                'validate-collaboration',
                                'reject-collaboration'
                            ],
                            'roles' => ['COLLABORATIONS_ADMINISTRATOR']
                        ],
                        [
                            // COLLABORATIONS STAFF
                            'allow' => true,
                            'actions' => [
                                'created-by-me',
                                'to-publish',
                                'import',
                                'insert-notification-conf-contents',
                                'refused'
                            ],
                            'roles' => ['COLLABORATIONS_STAFF']
                        ],
                        [
                            // COLLABORATIONS READER
                            'allow' => true,
                            'actions' => [
                                'archived',
                                'closed',
                                'own-interest',
                                'refused'
                            ],
                            'roles' => ['COLLABORATIONS_READER']
                        ],
                        [
                            // GUEST
                            'allow' => true,
                            'actions' => [
                                'index',
                                'own-interest',
                                'view'
                            ],
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

    /**
     * Before action
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {

        $titleSection = Yii::t('amoscollaborations', 'Proposte di collaborazione');
        $labelLinkAll = '';
        $urlLinkAll = '';
        $titleLinkAll = Yii::t('amoscollaborations', 'Visualizza tutte le proposte di collaborazione');
        $labelCreate = Yii::t('amoscollaborations', 'Nuova');
        $titleCreate = Yii::t('amoscollaborations', 'Crea nuova proposta di collaborazione');
        $labelManage = Yii::t('amoscollaborations', 'Gestisci');
        $titleManage = Yii::t('amoscollaborations', 'Gestisci proposte di collaborazione');
        $urlCreate = '/collaborations/collaboration-proposals/create';
        $urlManage = null;

        $this->view->params = [
            'isGuest' => Yii::$app->user->isGuest,
            'modelLabel' => 'collaborationProposals',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
            'hideCreate' => (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || Yii::$app->user->can('COLLABORATIONS_STAFF')) ? false : true
        ];
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * Manage links button
     * @return array|void
     */
    public static function getManageLinks()
    {
        if (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR')
            || Yii::$app->user->can('COLLABORATIONS_STAFF')) {
            $links = [];
            $links[] = [
                'title' => Yii::t('amoscollaborations',
                    'Elenco di tutte le proposte di collaborazione pubblicate'),
                'label' => Yii::t('amoscollaborations', 'Pubblicate'),
                'url' => '/collaborations/collaboration-proposals/index'
            ];
            $links[] = [
                'title' => Yii::t('amoscollaborations',
                    'Elenco delle proposte da pubblicare'),
                'label' => Yii::t('amoscollaborations', 'Da pubblicare'),
                'url' => '/collaborations/collaboration-proposals/to-publish'
            ];
            $links[] = [
                'title' => Yii::t('amoscollaborations',
                    'Elenco delle proposte create da me'),
                'label' => Yii::t('amoscollaborations', 'Create da me'),
                'url' => '/collaborations/collaboration-proposals/created-by-me'
            ];
            $links[] = [
                'title' => Yii::t('amoscollaborations',
                    'Elenco delle proposte rifiutate'),
                'label' => Yii::t('amoscollaborations', 'Rifiutate'),
                'url' => '/collaborations/collaboration-proposals/refused'
            ];
            // $links[] = [
            //     'title' => Yii::t('amoscollaborations',
            //         'Elenco delle proposte archiviate'),
            //     'label' => Yii::t('amoscollaborations', 'Archiviate'),
            //     'url' => '/collaborations/collaboration-proposals/archived'
            // ];
            $links[] = [
                'title' => Yii::t('amoscollaborations',
                    'Elenco delle proposte chiuse'),
                'label' => Yii::t('amoscollaborations', 'Chiuse'),
                'url' => '/collaborations/collaboration-proposals/closed'
            ];
            if (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR')) {
                $links[] = [
                    'title' => Yii::t('amoscollaborations',
                        'Amministra tutte le proposte'),
                    'label' => Yii::t('amoscollaborations', 'Amministra'),
                    'url' => '/collaborations/collaboration-proposals/manage'
                ];
            }

            return $links;
        }
    }

    /**
     * Collaboration proposals to validate
     * @throws InvalidConfigException
     */
    public function actionToValidate()
    {
        Url::remember();

        $this->setViewParams('toValidate');
        $this->setDataProvider(
            $this->modelSearch->searchToValidate(
                Yii::$app->request->getQueryParams()
            )
        );
        $this->setUpLayout('list');

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
        ]);
    }

    /**
     * Collaboration proposals refused
     * @throws InvalidConfigException
     */
    public function actionRefused()
    {
        Url::remember();

        $this->setViewParams('refused');
        $this->setDataProvider(
            $this->modelSearch->searchRefused(
                Yii::$app->request->getQueryParams()
            )
        );
        $this->setUpLayout('list');

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
        ]);
    }


    /**
     * Collaboration proposals to publish
     * @throws InvalidConfigException
     */
    public function actionToPublish()
    {
        Url::remember();

        $this->setViewParams('toPublish');
        $this->setDataProvider($this->modelSearch->searchToPublish(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * Collaboration proposals created by current user
     * @throws InvalidConfigException
     */
    public function actionCreatedByMe()
    {
        Url::remember();

        $this->setViewParams('createdByMe');
        $this->setDataProvider($this->modelSearch->searchCreatedByMe(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * Own interest collaboration proposals
     * @throws InvalidConfigException
     */
    public function actionOwnInterest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }

        Url::remember();

        $this->setViewParams('ownInterest');
        $this->setDataProvider($this->modelSearch->searchOwnInterest(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * Collaboration proposals archived
     * @throws InvalidConfigException
     */
    public function actionArchived()
    {
        Url::remember();

        $this->setViewParams('archived');
        $this->setDataProvider($this->modelSearch->searchArchived(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * Collaboration proposals closed
     * @throws InvalidConfigException
     */
    public function actionClosed()
    {
        Url::remember();

        $this->setViewParams('closed');
        $this->setDataProvider($this->modelSearch->searchClosed(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * All collaboration proposals
     * @throws InvalidConfigException
     */
    public function actionManage()
    {
        Url::remember();

        $this->setViewParams('manage');
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * Imports data from file.
     * @return mixed
     */

    public function actionImport()
    {
        $directoryPath = Yii::getAlias('@vendor').'/open20/amos-collaborations/src/import';

        $marketKeywords = $this->importMarketKeywords($directoryPath);
        $techKeywords = $this->importTechKeywords($directoryPath);
        $targetCountries = $this->importTargetCountries($directoryPath);

        if ($marketKeywords && $techKeywords && $targetCountries) {
            return 'Dati importati';
        }
    }

    /**
     * Add configurations for Collaboration Proposals in notification_conf_content table
     * @return bool
     * @throws InvalidConfigException
     */
    public function actionInsertNotificationConfContents($total = null, $offset = null)
    {
        if (!is_null($total) && !is_null($offset)) {
            $this->insertNotificationConfContents(5000, $offset); // +5000

            $countNotificationConfContent = NotificationConfContent::find()->count();

            echo $countNotificationConfContent . '/' . $total . ' users successfully configured for Collaboration Proposals notifications. ';

            if ($total > $countNotificationConfContent) {
                return 'Click ' . Html::a(
                        'here',
                        'insert-notification-conf-contents?total='.$total.'&offset='.$countNotificationConfContent
                    ) . ' to configure remaining users.';

            }

            return 'Completed.';
        }

        $countNotificationEnabled = NotificationConf::find()->andWhere(['notifications_enabled' => 1])->count();

        $this->insertNotificationConfContents(5000); // 5000

        $countNotificationConfContent = NotificationConfContent::find()->count();

        echo $countNotificationConfContent . '/' . $countNotificationEnabled . ' users successfully configured for Collaboration Proposals notifications. ';

        if ($countNotificationEnabled > $countNotificationConfContent) {
            return 'Click ' . Html::a(
                'here',
                'insert-notification-conf-contents?total='.$countNotificationEnabled.'&offset='.$countNotificationConfContent
                ) . ' to configure remaining users';
        }
    }

    /**
     * Function useful to insert missing notification conf contents for Collaboration Proposals in table notification_conf_content
     * @param $limit
     * @param $offset
     * @return void
     */
    public function insertNotificationConfContents($limit, $offset = null)
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (!is_null($notifyModule)) {
            $notificationConfEnabled = ArrayHelper::getColumn(
                NotificationConf::find()
                    ->andWhere(['notifications_enabled' => 1])
                    ->limit($limit)
                    ->offset($offset)
                    ->asArray()
                    ->all(),
                'id');
        }
        if (!empty($notificationConfEnabled)) {
            if (class_exists('open20\amos\collaborations\models\CollaborationProposals')) {
                if (in_array(\open20\amos\collaborations\models\CollaborationProposals::classname(), $notifyModule->notificationConfContentEnabled)) {
                    $modelClassnameCollaborationProposalsId = ModelsClassname::findOne([
                        'classname' => \open20\amos\collaborations\models\CollaborationProposals::classname()
                    ])->id;
                    foreach ($notificationConfEnabled as $key => $notificationConfId) {
                        $notificationConfContentExists = NotificationConfContent::findOne([
                            'notification_conf_id' => $notificationConfId,
                            'models_classname_id' => $modelClassnameCollaborationProposalsId
                        ]);
                        if (is_null($notificationConfContentExists)) {
                            $notificationConfContentRow = new NotificationConfContent();
                            $notificationConfContentRow->notification_conf_id = $notificationConfId;
                            $notificationConfContentRow->models_classname_id = $modelClassnameCollaborationProposalsId;
                            $notificationConfContentRow->email = 1;
                            $notificationConfContentRow->push_notification = 1;
                            $notificationConfContentRow->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Imports Market Keywords data
     * @param $filePath
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importMarketKeywords($filePath)
    {
        $marketKeywordsFileName = $filePath.'/market-keywords.xlsx';
        $marketKeywordsSpreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($marketKeywordsFileName);
        $marketKeywordsWorksheet = $marketKeywordsSpreadsheet->getActiveSheet();
        $marketKeywordsHighestRow = $marketKeywordsWorksheet->getHighestDataRow();
        for ($r = 2; $r <= $marketKeywordsHighestRow; ++$r) { // Skip first row
            if (empty($marketKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue()) || $marketKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue() == '') {
                break;
            }
            $code_market_keyword = trim($marketKeywordsWorksheet->getCellByColumnAndRow(2, $r)->getValue());
            $market_keyword = CollaborationMarketKeywords::findOne(['code' => $code_market_keyword]);
            if (empty($market_keyword)) {
                $market_keyword = new CollaborationMarketKeywords();
            }
            $market_keyword->code = trim($marketKeywordsWorksheet->getCellByColumnAndRow(2, $r)->getValue());
            $market_keyword->description = trim($marketKeywordsWorksheet->getCellByColumnAndRow(3, $r)->getValue());
            $market_keyword->category = trim($marketKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue());

            $market_keyword->save(false);
        }

        return true;
    }

    /**
     * Imports Tech Keywords data
     * @param $filePath
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importTechKeywords($filePath)
    {
        $techKeywordsFileName = $filePath.'/tech-keywords.xlsx';
        $techKeywordsSpreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($techKeywordsFileName);
        $techKeywordsWorksheet = $techKeywordsSpreadsheet->getActiveSheet();
        $techKeywordsHighestRow = $techKeywordsWorksheet->getHighestDataRow();
        for ($r = 1; $r <= $techKeywordsHighestRow; ++$r) {
            if (empty($techKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue()) || $techKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue() == '') {
                break;
            }
            $description_tech_keyword = trim($techKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue());
            $tech_keyword = CollaborationTechnologyKeywords::findOne(['description' => $description_tech_keyword]);
            if (empty($tech_keyword)) {
                $tech_keyword = new CollaborationTechnologyKeywords();
            }
            $tech_keyword->description = trim($techKeywordsWorksheet->getCellByColumnAndRow(1, $r)->getValue());
            $tech_keyword->type = trim($techKeywordsWorksheet->getCellByColumnAndRow(2, $r)->getValue());

            $tech_keyword->save(false);
        }

        return true;
    }

    
    /**
     * Valida la proposta di collaborazione il cui id e' passato al metodo
     * @param int $id Id della proposta di collaborazione da validare
     * @return \yii\web\Response
     */
    public function actionValidateCollaboration($id)
    {
        $proposal = CollaborationProposals::findOne($id);
        try {
            $proposal->sendToStatus(CollaborationProposals::STATUS_PUBLISHED);
            
            $ok = $proposal->save(false);
            if ($ok) {
                Yii::$app->getSession()->addFlash('success',
                    Yii::t('amoscollaborations', '#item_validated')
                );
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Yii::t('amoscollaborations', '#item_not_validated')
                );
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
        }

        return $this->redirect(Url::previous());
    }

    /**
     * Rifiuta la proposta di collaborazione il cui id e' passato al metodo
     * @param int $id Id della proposta di collaborazione da rifiutare
     * @return \yii\web\Response
     */
    public function actionRejectCollaboration($id)
    {
        $proposal = CollaborationProposals::findOne($id);
        try {
            $proposal->sendToStatus(CollaborationProposals::STATUS_REFUSED);
            
            $ok = $proposal->save(false);
            if ($ok) {
                Yii::$app->getSession()->addFlash('success',
                    Yii::t('amoscollaborations', '#item_rejected')
                );
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Yii::t('amoscollaborations', '#item_not_rejected')
                );
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
        }

        return $this->redirect(Url::previous());
    }    

    /**
     * Imports Target Countries data
     * @param $filePath
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importTargetCountries($filePath)
    {
        $targetCountriesFileName = $filePath.'/target-countries.xlsx';
        $targetCountriesSpreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetCountriesFileName);
        $targetCountriesWorksheet = $targetCountriesSpreadsheet->getActiveSheet();
        $targetCountriesHighestRow = $targetCountriesWorksheet->getHighestDataRow();
        for ($r = 2; $r <= $targetCountriesHighestRow; ++$r) { // Skip first row
            if (empty($targetCountriesWorksheet->getCellByColumnAndRow(1, $r)->getValue()) || $targetCountriesWorksheet->getCellByColumnAndRow(1, $r)->getValue() == '') {
                break;
            }
            $name_target_countries = trim($targetCountriesWorksheet->getCellByColumnAndRow(1, $r)->getValue());
            $target_country = CollaborationTargetCountries::findOne(['name' => $name_target_countries]);
            if (empty($target_country)) {
                $target_country = new CollaborationTargetCountries();
            }
            $target_country->name = trim($targetCountriesWorksheet->getCellByColumnAndRow(1, $r)->getValue());
            $target_country->alpha_2 = trim($targetCountriesWorksheet->getCellByColumnAndRow(2, $r)->getValue());
            $target_country->alpha_3 = trim($targetCountriesWorksheet->getCellByColumnAndRow(3, $r)->getValue());
            $target_country->country_code = trim($targetCountriesWorksheet->getCellByColumnAndRow(4, $r)->getValue());
            $target_country->iso_3166_2 = trim($targetCountriesWorksheet->getCellByColumnAndRow(5, $r)->getValue());
            $target_country->region = trim($targetCountriesWorksheet->getCellByColumnAndRow(6, $r)->getValue());
            $target_country->sub_region = trim($targetCountriesWorksheet->getCellByColumnAndRow(7, $r)->getValue());
            $target_country->intermediate_region = trim($targetCountriesWorksheet->getCellByColumnAndRow(8, $r)->getValue());
            $target_country->region_code = trim($targetCountriesWorksheet->getCellByColumnAndRow(9, $r)->getValue());
            $target_country->sub_region_code = trim($targetCountriesWorksheet->getCellByColumnAndRow(10, $r)->getValue());
            $target_country->intermediate_region_code = trim($targetCountriesWorksheet->getCellByColumnAndRow(11, $r)->getValue());

            $target_country->save(false);
        }

        return true;
    }
}
