<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\exceptions\AdminException;
use open20\amos\admin\models\ChangeUserCreateForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\ChangeUserUtility;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\attachments\components\FileImport;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\File;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\cwh\utility\CwhUtil;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationConf;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;

/**
 * Class ChangeUserController
 *
 * @property \open20\amos\admin\models\UserProfile $model
 * @property \open20\amos\admin\models\search\ChangeUserSearch $modelSearch
 *
 * @package open20\amos\admin\controllers
 */
class ChangeUserController extends CrudController
{
    const ERROR_POINT_CREATE_NEW_ACCOUNT = 1;
    const ERROR_POINT_DUPLICATE_USER_INTERESTS = 2;
    const ERROR_POINT_DUPLICATE_USER_AVATAR = 3;
    const ERROR_POINT_DUPLICATE_NOTIFICATION_CONF = 4;
    const ERROR_POINT_AFTER_CREATE_YOUR_NEW_PROFILE = 5;

    /**
     * @var AmosAdmin $adminModule
     */
    protected $adminModule;

    /**
     * @var FileModule $attachmentsModule
     */
    protected $attachmentsModule;

    /**
     * @var AmosNotify $notifyModule
     */
    protected $notifyModule;

    /**
     * @var AmosCwh $cwhModule
     */
    protected $cwhModule;

    /**
     * @var array $errorPoints
     */
    protected $errorPoints = [];

    /**
     * @var bool $notificationsAlreadyRemoved
     */
    protected $notificationsAlreadyRemoved = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->adminModule = AmosAdmin::instance();

        $this->setModelObj($this->adminModule->createModel('UserProfile'));
        $this->setModelSearch($this->adminModule->createModel('ChangeUserSearch'));

        $this->viewIcon = [
            'name' => 'icon',
            'label' => AmosIcons::show('grid') . Html::tag('p', AmosAdmin::t('amosadmin', 'Icone')),
            'url' => '?currentView=icon'
        ];

        $this->setAvailableViews([
            'icon' => $this->viewIcon,
        ]);

        parent::init();

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'ic ic-user';
        }
        $this->setUpLayout();

        $this->errorPoints = [
            self::ERROR_POINT_AFTER_CREATE_YOUR_NEW_PROFILE,
            self::ERROR_POINT_DUPLICATE_NOTIFICATION_CONF,
            self::ERROR_POINT_DUPLICATE_USER_AVATAR,
            self::ERROR_POINT_DUPLICATE_USER_INTERESTS,
            self::ERROR_POINT_CREATE_NEW_ACCOUNT
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'my-users-list',
                            'login-with-my-user',
                            'check-email-ajax',
                            'create-your-new-profile',
                        ],
                        'roles' => ['CHANGE_USER_PROFILE']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        \Yii::$app->view->params['forceCreateNewButtonWidget'] = true;
        \Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile'),
            'urlCreateNew' => ['/' . AmosAdmin::getModuleName() . '/change-user/create-your-new-profile']
        ];
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        \Yii::$app->session->set('previousTitle', $pageTitle);
        \Yii::$app->session->set('previousUrl', Url::previous());
        \Yii::$app->view->title = $pageTitle;
        \Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * Used for set lists view params.
     */
    public function setListsViewParams()
    {
        Url::remember();
        $this->setUpLayout('list');
        $this->setCreateNewBtnParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', '#change_user_profile'));
        \Yii::$app->session->set('previousUrl', Url::previous());
        \Yii::$app->session->set(AmosAdmin::beginCreateNewSessionKey(), Url::previous());
        \Yii::$app->session->set(AmosAdmin::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * @return string
     */
    protected function redirectAfterError()
    {
        $referrer = \Yii::$app->request->getReferrer();
        if (!is_null($referrer)) {
            return $this->redirect($referrer);
        }
        $urlPrevious = Url::previous();
        if (!is_null($urlPrevious)) {
            return $this->redirect($urlPrevious);
        }
        return $this->goHome();
    }

    /**
     * This action render the logged user other profiles list.
     * @return string
     */
    public function actionMyUsersList()
    {
        $this->setDataProvider($this->modelSearch->search(\Yii::$app->request->getQueryParams()));
        $this->setListsViewParams();

        return $this->render('my-users-list', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
        ]);
    }

    /**
     * This action change the logged user with the selected profile.
     * @param int $user_id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLoginWithMyUser($user_id)
    {
        /** @var User $loggedUser */
        $loggedUser = \Yii::$app->user->identity;
        $loggedUserId = $loggedUser->id;
        $loggedUserProfile = $loggedUser->userProfile;

        if (empty($loggedUserProfile->codice_fiscale)) {
            \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#change_user_error_empty_cf'));
            return $this->redirectAfterError();
        }

        $requestedUser = User::findOne($user_id);
        $requestedUserId = $requestedUser->id;

        if (is_null($requestedUser)) {
            \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#change_user_error_user_not_exists'));
            return $this->redirectAfterError();
        }

        if ($requestedUserId == $loggedUserId) {
            \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#change_user_error_user_already_logged'));
            return $this->redirectAfterError();
        }

        $requestedUserProfile = $requestedUser->userProfile;

        if ($requestedUserProfile->nome == UserProfileUtility::DELETED_ACCOUNT_NAME) {
            \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#change_user_error_user_deleted'));
            return $this->redirectAfterError();
        }

        if ($requestedUserProfile->isDeactivated()) {
            \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#change_user_error_user_deactivated'));
            return $this->redirectAfterError();
        }

        /** @var ActiveQuery $queryLoggedUser */
        $queryLoggedUser = UserProfile::find();
        $queryLoggedUser->andWhere(['user_id' => $requestedUserId]);
        $queryLoggedUser->andWhere(['codice_fiscale' => $loggedUserProfile->codice_fiscale]);
        $queryLoggedUser->andWhere(['attivo' => UserProfile::STATUS_ACTIVE]);
        $queryLoggedUser->andWhere(['<>', 'nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        $sameCfRequestedUserAndLoggedUser = $queryLoggedUser->exists();

        if (!$sameCfRequestedUserAndLoggedUser) {
            \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#change_user_error_not_your_user'));
            return $this->redirectAfterError();
        }

        $loginTimeout = \Yii::$app->params['loginTimeout'] ?: 3600;
        \Yii::$app->user->logout();
        \Yii::$app->user->login($requestedUser, $loginTimeout);

        \Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', '#change_user_success'));

        return $this->goHome();
    }

    /**
     * @param string $email
     * @return false|string
     */
    public function actionCheckEmailAjax($email = '')
    {
        $responseArray = ['success' => 1];
        $response = ChangeUserUtility::checkUserAlreadyPresent($email, true, true);
        if (!empty($response['present']) && $response['present']) {
            $responseArray = ArrayHelper::merge([
                'success' => 0,
                'messageConfirm' => AmosAdmin::t('amosadmin', '#check_mail_ajax_user_already_present'),
            ], $response);
        }
        if ($responseArray['success'] == 1) {
            $responseArray['message'] = '';
        }
        return json_encode($responseArray);
    }

    /**
     * This action creates a new profile as a copy of the logged user.
     */
    public function actionCreateYourNewProfile()
    {
        $this->setUpLayout('form');

        /** @var ChangeUserCreateForm $model */
        $model = $this->adminModule->createModel('ChangeUserCreateForm');

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            /** @var User $loggedUser */
            $loggedUser = \Yii::$app->user->identity;
            $loggedUserId = $loggedUser->id;
            $loggedUserProfile = $loggedUser->userProfile;
            $loggedUserProfileId = $loggedUserProfile->id;

            $this->attachmentsModule = FileModule::instance();
            $this->cwhModule = AmosCwh::instance();
            $this->notifyModule = AmosNotify::instance();

            $this->beforeCreateYourNewProfile($model, $loggedUser);

            $retVal = UserProfileUtility::createNewAccount(
                $loggedUserProfile->nome,
                $loggedUserProfile->cognome,
                $model->email,
                $loggedUserProfile->privacy
            );

            if (isset($retVal['error'])) {
                \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#create_your_new_profile_error_creating_user'));
                $this->rollBackOperations(self::ERROR_POINT_CREATE_NEW_ACCOUNT, $retVal);
                return $this->render('create-your-new-profile', [
                    'model' => $model
                ]);
            }

            /** @var User $newUser */
            $newUser = $retVal['user'];
            $userId = $newUser->id;

            $userProfileClassName = $this->adminModule->model('UserProfile');
            /** @var UserProfile $userProfileModel */
            $userProfileModel = $this->adminModule->createModel('UserProfile');
            /** @var $newUserProfile UserProfile */
            $newUserProfile = $userProfileModel::findOne(['user_id' => $userId]);
            $newUserProfile->setAttributes($loggedUserProfile->attributes);
            $newUserProfile->user_id = $userId;
            $newUserProfile->created_by = $loggedUserId;
            $newUserProfile->updated_by = $loggedUserId;
            $now = date('Y-m-d H:i:s');
            $newUserProfile->created_at = $now;
            $newUserProfile->updated_at = $now;

            // !empty means that the logged user is not the main user, then replicate in this new user the correct main user profile id.
            $mainUserProfileId = (!empty($loggedUserProfile->main_user_profile_id) ? $loggedUserProfile->main_user_profile_id : $loggedUserProfileId);
            $newUserProfile->main_user_profile_id = $mainUserProfileId;
            $newUserProfile->save(false);

            $ok = $this->duplicateUserInterests($loggedUserProfileId, $newUserProfile->id, $userProfileClassName);
            if (!$ok) {
                \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#create_your_new_profile_error_copy_user_interests'));
                $this->rollBackOperations(self::ERROR_POINT_DUPLICATE_USER_INTERESTS, [
                    'user' => $newUser,
                    'newUserProfileId' => $newUserProfile->id,
                    'userProfileClassName' => $userProfileClassName
                ]);
                return $this->render('create-your-new-profile', [
                    'model' => $model
                ]);
            }

            $ok = $this->duplicateUserAvatar($loggedUserProfile, $newUserProfile, $userProfileClassName);
            if (!$ok) {
                \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#create_your_new_profile_error_copy_user_avatar'));
                $this->rollBackOperations(self::ERROR_POINT_DUPLICATE_USER_AVATAR, [
                    'user' => $newUser,
                    'newUserProfileId' => $newUserProfile->id,
                    'userProfileClassName' => $userProfileClassName,
                    'newUserProfile' => $newUserProfile
                ]);
                return $this->render('create-your-new-profile', [
                    'model' => $model
                ]);
            }

            $ok = $this->duplicateNotificationConf($loggedUserId, $userId);
            if (!$ok) {
                \Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#create_your_new_profile_error_copy_notification_conf'));
                $this->rollBackOperations(self::ERROR_POINT_DUPLICATE_NOTIFICATION_CONF, [
                    'user' => $newUser,
                    'newUserProfileId' => $newUserProfile->id,
                    'userProfileClassName' => $userProfileClassName,
                    'newUserProfile' => $newUserProfile,
                    'newUserId' => $userId
                ]);
                return $this->render('create-your-new-profile', [
                    'model' => $model
                ]);
            }

            $ok = $this->afterCreateYourNewProfile($model, $loggedUser, $newUser);
            if (!$ok) {
                \Yii::getLogger()->log(AmosAdmin::t('amosadmin', '#create_your_new_profile_error_post_copy_operations'), Logger::LEVEL_ERROR);
                $this->rollBackOperations(self::ERROR_POINT_AFTER_CREATE_YOUR_NEW_PROFILE, [
                    'user' => $newUser,
                    'newUserProfileId' => $newUserProfile->id,
                    'userProfileClassName' => $userProfileClassName,
                    'newUserProfile' => $newUserProfile,
                    'newUserId' => $userId,
                    'loggedUser' => $loggedUser,
                    'newUser' => $newUser
                ]);
                return $this->render('create-your-new-profile', [
                    'model' => $model
                ]);
            }

            \Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', '#create_your_new_profile_success'));

            return $this->redirect(['/' . AmosAdmin::getModuleName() . '/change-user/my-users-list']);
        }

        return $this->render('create-your-new-profile', [
            'model' => $model
        ]);
    }

    /**
     * @param int $errorPoint
     * @param User $user
     * @throws AdminException
     */
    /**
     * @param int $errorPoint
     * @param User $user
     * @param string $userProfileClassName
     * @param $newUser $newUser
     * @param null $newUserProfile
     * @param int $createNewAccountErrCode
     * @throws AdminException
     */
    protected function rollBackOperations($errorPoint, $params/*, $userProfileClassName, $newUser = null, $newUserProfile = null, $createNewAccountErrCode = 0*/)
    {
        // Missing break statements is not an error because this is the inverted profile copy sequence.
        // If an error occurs at the end of the copy operations, then all the operations from the last to the first step must be reverted.
        switch ($errorPoint) {
            case self::ERROR_POINT_AFTER_CREATE_YOUR_NEW_PROFILE:
                $ok = $this->removeAfterCreateNewProfile($errorPoint, $params);
                if (!$ok) {
                    throw new AdminException('Error revert after create new profile operations');
                }
            case self::ERROR_POINT_DUPLICATE_NOTIFICATION_CONF:
                $ok = $this->removeDuplicatedNotificationConf($errorPoint, $params['newUserId']);
                if (!$ok) {
                    throw new AdminException('Error remove duplicated user notification conf');
                }
            case self::ERROR_POINT_DUPLICATE_USER_AVATAR:
                /** @var UserProfile $newUserProfile */
                $newUserProfile = $params['newUserProfile'];
                $this->removeDuplicatedUserAvatar($errorPoint, $newUserProfile);
            case self::ERROR_POINT_DUPLICATE_USER_INTERESTS:
                $ok = $this->removeDuplicatedUserInterests($errorPoint, $params['newUserProfileId'], $params['userProfileClassName']);
                if (!$ok) {
                    throw new AdminException('Error remove duplicated user interests');
                }
            case self::ERROR_POINT_CREATE_NEW_ACCOUNT:
                $ok = $this->removeDuplicatedBaseProfile($errorPoint, $params);
                if (!$ok) {
                    throw new AdminException('Error remove duplicated base profile');
                }
                break;
            default:
                throw new AdminException('Error point not supported');
                break;
        }
    }

    /**
     * @param int $errorPoint
     * @param array $params
     * @return bool
     */
    protected function removeDuplicatedBaseProfile($errorPoint, $params)
    {
        if (isset($params['user'])) {
            /** @var User $newUser */
            $newUser = $params['user'];
            $newUserId = $newUser->id;
            $createNewAccountErrCode = 0;
            $createUserSuccessful = true;
        } else {
            $newUser = null;
            $newUserId = $params['userId'];
            $createNewAccountErrCode = $params['error'];
            $createUserSuccessful = false;
        }

        // This means that the createNewAccount utility hasn't created the user record. Then nothing to revert.
        if ($createNewAccountErrCode == UserProfileUtility::UNABLE_TO_CREATE_USER_ERROR) {
            return true;
        }

        if ($createUserSuccessful || ($createNewAccountErrCode == UserProfileUtility::UNABLE_TO_SAVE_USER_NOTIFICATIONS_CONFS)) {
            $ok = $this->removeDuplicatedNotificationConf($errorPoint, $newUserId);
            if (!$ok) {
                return false;
            }
        }

        if ($createUserSuccessful || in_array($createNewAccountErrCode, [
                UserProfileUtility::UNABLE_TO_SAVE_USER_NOTIFICATIONS_CONFS,
                UserProfileUtility::UNABLE_TO_ASSIGN_USER_ROLES_ERROR
            ])) {
            $ok = $this->removeCwhAuthAssignments($newUserId);
            if (!$ok) {
                return false;
            }
        }

        if ($createUserSuccessful || in_array($createNewAccountErrCode, [
                UserProfileUtility::UNABLE_TO_SAVE_USER_NOTIFICATIONS_CONFS,
                UserProfileUtility::UNABLE_TO_ASSIGN_USER_ROLES_ERROR,
                UserProfileUtility::UNABLE_TO_CREATE_USER_PROFILE_ERROR
            ])) {

            if (!is_null($newUser)) {
                $profile = $newUser->userProfile;
            } else {
                /** @var User $userModel */
                $userModel = $this->adminModule->createModel('User');
                $newUser = $userModel::findOne(['id' => $newUserId]);
                /** @var UserProfile $userProfileModel */
                $userProfileModel = $this->adminModule->createModel('UserProfile');
                $profile = $userProfileModel::findOne(['user_id' => $newUserId]);
            }

            if (!is_null($profile)) {
                $profile = UserProfileUtility::maskProfileData($profile);
                $profile->delete();
                if ($profile->hasErrors()) {
                    return false;
                }
            }

            $newUser = UserProfileUtility::maskUserData($newUser);
            $newUser->delete();

            if ($newUser->hasErrors()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $userId
     * @return bool
     */
    protected function removeCwhAuthAssignments($userId)
    {
        if (!empty($this->cwhModule)) {
            /** @var ActiveQuery $query */
            $query = CwhAuthAssignment::find();
            $query->andWhere(['user_id' => $userId]);
            $cwhAuthAssignments = $query->all();
            foreach ($cwhAuthAssignments as $cwhAuthAssignment) {
                /** @var CwhAuthAssignment $cwhAuthAssignment */
                $cwhAuthAssignment->delete();
                if ($cwhAuthAssignment->hasErrors()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param int $oldUserProfileId
     * @param int $newUserProfileId
     * @param string $userProfileClassName
     * @return bool
     * @throws \open20\amos\cwh\exceptions\CwhException
     * @throws \yii\base\InvalidConfigException
     */
    protected function duplicateUserInterests($oldUserProfileId, $newUserProfileId, $userProfileClassName)
    {
        $tagModule = \Yii::$app->getModule('tag');
        if (!is_null($tagModule)) {
            /** @var \open20\amos\tag\AmosTag $tagModule */
            $userInterestsTagIds = CwhUtil::findInterestTagIdsByUser($oldUserProfileId);
            foreach ($userInterestsTagIds as $tagId) {
                /** @var \open20\amos\tag\models\Tag $tagModel */
                $tagModel = $tagModule->createModel('Tag');
                $tag = $tagModel::findOne($tagId);
                if (!is_null($tag)) {
                    $ok = CwhUtil::addNewUserInterest($tag, $newUserProfileId, 'simple-choice', $userProfileClassName);
                    if (!$ok) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param int $errorPoint
     * @param int $newUserProfileId
     * @param string $userProfileClassName
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    protected function removeDuplicatedUserInterests($errorPoint, $newUserProfileId, $userProfileClassName)
    {
        $tagModule = \Yii::$app->getModule('tag');
        if (!is_null($tagModule)) {
            /** @var \open20\amos\tag\AmosTag $tagModule */
            /** @var ActiveQuery $query */
            $query = CwhTagOwnerInterestMm::find();
            $query->andWhere([
                'interest_classname' => 'simple-choice',
                'classname' => $userProfileClassName,
                'record_id' => $newUserProfileId,
            ]);
            $userInterests = $query->all();
            foreach ($userInterests as $userInterest) {
                /** @var CwhTagOwnerInterestMm $userInterest */
                $userInterest->delete();
                if ($userInterest->hasErrors()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param UserProfile $oldUserProfile
     * @param UserProfile $newUserProfile
     * @param string $userProfileClassName
     * @return bool
     */
    protected function duplicateUserAvatar($oldUserProfile, $newUserProfile, $userProfileClassName)
    {
        $oldUserImage = File::findOne(['model' => $userProfileClassName, 'attribute' => 'userProfileImage', 'item_id' => $oldUserProfile->id]);
        if (!is_null($oldUserImage)) {
            $newUserImage = File::findOne(['model' => $userProfileClassName, 'attribute' => 'userProfileImage', 'item_id' => $newUserProfile->id]);
            if (is_null($newUserImage)) {
                $newUserImage = new File();
                $newUserImage->setAttributes($oldUserImage->attributes);
                $newUserImage->id = null;
            } else {
                $newUserFileId = $newUserImage->id;
                $newUserImage->setAttributes($oldUserImage->attributes);
                $newUserImage->id = $newUserFileId;
            }
            $newUserImage->item_id = $newUserProfile->id;
            $ok = $newUserImage->save(false);
        } elseif (empty($newUserProfile->userProfileImage)) {
            $newUserImage = File::findOne(['model' => $userProfileClassName, 'attribute' => 'userProfileImage', 'item_id' => $newUserProfile->id]);
            if (!is_null($newUserImage)) {
                $ok = true;
            } else {
                $fileImport = new FileImport();
                $ok = $fileImport->importFileForModel(
                    $newUserProfile,
                    'userProfileImage',
                    \Yii::getAlias($this->adminModule->defaultProfileImagePath),
                    false
                );
            }
        }
        return $ok;
    }

    /**
     * @param int $errorPoint
     * @param UserProfile $newUserProfile
     */
    protected function removeDuplicatedUserAvatar($errorPoint, $newUserProfile)
    {
        $newAvatar = $newUserProfile->getUserProfileImage();
        if (!empty($newAvatar)) {
            $this->attachmentsModule->detachFile($newAvatar->id);
        }
    }

    /**
     * @param int $oldUserId
     * @param int $newUserId
     * @param string $userProfileClassName
     * @return bool
     */
    protected function duplicateNotificationConf($oldUserId, $newUserId)
    {
        $ok = true;
        /** @var NotificationConf $notificationConfModel */
        $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
        $oldUserNotificationConf = $notificationConfModel::find()->andWhere(['user_id' => $oldUserId])->one();
        if (!is_null($oldUserNotificationConf)) {
            /** @var NotificationConf $newUserNotificationConf */
            $newUserNotificationConf = $notificationConfModel::find()->andWhere(['user_id' => $newUserId])->one();
            if (is_null($newUserNotificationConf)) {
                $newUserNotificationConf = $this->notifyModule->createModel('NotificationConf');
            }
            $newUserNotificationConf->setAttributes($oldUserNotificationConf->attributes);
            $newUserNotificationConf->user_id = $newUserId;
            $ok = $newUserNotificationConf->save(false);
        }
        return $ok;
    }

    /**
     * @param int $errorPoint
     * @param int $newUserId
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    protected function removeDuplicatedNotificationConf($errorPoint, $newUserId)
    {
        if ($this->notificationsAlreadyRemoved === false) {
            /** @var NotificationConf $notificationConfModel */
            $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
            /** @var NotificationConf $newUserNotificationConf */
            $newUserNotificationConf = $notificationConfModel::find()->andWhere(['user_id' => $newUserId])->one();
            if (!is_null($newUserNotificationConf)) {
                $newUserNotificationConf->delete();
                if ($newUserNotificationConf->hasErrors()) {
                    return false;
                }
            }
            $this->notificationsAlreadyRemoved = true;
        }
        return true;
    }

    /**
     * This method is called at the beginning of the copy operations.
     * @param ChangeUserCreateForm $model
     * @param User $loggedUser
     */
    protected function beforeCreateYourNewProfile($model, $loggedUser)
    {

    }

    /**
     * This method is called at the end of the copy operations and it must returns a boolean.
     * @param ChangeUserCreateForm $model
     * @param User $loggedUser
     * @param User $newUser
     * @return bool
     */
    protected function afterCreateYourNewProfile($model, $loggedUser, $newUser)
    {
        return true;
    }

    /**
     * @param int $errorPoint
     * @param array $params
     * @return bool
     */
    protected function removeAfterCreateNewProfile($errorPoint, $params)
    {
        return true;
    }
}
