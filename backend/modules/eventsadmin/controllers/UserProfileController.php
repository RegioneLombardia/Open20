<?php

namespace backend\modules\eventsadmin\controllers;

use backend\modules\eventsadmin\models\search\UserProfileSearch;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\controllers\UserDropController;
use open20\amos\admin\models\DropAccountForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventGroupReferent;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\myactivities\basic\UserProfileToValidate;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\widgets\NotifyFrequencyWidget;
use open20\amos\attachments\components\FileImport;
use open20\amos\tag\models\Tag;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\NotFoundHttpException;


class UserProfileController extends \open20\amos\admin\controllers\UserProfileController
{

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->defaultViews = [
            'grid' => $this->gridView,
        ];
        foreach ($this->adminModule->defaultListViews as $view) {
            if (isset($this->defaultViews[$view])) {
                $availableViews[$view] = $this->defaultViews[$view];
            }
        }
        $this->setCurrentView([
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosAdmin::t('amosadmin', 'Tabella')),
            'url' => '?currentView=grid'
        ]);
        $this->setAvailableViews($availableViews);
        $this->setModelSearch(new UserProfileSearch());
    }


    /**
     * @param null $group_id
     * @return string|\yii\web\Response
     * @throws \raoul2000\workflow\base\WorkflowException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate($group_id = null)
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
        if($group_id){
            $group = EventGroupReferent::findOne($group_id);
            $profile->tightCouplingField = [$group];
        }

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
                $user->auth_key = '';
                $user->password_hash = '';
            }

            // Se mi trovo qua posso salvare entrambe le entità senza avere errore
            $user->save();
            $profile->presentazione_breve = strip_tags($profile->presentazione_breve);
            $profile->user_id = $user->id;
            $profile->widgets_selected = 'a:2:{s:7:"primary";a:1:{i:0;a:6:{i:0;a:2:{s:4:"code";s:12:"USER_PROFILE";s:11:"module_name";s:5:"admin";}i:1;a:2:{s:4:"code";s:5:"USERS";s:11:"module_name";s:5:"admin";}i:2;a:2:{s:4:"code";s:11:"TAG_MANAGER";s:11:"module_name";s:3:"tag";}i:3;a:2:{s:4:"code";s:4:"ENTI";s:11:"module_name";s:4:"enti";}i:4;a:2:{s:4:"code";s:9:"ENTI_TIPO";s:11:"module_name";s:4:"enti";}i:5;a:2:{s:4:"code";s:4:"SEDI";s:11:"module_name";s:4:"enti";}}}s:5:"admin";a:1:{i:0;a:2:{i:0;a:2:{s:4:"code";s:12:"USER_PROFILE";s:11:"module_name";s:5:"admin";}i:1;a:2:{s:4:"code";s:5:"USERS";s:11:"module_name";s:5:"admin";}}}}';

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

            $azienda = \Yii::$app->request->post('azienda');
            $this->model->azienda = $azienda;
            $savedProfile = $profile->save();


            $postTightCoupling = (!empty(\Yii::$app->request->post()['UserProfile']['tightCouplingField']) ? \Yii::$app->request->post()['UserProfile']['tightCouplingField']
                : []);
            $this->setTightCouplingUser($profile->user_id, $postTightCoupling);


            //setting personal validation scope for contents if cwh module is enabled
            if ($savedProfile) {
                if(!empty($group_id)) {
                    $okAssign = $this->assignRoleOperator($user->id, $group_id);
                }
                $preferencesTags = \Yii::$app->request->post('preferencesTags');
                $this->savePreferencesTags($this->model, $preferencesTags);

                $cwhModule = Yii::$app->getModule('cwh');
                if (!empty($cwhModule)) {
                    $cwhModelsEnabled = $cwhModule->modelsEnabled;
                    foreach ($cwhModelsEnabled as $contentModel) {
                        $permissionCreateArray = [
                            'item_name' => $cwhModule->permissionPrefix . "_CREATE_" . $contentModel,
                            'user_id' => $profile->user_id,
                            'cwh_nodi_id' => 'user-' . $profile->user_id
                        ];
                        //add cwh permission to create content in 'Personal' scope
                        $cwhAssignCreate = new \open20\amos\cwh\models\CwhAuthAssignment($permissionCreateArray);
                        $cwhAssignCreate->save(false);
                    }
                }
                if (empty($profile->userProfileImage)) {
                    $adminmodule = AmosAdmin::instance();
                    if (!is_null($adminmodule)) {
                        $fileImport = new FileImport();
                        $ok = $fileImport->importFileForModel($profile, 'userProfileImage',
                            \Yii::getAlias($adminmodule->defaultProfileImagePath), false);
                    }
                }
            }

            // Save email and sms notify frequency
            $notifyModule = Yii::$app->getModule('notify');
            if (!is_null($notifyModule)) {
                /** @var AmosNotify $notifyModule */
                $post = Yii::$app->request->post();
                $emailFrequency = 0;
                $smsFrequency = 0;
                $atLeastOne = false;
                if (isset($post[NotifyFrequencyWidget::emailFrequencySelectorName()])) {
                    $atLeastOne = true;
                    $emailFrequency = Yii::$app->request->post()[NotifyFrequencyWidget::emailFrequencySelectorName()];
                }
                if (isset($post[NotifyFrequencyWidget::smsFrequencySelectorName()])) {
                    $atLeastOne = true;
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
            if($group_id){
                return $this->redirect(['/events/event-group-referent-mm/index','group_id' => $group_id]);
            }
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
    public function actionUpdate($id, $render = true, $tabActive = null, $group_id = null)
    {
        Url::remember();

        $this->setUpLayout('form');

        $url = Yii::$app->urlManager->createUrl(['/admin/user-profile/update-profile', 'id' => $id]);

        if ($render) {
            $url = Yii::$app->urlManager->createUrl(['/admin/user-profile/update', 'id' => $id]);
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
            $ruoliUtente = (isset(\Yii::$app->request->post()[$this->getModelName()]['listaRuoli']) && is_array(\Yii::$app->request->post()[$this->getModelName()]['listaRuoli']))
                ? \Yii::$app->request->post()[$this->getModelName()]['listaRuoli'] : [];
            $setRuoli = (isset(\Yii::$app->request->post()[$this->getModelName()]['listaRuoli'])) ? true : false;

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
            $userProfilePost = Yii::$app->request->post('UserProfile');
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
//                    if ($this->model->notify_from_editorial_staff != $notify_from_editorial_staff) {
//                        $sent = UserProfileUtility::sendMail($this->model,
//                            '@vendor/open20/amos-admin/src/mail/user/notify-editorial-staff-subject',
//                            '@vendor/open20/amos-admin/src/mail/user/notify-editorial-staff-html'
//                        );
//                    }
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

                $azienda = \Yii::$app->request->post('azienda');
                $this->model->azienda = $azienda;
                if ($this->model->save() && $this->model->user->save()) {
                    $postTightCoupling = (!empty(\Yii::$app->request->post()['UserProfile']['tightCouplingField']) ? \Yii::$app->request->post()['UserProfile']['tightCouplingField']
                        : []);
                    $this->setTightCouplingUser($this->model->user_id, $postTightCoupling);

                    $preferencesTags = \Yii::$app->request->post('preferencesTags');
                    $this->savePreferencesTags($this->model, $preferencesTags);
                    $this->assignFacilitator($isFacilitatorRoleRemoved);

                    if (empty($this->model->userProfileImage)) {
                        $adminmodule = AmosAdmin::instance();
                        if (!is_null($adminmodule)) {
                            $fileImport = new FileImport();
                            $ok = $fileImport->importFileForModel($this->model, 'userProfileImage',
                                \Yii::getAlias($adminmodule->defaultProfileImagePath), false);
                        }
                    }

                    // Save email and sms notify frequency
                    $notifyModule = Yii::$app->getModule('notify');
                    if (!is_null($notifyModule)) {
                        /** @var AmosNotify $notifyModule */
                        $post = Yii::$app->request->post();
                        $emailFrequency = 0;
                        $smsFrequency = 0;
                        $atLeastOne = false;
                        if (isset($post[NotifyFrequencyWidget::emailFrequencySelectorName()])) {
                            $atLeastOne = true;
                            $emailFrequency = Yii::$app->request->post()[NotifyFrequencyWidget::emailFrequencySelectorName()];
                        }
                        if (isset($post[NotifyFrequencyWidget::smsFrequencySelectorName()])) {
                            $atLeastOne = true;
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
                    if($group_id){
                        return $this->redirect(['/events/event-group-referent-mm/index','group_id' => $group_id]);
                    }
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
                $messageUsers = AmosAdmin::t('amosadmin', 'Users');
                $messageToValidate = strtolower(AmosAdmin::t('amosadmin', 'To validate'));
                $bodyText .= "<h3><strong>{$messageUsers} {$messageToValidate}</strong></h3>";
                /** @var UserProfileToValidate $user */
                foreach ($resultsArray['usersNeedsValidation'] as $user) {
                    $userNameSurname = $user->getNomeCognome();
                    $bodyText .= "{$userNameSurname}<br />";
                }
                $bodyText .= "<hr />";
            }
        }

        // Email body text construction
        if (!empty($bodyText)) {
            $messageUserInElimination = AmosAdmin::t('amosadmin', '#user_in_elimination_had_activites_pending',
                ['userNameSurname' => $userProfileFacilitator->getNomeCognome()]);
            $messageAllActivitiesInPlugin = AmosAdmin::t('amosadmin', '#find_all_activities_pending_in_plugin');
            $bodyText = "<br /><h3>{$messageUserInElimination}</h3><hr />" .
                $bodyText .
                "<strong>{$messageAllActivitiesInPlugin}</strong>";
        }

        return $bodyText;
    }


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public static function loadTagPreferences($model)
    {
        $preferencesTags = [];
        $root = Tag::find()->andWhere(['codice' => Event::ROOT_TAG_PREFERENCE_CENTER])->one();
        if ($root) {
            $tagsMm = CwhTagOwnerInterestMm::find()
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['record_id' => $model->id])
                ->andWhere(['interest_classname' => 'simple-choice'])
                ->all();
            foreach ($tagsMm as $tagMm) {
                $preferencesTags [] = $tagMm->tag_id;
            }
        }
        return $preferencesTags;
    }

    /**
     *
     */
    public function savePreferencesTags($model, $preferencesTags)
    {
        $root = Tag::find()->andWhere(['codice' => Event::ROOT_TAG_PREFERENCE_CENTER])->one();
        if ($root) {
            CwhTagOwnerInterestMm::deleteAll(['root_id' => $root->id, 'record_id' => $model->id, 'classname' => UserProfile::className()]);
            foreach ($preferencesTags as $tagId) {
                $tagsMm = new CwhTagOwnerInterestMm();
                $tagsMm->tag_id = $tagId;
                $tagsMm->record_id = $model->id;
                $tagsMm->interest_classname = 'simple-choice';
                $tagsMm->root_id = $root->id;
                $tagsMm->classname = UserProfile::className();
                $tagsMm->save(false);
            }
//            pr(CwhTagOwnerInterestMm::find()->andWhere(['root_id' => $root->id, 'record_id' => $model->id, 'classname' => UserProfile::className()])->count());
//            die;

        }
    }


    /**
     * @param $user_id
     * @param $group_id
     * @return bool
     * @throws \Exception
     */
    public function assignRoleOperator($user_id, $group_id)
    {
        $module = \Yii::$app->getModule('events');
        if ($module) {
            $groupReferentAdministration = $module->groupReferentAdministration;
            if (!empty($groupReferentAdministration)) {
                $administrator_group_id = $groupReferentAdministration['id'];
                if ($group_id == $administrator_group_id) {
                    $role = $groupReferentAdministration['role'];
                } else {
                    $role = 'EVENT_DG_OPERATOR';
                }
                if (!\Yii::$app->authManager->checkAccess($user_id, $role)) {
                    $objRole = \Yii::$app->authManager->getRole($role);
                    if($objRole) {
                        $ok = \Yii::$app->authManager->assign($objRole, $user_id);
                        $refeMm = EventGroupReferentMm::find()
                            ->andWhere(['event_group_referent_id' => $group_id])
                            ->andWhere(['user_id' => $user_id])->one();
                        if($refeMm){
                            $refeMm->exclude_from_query = 1;
                            $refeMm->save(false);
                        }
                        return true;
                    }
                }

            }
        }
        return false;
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
//                            ->andWhere([$admin->tightCouplingExcludeField => 0])
                    ->select($tightCouplingField)->all(), $tightCouplingField, $tightCouplingField);
            }
        }
        return [];
    }


    /**
     * This is a GDPR required function to allow user drop theri own account and all his data
     * @param int $id UserProfile
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDropAccount($id)
    {
        //Avoid admin self-dropping
        if (\Yii::$app->user->can('ADMIN') && $id == \Yii::$app->user->id) {
            throw new \Exception('Hey! Can\'t Drop ADMIN User');
        }

        $this->setUpLayout('form');

        $user  = $this->findModel($id);
        $model = new DropAccountForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //New drop instance
                $dropController = new UserDropController('user_drop', $this->module);

                $moduleAdmin = \Yii::$app->getModule('admin');

                // Send a report of user's assignments via mail if he/she has many of them
                if (!empty($moduleAdmin) && $moduleAdmin->sendUserAssignmentsReportOnDelete) {
                    // Security Policy copied from dropEverything() function below, so the email won't be sent
                    // if the function will throw an exception.
                    if (!\Yii::$app->user->can('ADMIN') && $user->user_id != Yii::$app->user->id) {
                        throw new \Exception('Not allowed to drop other users');
                    }

                    $this->sendUserAssignmentsReport($user->user_id, \Yii::$app->user->id);
                }

                //Irreversible action of user drop
                $dropped = false;
                if (!empty($moduleAdmin) && $moduleAdmin->hardDelete) {
                    $dropped = $dropController->dropEverything($user->user_id);
                    \open20\amos\core\models\UserActivityLog::registerLog(\Yii::t('app', 'Cancellazione dalla piattaforma'), $user, UserProfile::LOG_TYPE_DELETE_USER, null, $user->user_id);

                } else {
                    $dropped = $dropController->softDropEverything($user->user_id);
                    \open20\amos\core\models\UserActivityLog::registerLog(\Yii::t('app', 'Cancellazione dalla piattaforma'), $user, UserProfile::LOG_TYPE_DELETE_USER, null, $user->user_id);
                }

                Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'Account Deleted.'));

                //Back to home because youre not logged anymore
                $redirectUrl = Url::home();
                if (\Yii::$app->user->can('ADMIN')) {
                    $redirectUrl = '/dashboard';
                }
                return $this->redirect($redirectUrl);
            } else {
                return $this->render('drop-account', ['model' => $model, 'id' => $id]);
            }
        } else {
            return $this->render('drop-account', ['model' => $model, 'id' => $id]);
        }
    }



}

