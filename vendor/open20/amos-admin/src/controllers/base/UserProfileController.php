<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\myactivities\basic\UserProfileToValidate;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\widgets\NotifyFrequencyWidget;
use open20\amos\attachments\components\FileImport;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;

/**
 * Class UserProfileController
 * UserProfileController implements the CRUD actions for UserProfile model.
 *
 * @property \open20\amos\admin\models\UserProfile $model
 *
 * @package open20\amos\admin\controllers\base
 */
class UserProfileController extends CrudController
{

    use TabDashboardControllerTrait;
    /**
     * @var string $layout
     */
    public $layout      = 'list';
    // La utilizzo per settare il parametri al render anche da classi ereditate.
    // così anche loro potranno aggiungere parametri al render per le viste
    // caso di update
    public $updateParamsRender;
    // caso di create
    public $createParamsRender;
    //campo di appoggio per poter gestire il dato anche da classi ereditanti
    public $forzaListaRuoli;
    protected $gridView = null;
    protected $iconView = null;
    protected $listView = null;

    /**
     * @var AmosAdmin|null $adminModule
     */
    public $adminModule = null;

    /**
     * @var array $defaultViews
     */
    public $defaultViews = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(AmosAdmin::instance()->createModel('UserProfile'));
        $this->setModelSearch(AmosAdmin::instance()->createModel('UserProfileSearch'));

        $this->adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

        $this->gridView = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt').Html::tag('p', AmosAdmin::t('amosadmin', 'Tabella')),
            'url' => '?currentView=grid'
        ];

        $this->iconView = [
            'name' => 'icon',
            'label' => AmosIcons::show('grid').Html::tag('p', AmosAdmin::t('amosadmin', 'Icone')),
            'url' => '?currentView=icon'
        ];

        $this->listView = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list').Html::tag('p', AmosAdmin::t('amosadmin', 'Lista')),
            'url' => '?currentView=list'
        ];

        $this->forceDefaultViewType = $this->adminModule->forceDefaultViewType;
        $this->defaultViews         = [
            'icon' => $this->iconView,
            'grid' => $this->gridView,
            'list' => $this->listView,
        ];
        $availableViews             = [];
        foreach ($this->adminModule->defaultListViews as $view) {
            if (isset($this->defaultViews[$view])) {
                $availableViews[$view] = $this->defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        parent::init();

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'ic ic-user';
        }
        $this->setUpLayout();
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosAdmin::t('amosadmin', 'Add new user')
        ];

        if (\Yii::$app->getModule('invitations') && (\Yii::$app->getUser()->can('INVITATIONS_BASIC_USER') || \Yii::$app->getUser()->can('INVITATIONS_ADMINISTRATOR'))
            &&
            !$this->adminModule->checkManageInviteBlackList() //aggiunta chiamata metodo checkMAnageInviteBlackList
        ) {
            $widget                                      = new \open20\amos\invitations\widgets\icons\WidgetIconInvitations();
            $invitations                                 = Html::a(AmosAdmin::t('amosadmin', 'Gestisci inviti'),
                    $widget->url, ['class' => 'btn btn-navigation-primary']);
            Yii::$app->view->params['additionalButtons'] = [
                'htmlButtons' => [$invitations]
            ];
        }

        $createNewBtnParams = yii\helpers\ArrayHelper::merge(Yii::$app->view->params['createNewBtnParams'],
                [
                'layout' => "{buttonCreateNew}"
        ]);

        Yii::$app->view->params['createNewBtnParams'] = $createNewBtnParams;
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->session->set('previousTitle', $pageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title                 = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * Used for set lists view params.
     */
    public function setListsViewParams()
    {
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->session->set(AmosAdmin::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosAdmin::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Lists all UserProfile models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        if (!empty(Yii::$app->session['cwh-scope'])) {
            $this->setAvailableViews([
                'icon' => $this->iconView
            ]);
        } else {
            $availableViews = [];
            foreach ($this->adminModule->defaultListViews as $view) {
                if (isset($this->defaultViews[$view])) {
                    $availableViews[$view] = $this->defaultViews[$view];
                }
            }
            $this->setAvailableViews($availableViews);
        }
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'All users'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return parent::actionIndex($this->layout);
    }

    /**
     * Displays a single UserProfile model.
     * @param integer $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        Url::remember();
        $this->setUpLayout('main');

        $this->model = $this->findModel($id);

        return $this->render(
                'view', [
                'model' => $this->model,
                ]
        );
    }

    /**
     * Creates a new UserProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        /** @var UserProfile $profile */
        $profile = AmosAdmin::instance()->createModel('UserProfile');
        $profile->setScenario(UserProfile::SCENARIO_DYNAMIC);

        $defaultFacilitatorProfile = $profile->getDefaultFacilitator();
        if (!is_null($defaultFacilitatorProfile)) {
            $profile->facilitatore_id = $defaultFacilitatorProfile->id;
        }

        /** @var User $user */
        $user = AmosAdmin::instance()->createModel('User');

        // Salvo l'utente e subito dopo salvo il profilo agganciando l'utente
        if ($user->load(Yii::$app->request->post()) && $user->validate()) {
            if (!($profile->load(Yii::$app->request->post()) && $profile->validate())) {

                // QUALCOSA è andato storto! ERRORE...
                Yii::$app->getSession()->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'Internal error. Impossible to link user to the relative profile.'));
                return $this->render('create',
                        [
                        'model' => $profile,
                        'user' => $user,
                        'permissionSave' => 'USERPROFILE_CREATE'
                ]);
            }
            /** @var AmosAdmin $adminModule */
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!$adminModule->userCanSelectUsername) {
                $user->username = UserProfileUtility::generateUsername($user->email);
            }

            /**
             * Questi campi sul db non hanno un valore di default ma sono dichiarati NOT NULL
             * per cui schianta la query durante la creazione dell'utente,
             */
            if ($user->id == null) {
                $user->auth_key      = '';
                $user->password_hash = '';
            }

            // Se mi trovo qua posso salvare entrambe le entità senza avere errore
            $user->save();
            $profile->presentazione_breve = strip_tags($profile->presentazione_breve);
            $profile->user_id             = $user->id;
            $profile->widgets_selected    = 'a:2:{s:7:"primary";a:1:{i:0;a:6:{i:0;a:2:{s:4:"code";s:12:"USER_PROFILE";s:11:"module_name";s:5:"admin";}i:1;a:2:{s:4:"code";s:5:"USERS";s:11:"module_name";s:5:"admin";}i:2;a:2:{s:4:"code";s:11:"TAG_MANAGER";s:11:"module_name";s:3:"tag";}i:3;a:2:{s:4:"code";s:4:"ENTI";s:11:"module_name";s:4:"enti";}i:4;a:2:{s:4:"code";s:9:"ENTI_TIPO";s:11:"module_name";s:4:"enti";}i:5;a:2:{s:4:"code";s:4:"SEDI";s:11:"module_name";s:4:"enti";}}}s:5:"admin";a:1:{i:0;a:2:{i:0;a:2:{s:4:"code";s:12:"USER_PROFILE";s:11:"module_name";s:5:"admin";}i:1;a:2:{s:4:"code";s:5:"USERS";s:11:"module_name";s:5:"admin";}}}}';

            // it's used to create a new profile in the status to validate directly
            if ($profile->getWorkflowSource()->getWorkflow(UserProfile::USERPROFILE_WORKFLOW)->getInitialStatusId() !== UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
                if ($profile->status == UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) {
                    $profile->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_DRAFT;
                    $profile->save();
                    $profile->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED;
                }
            }

            //If admin module bypasses workflow flag is set, user profile is already validated
            if (($profile->status == UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) || $this->adminModule->bypassWorkflow) {
                $profile->validato_almeno_una_volta = 1;
            }

            $savedProfile = $profile->save();


            $postTightCoupling = (!empty(\Yii::$app->request->post()['UserProfile']['tightCouplingField']) ? \Yii::$app->request->post()['UserProfile']['tightCouplingField']
                    : []);
            $this->setTightCouplingUser($profile->user_id, $postTightCoupling);


            //setting personal validation scope for contents if cwh module is enabled
            if ($savedProfile) {
                $cwhModule = Yii::$app->getModule('cwh');
                if (!empty($cwhModule)) {
                    $cwhModelsEnabled = $cwhModule->modelsEnabled;
                    foreach ($cwhModelsEnabled as $contentModel) {
                        $permissionCreateArray = [
                            'item_name' => $cwhModule->permissionPrefix."_CREATE_".$contentModel,
                            'user_id' => $profile->user_id,
                            'cwh_nodi_id' => 'user-'.$profile->user_id
                        ];
                        //add cwh permission to create content in 'Personal' scope
                        $cwhAssignCreate       = new \open20\amos\cwh\models\CwhAuthAssignment($permissionCreateArray);
                        $cwhAssignCreate->save(false);
                    }
                }
                if (empty($profile->userProfileImage)) {
                    $adminmodule = AmosAdmin::instance();
                    if (!is_null($adminmodule)) {
                        $fileImport = new FileImport();
                        $ok         = $fileImport->importFileForModel($profile, 'userProfileImage',
                            \Yii::getAlias($adminmodule->defaultProfileImagePath), false);
                    }
                }
            }

            // Save email and sms notify frequency
            $notifyModule = Yii::$app->getModule('notify');
            if (!is_null($notifyModule)) {
                /** @var AmosNotify $notifyModule */
                $post           = Yii::$app->request->post();
                $emailFrequency = 0;
                $smsFrequency   = 0;
                $atLeastOne     = false;
                if (isset($post[NotifyFrequencyWidget::emailFrequencySelectorName()])) {
                    $atLeastOne     = true;
                    $emailFrequency = Yii::$app->request->post()[NotifyFrequencyWidget::emailFrequencySelectorName()];
                }
                if (isset($post[NotifyFrequencyWidget::smsFrequencySelectorName()])) {
                    $atLeastOne   = true;
                    $smsFrequency = Yii::$app->request->post()[NotifyFrequencyWidget::smsFrequencySelectorName()];
                }

                if ($atLeastOne) {
                    $ok = $notifyModule->saveNotificationConf($user->id, $emailFrequency, $smsFrequency, $post);
                } else {
                    $ok = $notifyModule->setDefaultNotificationsConfs($user->id);
                }
                if (!$ok) {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosAdmin::t('amosadmin', 'Error while saving email frequency'));
                    return $this->render('create',
                            [
                            'model' => $profile,
                            'user' => $user,
                            'permissionSave' => 'USERPROFILE_CREATE',
                    ]);
                }
            }

            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
            Yii::$app->getAuthManager()->assign(Yii::$app->getAuthManager()->getRole($adminModule->defaultUserRole),
                $user->id);
            Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'Utente creato correttamente.'));
            //return $this->redirect(['view', 'id' => $this->model->id]);
            return $this->redirectOnCreate($profile);
        } else {
            //Ripasso al form i dati inseriti anche se non corretti...
            $user->load(Yii::$app->request->post());
            $profile->load(Yii::$app->request->post());
            return $this->render('create',
                    [
                    'model' => $profile,
                    'user' => $user,
                    'permissionSave' => 'USERPROFILE_CREATE',
            ]);
        }
    }

    /**
     * Updates an existing UserProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @param bool $render
     * @param string|null $tabActive
     * @return UserProfile|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id, $render = true, $tabActive = null)
    {
        Url::remember();

        $this->setUpLayout('form');

        $url = Yii::$app->urlManager->createUrl(['/'.AmosAdmin::getModuleName().'/user-profile/update-profile', 'id' => $id]);

        if ($render) {
            $url = Yii::$app->urlManager->createUrl(['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => $id]);
        }


        // Finding the user profile model
        $this->model = $this->findModel($id);

        $this->model->tightCouplingField = $this->getTightCouplingUser($this->model->user_id);
        // Setting the dynamic scenario. It's compiled dinamically by the
        // configuration manager based on the module configurations.
        // Remove this row to restore the default functionalities.
        $this->model->setScenario(UserProfile::SCENARIO_DYNAMIC);

        $selectedFacilitatorRoles = [];

        if (Yii::$app->request->post()) {
            $previousStatus = $this->model->status;
            $ruoliUtente    = (isset(\Yii::$app->request->post()[$this->getModelName()]['listaRuoli']) && is_array(\Yii::$app->request->post()[$this->getModelName()]['listaRuoli']))
                    ? \Yii::$app->request->post()[$this->getModelName()]['listaRuoli'] : [];
            $setRuoli       = (isset(\Yii::$app->request->post()[$this->getModelName()]['listaRuoli'])) ? true : false;

            /**
             * Keep track of old status
             */
            $currentStatus = $this->model->status;

            /**
             * Keep track of old setting of notify_from_editorial_staff
             */
//            $notify_from_editorial_staff = $this->model->notify_from_editorial_staff;

            /**
             * Check if facilitator roles are deleted for the current user
             */
            $isFacilitatorRoleRemoved = false;
            $userProfilePost          = Yii::$app->request->post('UserProfile');
            if (!empty($userProfilePost)) {
                if (array_key_exists('enable_facilitator_box', $userProfilePost)) {
                    if ($this->model->enable_facilitator_box == true && $userProfilePost['enable_facilitator_box'] == false) {
                        $isFacilitatorRoleRemoved = true;
                    }
                    $this->model->enable_facilitator_box = $userProfilePost['enable_facilitator_box'];
                }
            }

            /**
             * Load post data
             */
            $notify_from_editorial_staff = $this->model->notify_from_editorial_staff;
            $this->model->load(Yii::$app->request->post());
            $this->model->user->load(Yii::$app->request->post());
            if ($this->model->validate() && $this->model->user->validate()) {
                //$this->model->presentazione_breve = strip_tags($this->model->presentazione_breve);
                if (empty(Yii::$app->request->post('notify_from_editorial_staff'))) {
                    $this->model->notify_from_editorial_staff = 0;
                    if ($this->model->notify_from_editorial_staff != $notify_from_editorial_staff) {
                        $sent = UserProfileUtility::sendMail($this->model,
                                '@vendor/open20/amos-admin/src/mail/user/notify-editorial-staff-subject',
                                '@vendor/open20/amos-admin/src/mail/user/notify-editorial-staff-html'
                        );
                    }
                } else {
                    $this->model->notify_from_editorial_staff = 1;
                }
                if ($setRuoli) {
                    if (!empty($this->forzaListaRuoli)) {
                        // Se mi hanno forzato i ruoli, prendo buoni quelli passati
                        $this->model->setRuoli($this->forzaListaRuoli);
                        $this->forzaListaRuoli = null;
                    } else {
                        $this->model->setRuoli($ruoliUtente);
                    }
                }

                if (($this->model->status == UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) || $this->adminModule->bypassWorkflow) {
                    $this->model->validato_almeno_una_volta = 1;
                }

                //If the previous status is validated return to draft
                if (!empty(\Yii::$app->request->post()['UserProfile']['isProfileModified'])) {
                    $isProfileModified = \Yii::$app->request->post()['UserProfile']['isProfileModified'];
                }
                if (($currentStatus == UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED) && !empty($isProfileModified)
                    && $isProfileModified == 1) {
                    $this->model->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE;
                }

                if ($this->model->save() && $this->model->user->save()) {
                    $postTightCoupling = (!empty(\Yii::$app->request->post()['UserProfile']['tightCouplingField']) ? \Yii::$app->request->post()['UserProfile']['tightCouplingField']
                            : []);
                    $this->setTightCouplingUser($this->model->user_id, $postTightCoupling);

                    $this->assignFacilitator($isFacilitatorRoleRemoved);

                    if (empty($this->model->userProfileImage)) {
                        $adminmodule = AmosAdmin::instance();
                        if (!is_null($adminmodule)) {
                            $fileImport = new FileImport();
                            $ok         = $fileImport->importFileForModel($this->model, 'userProfileImage',
                                \Yii::getAlias($adminmodule->defaultProfileImagePath), false);
                        }
                    }

                    // Save email and sms notify frequency
                    $notifyModule = Yii::$app->getModule('notify');
                    if (!is_null($notifyModule)) {
                        /** @var AmosNotify $notifyModule */
                        $post           = Yii::$app->request->post();
                        $emailFrequency = 0;
                        $smsFrequency   = 0;
                        $atLeastOne     = false;
                        if (isset($post[NotifyFrequencyWidget::emailFrequencySelectorName()])) {
                            $atLeastOne     = true;
                            $emailFrequency = Yii::$app->request->post()[NotifyFrequencyWidget::emailFrequencySelectorName()];
                        }
                        if (isset($post[NotifyFrequencyWidget::smsFrequencySelectorName()])) {
                            $atLeastOne   = true;
                            $smsFrequency = Yii::$app->request->post()[NotifyFrequencyWidget::smsFrequencySelectorName()];
                        }
                        if ($atLeastOne) {
                            $ok = $notifyModule->saveNotificationConf($this->model->user->id, $emailFrequency,
                                $smsFrequency, $post);
                            if (!$ok) {
                                Yii::$app->getSession()->addFlash('danger',
                                    AmosAdmin::t('amosadmin', 'Error while updating email frequency'));
                                if ($render) {
                                    $this->updateParamsRender = ArrayHelper::merge($this->updateParamsRender,
                                            [
                                            'user' => $this->model->user,
                                            'model' => $this->model,
                                            'tipologiautente' => $this->model->tipo_utente,
                                            'permissionSave' => 'USERPROFILE_UPDATE',
                                            'tabActive' => $tabActive,
                                    ]);
                                    return $this->render('update', $this->updateParamsRender);
                                } else {
                                    return $this->model;
                                }
                            }
                        }
                    }

                    Yii::$app->getSession()->addFlash('success',
                        AmosAdmin::t('amosadmin', 'Profilo utente aggiornato con successo.'));
                    if ($render) {
                        return $this->redirectOnUpdate($this->model, $previousStatus);
                    } else {
                        return $this->model;
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosAdmin::t('amosadmin', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                $selectedFacilitatorRoles = Yii::$app->request->post('selectedFacilitatorRoles');
                if (isset($this->model->user->getErrors()['email'])) {
                    Yii::$app->getSession()->addFlash('danger', $this->model->user->getErrors()['email'][0]);
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosAdmin::t('amosadmin', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
                }
            }
        }

        if ($render) {
            $this->updateParamsRender = ArrayHelper::merge($this->updateParamsRender,
                    [
                    'user' => $this->model->user,
                    'model' => $this->model,
                    'tipologiautente' => $this->model->tipo_utente,
                    'permissionSave' => 'USERPROFILE_UPDATE',
                    'tabActive' => $tabActive,
                    'selectedFacilitatorRoles' => $selectedFacilitatorRoles,
            ]);
            return $this->render('update', $this->updateParamsRender);
        } else {
            return $this->model;
        }
    }

    /**
     * @param array $resultsArray
     * @param UserProfile $userProfileFacilitator
     * @return string
     */
    private function createFacilitatorInEliminationRecapBodyText($resultsArray, $userProfileFacilitator)
    {
        $bodyText = "";

        // List of user that needs validation by the facilitator in elimination
        if (array_key_exists('usersNeedsValidation', $resultsArray)) {
            if (!empty($resultsArray['usersNeedsValidation'])) {
                $messageUsers      = AmosAdmin::t('amosadmin', 'Users');
                $messageToValidate = strtolower(AmosAdmin::t('amosadmin', 'To validate'));
                $bodyText          .= "<h3><strong>{$messageUsers} {$messageToValidate}</strong></h3>";
                /** @var UserProfileToValidate $user */
                foreach ($resultsArray['usersNeedsValidation'] as $user) {
                    $userNameSurname = $user->getNomeCognome();
                    $bodyText        .= "{$userNameSurname}<br />";
                }
                $bodyText .= "<hr />";
            }
        }

        // Email body text construction
        if (!empty($bodyText)) {
            $messageUserInElimination     = AmosAdmin::t('amosadmin', '#user_in_elimination_had_activites_pending',
                    ['userNameSurname' => $userProfileFacilitator->getNomeCognome()]);
            $messageAllActivitiesInPlugin = AmosAdmin::t('amosadmin', '#find_all_activities_pending_in_plugin');
            $bodyText                     = "<br /><h3>{$messageUserInElimination}</h3><hr />".
                $bodyText.
                "<strong>{$messageAllActivitiesInPlugin}</strong>";
        }

        return $bodyText;
    }

    private function retrieveUsersToValidateByFacilitator($userId)
    {
        $elementList = [];

        if (Yii::$app->hasModule('admin')) {
            $elementList = UserProfileToValidate::find()
                ->andWhere(['facilitatore_id' => $userId])
                ->andWhere(['status' => \open20\amos\admin\models\UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE])
                ->andWhere(['attivo' => 1])
                ->all();
        } else {
            $elementList = [];
        }

        return $elementList;
    }

    /**
     * Deletes an existing UserProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        //$user = AmosAdmin::instance()->createModel('User')->findOne(['id' => $this->model->user_id]);
        //da attivare con una transazione e i controlli su dove sono usati gli utenti (da altri 10 model) appena ne ho tempo
        $this->model->delete();

        if (!$this->model->getErrors()) {
            Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'Utente cancellato correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', "Errori durante la cancellazione dell'utente."));
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $path
     * @param $file
     * @param array $extensions
     * @return bool
     */
    protected function downloadFile($path, $file, $extensions = [])
    {
        if (is_file($path)) {
            $file_info = pathinfo($path);
            $extension = $file_info["extension"];
            if (is_array($extensions)) {
                foreach ($extensions as $e) {
                    if ($e === $extension) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="Allegato_utente.'.$extension.'"');
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: '.filesize($path));
                        ob_clean();
                        flush();
                        readfile($path);

                        return true; //Yii::$app->response->sendFile($path);
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return array|null
     */
    public function getWhiteListRoles()
    {
        $arrayRuoli  = null;
        $moduleWhite = $this->module->getWhiteListRoles();

        foreach ($moduleWhite as $rule) {
            $arrayRuoli[] = Yii::$app->authManager->getRole($rule);
        }
        return $arrayRuoli;
    }

    /**
     * @param int $lunghezza
     * @return string
     */
    protected function PasswordCasuale($lunghezza = 8)
    {
        $caratteri_disponibili = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!";

        $passwordcas = "";
        for ($i = 0; $i < $lunghezza; $i++) {
            $passwordcas = $passwordcas.substr($caratteri_disponibili, rand(0, strlen($caratteri_disponibili) - 1), 1);
        }
        return $passwordcas;
    }

    /**
     * @param $model
     * @return \yii\web\Response
     */
    protected function redirectOnCreate($model)
    {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page with the contents created by you
        $redirectToUpdatePage = false;

        if (Yii::$app->getUser()->can('USERPROFILE_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->getUser()->can('UserProfileWorkflow/VALIDATED', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if ($redirectToUpdatePage) {
            return $this->redirect(['/' . AmosAdmin::getModuleName(). '/user-profile/update', 'id' => $model->id]);
        } else {
            return $this->redirect('/' . AmosAdmin::getModuleName(). '/user-profile/validated-users');
        }
    }

    /**
     * @param $model
     * @param null $previousStatus
     * @return \yii\web\Response
     */
    protected function redirectOnUpdate($model, $previousStatus = null)
    {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page
        $redirectToUpdatePage = false;
        if (Yii::$app->getUser()->can('USERPROFILE_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        if (Yii::$app->getUser()->can(UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED, ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        if ($redirectToUpdatePage) {
            if ($model->status == UserProfile::EVENT_AFTER_VALIDATE) {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            } elseif (($model->status == UserProfile::USERPROFILE_WORKFLOW_STATUS_DRAFT) && ($previousStatus == UserProfile::USERPROFILE_WORKFLOW_STATUS_TOVALIDATE)) {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            } else {
                return $this->redirect(['/' . AmosAdmin::getModuleName(). '/user-profile/update', 'id' => $model->id]);
            }
        } else {
            return $this->redirect('/' . AmosAdmin::getModuleName(). '/user-profile/validated-users');
        }
    }

    /**
     * @param $isFacilitatorRoleRemoved
     * @throws \yii\db\Exception
     */
    public function assignFacilitator($isFacilitatorRoleRemoved)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
        if (Yii::$app->user->can('ADMIN') && $adminModule->showFacilitatorForModuleSelect) {
            /**
             * Actions to execute if facilitator roles are deleted (or added) for the current user
             */
            if ($isFacilitatorRoleRemoved) {
                // Get (configured) facilitator roles in the application
                $activeFacilitatorRoles = \open20\amos\admin\utility\UserProfileUtility::getFacilitatorForModuleRoles();

                // Remove all assigned facilitator roles to the current user
                \Yii::$app->db
                    ->createCommand()
                    ->delete("auth_assignment",
                        ['user_id' => $this->model->user_id, 'item_name' => array_keys($activeFacilitatorRoles)])
                    ->execute();

                $resultsListForEmail = [];

                // Getting list of users that needs validation by facilitator in elimination
                $usersToValidateByFacilitator = $this->retrieveUsersToValidateByFacilitator($this->model->user_id);
                if (!empty($usersToValidateByFacilitator)) {
                    $resultsListForEmail['usersNeedsValidation'] = $usersToValidateByFacilitator;
                }

                $emailBody = $this->createFacilitatorInEliminationRecapBodyText($resultsListForEmail, $this->model);

                if (!empty($emailBody)) {
                    // Invio email
                    $email        = new Email();
                    $from         = '';
                    $platformName = '';

                    // Ottengo nome applicazione/piattaforma per l'inserimento nel titolo della mail
                    if (isset(\Yii::$app->name)) {
                        $platformName = \Yii::$app->name;
                        $platformName = " {$platformName}";
                    }

                    $subject = "Ti sono state assegnate attività dell'utente {$this->model->getNomeCognome()} non più facilitatore nella piattaforma{$platformName}";
                    if (isset(\Yii::$app->params['adminEmail'])) {
                        //use default platform email assistance
                        $from = \Yii::$app->params['adminEmail'];
                    }

                    $defaultFacilitatorsList = User::findAll(['id' => (null != $this->model->getDefaultFacilitator() ? $this->model->getDefaultFacilitator()->id
                                : null)]);
                    $emailTo                 = [];
                    /** @var User $facilitator */
                    foreach ($defaultFacilitatorsList as $facilitator) {
                        $emailTo[] = $facilitator->email;
                    }

                    // Invio Email da Mail Manager
                    try {
                        $email->sendMail($from, $emailTo, $subject, $emailBody);
                    } catch (\Exception $ex) {
                        \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
                    }
                }

                Yii::$app->db->createCommand()->update(
                    UserProfile::tableName(),
                    [
                    'facilitatore_id' => (null != $this->model->getDefaultFacilitator() ? $this->model->getDefaultFacilitator()->id
                            : null),
                    ], [
                    'facilitatore_id' => $this->model->id
                    ]
                )->execute();

                /* ENABLE BOX FACILITATOR
                  // TEMPORARY SET ENABLE FACILITATOR BOX AGAIN
                  $this->model->enable_facilitator_box = 1;
                  $this->model->save(false);
                  pr("TEMPORARY SET ENABLE FACILITATOR BOX AGAIN");
                  // END TEMPORARY SET ENABLE FACILITATOR BOX AGAIN
                 */
            } else {
                // Get (configured) facilitator roles in the application
                $activeFacilitatorRoles = \open20\amos\admin\utility\UserProfileUtility::getFacilitatorForModuleRoles();

                // Remove all assigned facilitator roles to the current user
                \Yii::$app->db
                    ->createCommand()
                    ->delete("auth_assignment",
                        ['user_id' => $this->model->user_id, 'item_name' => array_keys($activeFacilitatorRoles)])
                    ->execute();

                $selectedFacilitatorRoles = Yii::$app->request->post('selectedFacilitatorRoles');
                // Assign selected facilitator roles
                if (!empty($selectedFacilitatorRoles)) {
                    foreach ($selectedFacilitatorRoles as $role) {
                        Yii::$app->db
                            ->createCommand()
                            ->insert('auth_assignment',
                                [
                                'user_id' => $this->model->user_id,
                                'item_name' => $role,
                                'created_at' => time(),
                            ])
                            ->execute();
                    }
                }
            }
        }
    }

    /**
     * 
     * @param integer $user_id
     * @return array
     */
    public function getTightCouplingUser($user_id)
    {
        $admin = AmosAdmin::getInstance();
        if ($admin->tightCoupling == true) {
            $tightCouplingModel = null;
            $tightCouplingField = null;
            if (!empty($admin->tightCouplingModel) && is_array($admin->tightCouplingModel)) {
                foreach ($admin->tightCouplingModel as $k => $v) {
                    $tightCouplingModel = $k;
                    $tightCouplingField = $v;
                }
            }

            if (!empty($tightCouplingModel) && !empty($tightCouplingField)) {
                return ArrayHelper::map($tightCouplingModel::find()->andWhere(['user_id' => $user_id])
                            ->andWhere([$admin->tightCouplingExcludeField => 0])
                            ->select($tightCouplingField)->all(), $tightCouplingField, $tightCouplingField);
            }
        }
        return [];
    }

    /**
     *
     * @param integer $user_id
     * @param array $value
     */
    public function setTightCouplingUser($user_id, $value)
    {
        $admin = AmosAdmin::getInstance();
        if ($admin->tightCoupling == true) {
            $tightCouplingModel = null;
            $tightCouplingField = null;
            if (!empty($admin->tightCouplingModel) && is_array($admin->tightCouplingModel)) {
                foreach ($admin->tightCouplingModel as $k => $v) {
                    $tightCouplingModel = $k;
                    $tightCouplingField = $v;
                }
            }

            if (!empty($tightCouplingModel) && !empty($tightCouplingField)) {
                $tightCouplingModel::deleteAll(['user_id' => $user_id, $admin->tightCouplingExcludeField => 0]);
                foreach ((array) $value as $k => $v) {
                    $model                                      = new $tightCouplingModel;
                    $model->user_id                             = $user_id;
                    $model->$tightCouplingField                 = $v;
                    $model->{$admin->tightCouplingExcludeField} = 0;
                    $model->save(false);
                }
            }
        }
    }
}