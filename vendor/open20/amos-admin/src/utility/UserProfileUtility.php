<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\utility
 * @category   CategoryName
 */

namespace open20\amos\admin\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\cwh\utility\CwhUtil;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\tag\models\Tag;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class UserProfileUtility
 * @package open20\amos\admin\utility
 */
class UserProfileUtility
{
    /**
     * Errors
     */
    const UNABLE_TO_CREATE_USER_ERROR = 1;
    const UNABLE_TO_CREATE_USER_PROFILE_ERROR = 2;
    const UNABLE_TO_ASSIGN_USER_ROLES_ERROR = 3;
    const UNABLE_TO_SAVE_USER_NOTIFICATIONS_CONFS = 4;

    /**
     * Deleted account consts
     */
    const DELETED_ACCOUNT_NAME = '########';
    const DELETED_ACCOUNT_SURNAME = '########';
    const DELETED_ACCOUNT_USERNAME_PREFIX = '#deleted_';
    const DELETED_ACCOUNT_EMAIL_PREFIX = 'deleted_';
    const DELETED_ACCOUNT_EMAIL_SUFFIX = '@deleted.it';

    /**
     * This method return all facilitator user ids.
     * @return int[]
     */
    public static function getAllFacilitatorUserIds()
    {
        return \Yii::$app->getAuthManager()->getUserIdsByRole('FACILITATOR');
    }

    /**
     * This method return all facilitator user ids.
     * @return int[]
     */
    public static function getAllExternalFacilitatorUserIds()
    {
        return \Yii::$app->getAuthManager()->getUserIdsByRole('FACILITATOR_EXTERNAL');
    }


    /**
     * The method create a new account. It creates a new User and new UserProfile only with name, surname
     * and email. The email must be unique in the database! It assign the BASIC_USER role to the new user.
     * This method returns the user id if all goes well. It returns boolean false in case of errors.
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $community Community
     * @param int $privacy default Not Accepted
     * @return array Error or user id
     */
    public static function createNewAccount($name, $surname, $email, $privacy = 0, $sendCredentials = false, $community = null, $urlFirstAccessRedirectUrl = null, $module_name = null)
    {
        $user = self::createNewUser($email, null, $module_name);

        if (!$user || $user->hasErrors()) {
            return [
                'error' => self::UNABLE_TO_CREATE_USER_ERROR,
                'messages' => $user->getErrors()
            ];
        }

        $userProfile = self::createNewUserProfile($user, $name, $surname, $privacy, $urlFirstAccessRedirectUrl, $module_name);

        if (!$userProfile || $userProfile->hasErrors()) {
            return [
                'error' => self::UNABLE_TO_CREATE_USER_PROFILE_ERROR,
                'messages' => $userProfile->getErrors()
            ];
        }

        self::setCwhPersonalValidation($userProfile);
        $ok = self::setBasicUserRoleToUser($user->id, $module_name);

        if (!$ok) {
            return [
                'error' => self::UNABLE_TO_ASSIGN_USER_ROLES_ERROR
            ];
        }

        /** @var AmosNotify $notifyModule */
        $notifyModule = Yii::$app->getModule('notify');
        if (!is_null($notifyModule)) {
            $ok = $notifyModule->setDefaultNotificationsConfs($user->id);
            if (!$ok) {
                return [
                    'error' => self::UNABLE_TO_SAVE_USER_NOTIFICATIONS_CONFS
                ];
            }
        }

        if ($sendCredentials) {
            self::sendCredentialsMail($userProfile, $community, $module_name);
        }

        // It is a social auth user?
        self::updateTagTreesAfterUserCreation($userProfile);

        return ['user' => $user];
    }

    /**
     * This method create new User only with the email that must be unique.
     * @param string $email
     * @param string!null $username
     * @return User
     */
    public static function createNewUser($email, $username = null, $module_name = null)
    {
        /** @var User $user */
        $user = AmosAdmin::instance()->createModel('User');
        $user->status = User::STATUS_ACTIVE;
        $user->email = $email;
        /** @var AmosAdmin $adminModule */
        $adminModule = Yii::$app->getModule((empty($module_name)? AmosAdmin::getModuleName() : $module_name));
        if (!$adminModule->userCanSelectUsername) {
            if (!empty($username) && is_string($username)) {
                $user->username = $username;
            } else {
                $user->username = $email;
            }
        } else {
            if (!empty($username) && is_string($username)) {
                $user->username = $username;
            } else {
                $user->username = self::generateUsername($email);
            }
        }
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @return string
     */
    public static function generateUsername($email)
    {
        $split = explode('@', strtolower($email));
        $firstPart = $split[0];
        $newUsername = $firstPart;

        $query = new Query();
        $query->from(User::tableName());
        $query->where(['username' => $firstPart]);
        $query->andWhere(['deleted_at' => null]);
        $usernames = $query->all();

        $usernameAlreadyExists = (count($usernames) > 0);
        if ($usernameAlreadyExists) {
            $newUsername = self::findUnusedUsername($firstPart);
        }

        return $newUsername;
    }

    /**
     * @param string $usernameUsed
     * @return string
     */
    private static function findUnusedUsername($usernameUsed)
    {
        $query = new Query();
        $query->select('username');
        $query->from(User::tableName());
        $query->where(new Expression("`username` LIKE '" . $usernameUsed . "%'"));
        $query->andWhere(['deleted_at' => null]);
        $query->groupBy(['username']);
        $usernames = $query->column();

        $newUsername = '';
        $counter = 1;
        $found = false;
        do {
            $newUsernameToCheck = $usernameUsed . $counter;
            if (in_array($newUsernameToCheck, $usernames)) {
                $counter++;
            } else {
                $query = new Query();
                $query->from(User::tableName());
                $query->where(['username' => $newUsernameToCheck]);
                $query->andWhere(['deleted_at' => null]);
                $exists = $query->exists();
                if (!$exists) {
                    $found = true;
                    $newUsername = $newUsernameToCheck;
                }
            }
        } while (!$found);

        return $newUsername;
    }

    /**
     * This method create new UserProfile only with the name and surname.
     * @param User $user
     * @param string $name
     * @param string $surname
     * @param int $privacy default Not Accepted
     * @return UserProfile
     */
    public static function createNewUserProfile($user, $name, $surname, $privacy = 0,$urlFirstAccessRedirectUrl = null, $module_name = null)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = \Yii::$app->getModule((empty($module_name)? AmosAdmin::getModuleName() : $module_name));
        /** @var UserProfile $userProfile */
        $userProfile = $adminModule->createModel('UserProfile');
        $userProfile->setScenario(UserProfile::SCENARIO_CREATE_NEW_ACCOUNT);
        $defaultFacilitatorProfile = $userProfile->getDefaultFacilitator();
        if (!is_null($defaultFacilitatorProfile)) {
            $userProfile->facilitatore_id = $defaultFacilitatorProfile->id;
        }
        $userProfile->user_id = $user->id;
        $userProfile->first_access_redirect_url = $urlFirstAccessRedirectUrl;
        $userProfile->attivo = UserProfile::STATUS_ACTIVE;
        if ($adminModule->bypassWorkflow) {
            $userProfile->validato_almeno_una_volta = 1;
        }
        $userProfile->status = $userProfile->getWorkflowSource()->getWorkflow(UserProfile::USERPROFILE_WORKFLOW)->getInitialStatusId();
        $userProfile->nome = $name;
        $userProfile->cognome = $surname;
        $userProfile->privacy = $privacy;
        $userProfile->widgets_selected = 'a:2:{s:7:"primary";a:1:{i:0;a:6:{i:0;a:2:{s:4:"code";s:12:"USER_PROFILE";s:11:"module_name";s:5:"admin";}i:1;a:2:{s:4:"code";s:5:"USERS";s:11:"module_name";s:5:"admin";}i:2;a:2:{s:4:"code";s:11:"TAG_MANAGER";s:11:"module_name";s:3:"tag";}i:3;a:2:{s:4:"code";s:4:"ENTI";s:11:"module_name";s:4:"enti";}i:4;a:2:{s:4:"code";s:9:"ENTI_TIPO";s:11:"module_name";s:4:"enti";}i:5;a:2:{s:4:"code";s:4:"SEDI";s:11:"module_name";s:4:"enti";}}}s:5:"admin";a:1:{i:0;a:2:{i:0;a:2:{s:4:"code";s:12:"USER_PROFILE";s:11:"module_name";s:5:"admin";}i:1;a:2:{s:4:"code";s:5:"USERS";s:11:"module_name";s:5:"admin";}}}}';
        $userProfile->detachBehaviorByClassName(SimpleWorkflowBehavior::className());
        $userProfile->save();

        return $userProfile;
    }

    /**
     * Setting personal validation scope for contents if cwh module is enabled
     * @param UserProfile $userProfile
     */
    public static function setCwhPersonalValidation($userProfile)
    {
        // Setting personal validation scope for contents if cwh module is enabled
        $cwhModule = \Yii::$app->getModule('cwh');
        if (!empty($cwhModule)) {
            $cwhModelsEnabled = $cwhModule->modelsEnabled;
            foreach ($cwhModelsEnabled as $contentModel) {
                $permissionCreateArray = [
                    'item_name' => $cwhModule->permissionPrefix . "_CREATE_" . $contentModel,
                    'user_id' => $userProfile->user_id,
                    'cwh_nodi_id' => 'user-' . $userProfile->user_id
                ];
                // Add cwh permission to create content in 'Personal' scope
                $cwhAssignCreate = new \open20\amos\cwh\models\CwhAuthAssignment($permissionCreateArray);
                $cwhAssignCreate->save(false);
            }
        }
    }

    /**
     * This method set the BASIC_USER role to the user id passed by parameters.
     * It return
     * @param int $userId
     * @return bool
     */
    public static function setBasicUserRoleToUser($userId, $module_name = null)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = \Yii::$app->getModule((empty($module_name)? AmosAdmin::getModuleName() : $module_name));
        $basicUserRole = \Yii::$app->getAuthManager()->getRole($adminModule->defaultUserRole);
        if (is_null($basicUserRole)) {
            return false;
        }
        $ok = true;
        try {
            \Yii::$app->getAuthManager()->assign($basicUserRole, $userId);
        } catch (\Exception $exception) {
            $ok = false;
        }
        return $ok;
    }

    /**
     * This method return all communities to view for a single manager in the community managers list.
     * @param \open20\amos\community\AmosCommunity $communityModule
     * @param int $userId
     * @return \open20\amos\community\models\Community[]
     */
    public static function getCommunitiesForManagers($communityModule, $userId)
    {
        $allUserCommunities = $communityModule->getCommunitiesManagedByUserId($userId);
        $userCommunities = [];
        foreach ($allUserCommunities as $userCommunity) {
            if (
                ($userCommunity->community_type_id != \open20\amos\community\models\CommunityType::COMMUNITY_TYPE_CLOSED) ||
                $userCommunity->isNetworkUser($userCommunity->id)
            ) {
                $userCommunities[] = $userCommunity;
            }
        }
        return $userCommunities;
    }

    /**
     * @param UserProfile $model
     * @param \open20\amos\community\models\Community $model
     * @return bool
     */
    public static function sendCredentialsMail($model, $community = null, $module_name = null)
    {
        try {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule((empty($module_name)? AmosAdmin::getModuleName() : $module_name));
            $subjectView = $adminModule->htmlMailSubject;
            $contentView = $adminModule->htmlMailContent;
            $subject = Email::renderMailPartial($subjectView, ['profile' => $model], $model->user->id);
            $mail = Email::renderMailPartial($contentView, ['profile' => $model, 'community' => $community], $model->user->id);
            return Email::sendMail(Yii::$app->params['supportEmail'], [$model->user->email], $subject, $mail, []);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return false;
    }

    /**
     * @param UserProfile $model
     * @param \open20\amos\community\models\Community $model
     * @param string $urlPrevious
     * @return bool
     */
    public static function sendPasswordResetMail($model, $community = null, $urlPrevious = null)
    {
        try {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            
            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule((empty($module_name)? AmosAdmin::getModuleName() : $module_name));
            $subjectView = $adminModule->htmlMailForgotPasswordSubjectView;
            $contentView = $adminModule->htmlMailForgotPasswordView;

            $subject = Email::renderMailPartial($subjectView, ['profile' => $model], \Yii::$app->getUser()->id);
            $mail = Email::renderMailPartial($contentView, ['profile' => $model, 'community' => $community, 'urlPrevious' => $urlPrevious], \Yii::$app->getUser()->id);
            return Email::sendMail(Yii::$app->params['supportEmail'], [$model->user->email], $subject, $mail, []);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return false;
    }

    /**
     * @param UserProfile $model
     * @param \open20\amos\community\models\Community $model
     * @return bool
     */
    public static function sendUserAcceptRegistrationRequestMail($model, $community = null, $invitationUserId = null)
    {
        try {
            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
            $subjectView = $adminModule->htmltMailNotifyAcceptedRegistrationRequestSubject;
            $contentView = $adminModule->htmlMailNotifyAcceptedRegistrationRequestContent;

            $invitationUser = UserProfile::findOne(['user_id' => $invitationUserId]);

            $subject = Email::renderMailPartial(
                $subjectView,
                ['profile' => $model],
                \Yii::$app->getUser()->getId()
            );

            $mail = Email::renderMailPartial(
                $contentView,
                [
                    'profile' => $model,
                    'community' => $community,
                    'invitationUser' => $invitationUser
                ],
                \Yii::$app->getUser()->getId()
            );

            return Email::sendMail(Yii::$app->params['supportEmail'], [$invitationUser->user->email], $subject, $mail, []);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

        return false;
    }

    /**
     * @param $model
     * @return string
     */
    public static function generateSubject($model)
    {
        $subject = AmosAdmin::t('amosadmin', '#welcome_man') . Yii::$app->name;
        if ($model->sesso == 'Femmina') {
            $subject = AmosAdmin::t('amosadmin', '#welcome_woman') . Yii::$app->name;
        }
        return $subject;
    }

    /**
     *
     */
    public static function generateDeactivateSubject(UserProfile $model)
    {
        $subject = $model->getNomeCognome();
        $subject .= AmosAdmin::t('amosadmin', "#subject_deactivate_user");
        return $subject;
    }

    /**
     * @param $model
     * @param $subjectView
     * @param $contentView
     * @return bool
     */
    public static function sendMail($model, $subjectView, $contentView)
    {
        try {
            $subject = Email::renderMailPartial($subjectView, ['profile' => $model], \Yii::$app->getUser()->id);
            $mail = Email::renderMailPartial($contentView, ['profile' => $model], \Yii::$app->getUser()->id);
            return Email::sendMail(Yii::$app->params['supportEmail'], [$model->user->email], $subject, $mail, []);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return false;
    }

    /**
     * @return array
     * @throws \ReflectionException
     * Get an array of active facilitator roles in the application (scanning configured plugins)
     */
    public static function getFacilitatorForModuleRoles()
    {
        /** @var array $facilitatorRoles
         * List of facilitator roles for modules set in the application
         */
        $facilitatorRoles = [];
        $facilitatorRoles['FACILITATOR'] = AmosAdmin::t('amosadmin', 'FACILITATOR');
        // Get all modules set in the application
        foreach (Yii::$app->getModules(false) as $key => $module) {
            // Get the full class path of a loaded module...
            $moduleClass = "";
            if (is_object($module)) {
                $moduleClass = get_class($module);
            }
            // ..if a module is not loaded, yii returns an array of config values.
            // In this case, get the full class path from the key of the current
            // value from the array of modules configured in the application
            if ($moduleClass == "") {
                $module = Yii::$app->getModule($key);
            }
            // Check if the getModelClassName function exists in the module
            if (method_exists($module, 'getModelClassName')) {
                $moduleModel = $module->getModelClassName();
                //pr($moduleModel, "modulo con model classname");
                if (!empty($moduleModel)) {
                    // Get the model of the module instance
                    $moduleModel = '\\' . $moduleModel;
                    $moduleModel = new $moduleModel();
                    // Check if the model implements the FacilitatorInterface
                    if (isset(class_implements($moduleModel)['open20\amos\core\interfaces\FacilitatorInterface'])) {
                        // If the model has a facilitator role set (is not empty)...
                        if (!empty($moduleModel->getFacilitatorRole()) && ($moduleModel->getFacilitatorRole() != "FACILITATOR")) {
                            // ...add the facilitator role for the module to the list of facilitator roles
                            // ---------------------------------------------------------------------------
                            // Q: Why is ReflectionClass used to get the shortname of the module class?
                            // R: Because it seems that is slighly faster than using explode or substring
                            // functions (source: https://coderwall.com/p/cpxxxw/php-get-class-name-without-namespace)
                            $facilitatorRoles[$moduleModel->getFacilitatorRole()] = $module::t($module->getAmosUniqueId(), $moduleModel->getFacilitatorRole());
                        }
                    }
                }
            }
        }
        return $facilitatorRoles;
    }

    /**
     * @param integer $userId The user id
     * @param array $facilitatorPermissionsEnabled The facilitator permissions of configured plugins on the application
     * @return array
     * Get the facilitator roles activated in the application (passed via $facilitatorPermissionsEnabled param) assigned to a user
     */
    public static function getFacilitatorRolesForUser($userId, $facilitatorPermissionsEnabled)
    {
        $rows = (new Query())
            ->select(['item_name'])
            ->from('auth_assignment')
            ->where([
                'user_id' => $userId,
                'item_name' => array_keys($facilitatorPermissionsEnabled),
            ])
            ->all();

        $result = [];
        foreach ($rows as $row) {
            $result[] = $row['item_name'];
        }

        return $result;
    }

    /**
     *
     * @param type $userId
     * @return type
     */
    public static function getQueryContacts($userId)
    {
        $contactsInvited =
            User::find()
                ->innerJoin('user_contact', 'user.id = user_contact.contact_id')
                ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                ->andWhere('user_contact.deleted_at IS NULL AND user_profile.deleted_at IS NULL')
                ->andWhere("user_contact.user_id = " . $userId)
                ->andWhere(['user_contact.status' => UserContact::STATUS_ACCEPTED])
                ->andWhere(['attivo' => 1]);

        $contactsInviting =
            User::find()
                ->innerJoin('user_contact', 'user.id = user_contact.user_id')
                ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                ->andWhere('user_contact.deleted_at IS NULL AND user_profile.deleted_at IS NULL')
                ->andWhere("user_contact.contact_id = " . $userId)
                ->andWhere(['user_contact.status' => UserContact::STATUS_ACCEPTED])
                ->andWhere(['attivo' => 1]);

        return $contactsInvited->union($contactsInviting);
    }

    /**
     * @param $int_param
     * @return null
     */
    public static function cleanIntegerParam($int_param)
    {
        if (is_integer($int_param)) {
            return $int_param;
        }
        return null;
    }

    /**
     * Returns the mail for deleted users.
     * @param int $userId
     * @return string
     */
    public static function makeDeletedUserEmail($userId)
    {
        return self::DELETED_ACCOUNT_EMAIL_PREFIX . $userId . self::DELETED_ACCOUNT_EMAIL_SUFFIX;
    }

    /**
     * @param $model UserProfile
     */
    public static function deassignRoleFacilitator($model){
        if (\Yii::$app->authManager->checkAccess($model->user_id, 'FACILITATOR')) {
            $roleFacilitator = \Yii::$app->authManager->getRole('FACILITATOR');
            if ($roleFacilitator) {
                $model->enable_facilitator_box = 0;
                $model->save(false);
                \Yii::$app->authManager->revoke($roleFacilitator, $model->user_id);
                $facilitatoreDefault = UserProfile::find()->andWhere(['default_facilitatore' => 1])->one();
                $profilesToModify = UserProfile::find()->andWhere(['facilitatore_id' => $model->id])->all();

                if ($facilitatoreDefault) {
                    foreach ($profilesToModify as $profile) {
                        $profile->facilitatore_id = $facilitatoreDefault->id;
                        $profile->save(false);
                    }
                }
            }
        }
    }

    /**
     * A social-auth is on and someone ask for a sign-in or sign-up action?
     * 
     * @param UserProfile $userProfile
     * @return bool
     * @throws \open20\amos\cwh\exceptions\CwhException
     * @throws \yii\base\InvalidConfigException
     */
    public static function updateTagTreesAfterUserCreation($userProfile)
    {
        // Cwh module is on?
        $cwhModule = Yii::$app->getModule('cwh');
        
        // Tag module is on?
        $tagModule = Yii::$app->getModule('tag');
        
        // Social Auth is on?
        $socialModule = Yii::$app->getModule('socialauth');
        
        //Get current provider from session
        $provider = Yii::$app->session->get('social-pending');
        
        if ((empty($provider)) || (empty($tagModule)) || (empty($socialModule)) || (empty($cwhModule))) {
            return false;
        }

        //Social Auth trigger
        $socialProfile = \Yii::$app->session->get('social-profile');

        // If the module is enabled and it is OpenInnovation import tags tree
        if ($socialModule && $socialModule->id) {
            // Find all providers for social auth
            $providers = array_change_key_case($socialModule->providers);

            $tagsTreeCodes = $providers[$provider]['syncronizeTagsTreeCodes'];
    
            /** @var Tag $rootTagNode */
            $rootTagNode = Tag::find()
                ->select(['root', 'codice', 'deleted_at'])
                ->where([
                    'codice' => $tagsTreeCodes,
                    'deleted_at' => null
                ])
                ->one();

            if (!empty($rootTagNode)) {
                $tagsCode = $socialProfile->tagscode;
                if (!empty($tagsCode)) {
                    $userInterestsTagIds = CwhUtil::findInterestTagIdsByUser($userProfile->id);
    
                    foreach($tagsCode as $code) {
                        /** @var Tag $tag */
                        $tag = Tag::find()
                            ->andWhere(['codice' => $code])
                            ->andWhere(['root' => $rootTagNode->root])
                            ->one();
        
                        if (!empty($tag) && !in_array($tag->id, $userInterestsTagIds)) {
                            CwhUtil::addNewUserInterest(
                                $tag,
                                $userProfile->id
                            );
                        }
                    }
                }
            }
        }
        
        return true;
    }

    /**
     * @param null $user_id
     * @return bool
     */
    public static function isSpidConnected($user_id = null)
    {
        if(empty($user_id)){
            $user_id = \Yii::$app->user->id;
        }
        $module = \Yii::$app->getModule('socialauth');
        if ($module) {
            $count =  \open20\amos\socialauth\models\SocialIdmUser::find()->andWhere(['user_id' => $user_id])->count();
            return $count > 0;
        }
        return false;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public static function isPasswordExpired($user_id)
    {
        // admin password never expire
        if ($user_id == 1) {
            return false;
        }
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        if ($adminModule) {
            /** @var UserProfile $userProfileModel */
            $userProfileModel = $adminModule->createModel('UserProfile');
            /** @var UserProfile $userProfile */
            $userProfile = $userProfileModel::find()->andWhere(['user_id' => $user_id])->one();
            $dataScadenza = date('Y-m-d', strtotime((isset(\Yii::$app->params['days-expiration-password'])) ? '+' . \Yii::$app->params['days-expiration-password'] . ' days' : '+90 days', strtotime(date('Y-m-d', strtotime($userProfile->ultimo_logout)))));
            $dataOdierna = date('Y-m-d');
            if ($dataScadenza <= $dataOdierna) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function isExpiredDateDlSemplification(){
        $expireDate = new \DateTime('2021-10-01 00:00:00');
        $now = new \DateTime();
        if($now >= $expireDate){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function mandatoryReconciliationPage()
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        if ($adminModule) {
            if ($adminModule->enableDlSemplification) {
                // admin skip
                if (\Yii::$app->user->id == 1) {
                    return false;
                }
                if (!UserProfileUtility::isSpidConnected()) {
                    $pwdExpired = UserProfileUtility::isPasswordExpired(Yii::$app->user->id);
                    $dateDlExpired = UserProfileUtility::isExpiredDateDlSemplification();
                    
                    if ($pwdExpired || $dateDlExpired) {
                        return true;
                    }
                }
            }
            return false;
        }
        return false;
    }
}
