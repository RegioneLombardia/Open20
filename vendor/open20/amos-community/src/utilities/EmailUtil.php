<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\utilities
 * @category   CategoryName
 */

namespace open20\amos\community\utilities;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\AmosCommunity;
use open20\amos\community\controllers\CommunityController;
use open20\amos\community\models\Community;
use open20\amos\core\controllers\CrudController;

/**
 * Class EmailUtil
 * @package open20\amos\community\utilities
 */
class EmailUtil
{
    const REGISTRATION_NOTIFICATION = 1;
    const REGISTRATION_REQUEST = 2;
    const INVITATION = 3;
    const ACCEPT_INVITATION = 4;
    const WELCOME = 5;
    const CHANGE_ROLE = 6;
    const REJECT_INVITATION = 7;
    const REGISTRATION_REJECTED = 8;
    const DELETED_COMMUNITY = 9;
    
    /**
     * @var string $status
     */
    public $type = '';
    
    /**
     * @var string $role
     */
    public $role = '';
    
    /**
     * @var Community $community
     */
    public $community;
    
    /**
     * @var string $userName
     */
    public $userName = '';
    
    /**
     * @var string $managerName
     */
    public $managerName = '';
    
    /**
     * @var string url
     */
    public $url = '';
    
    /**
     * @var bool $isCommunityContext
     */
    public $isCommunityContext = true;
    
    /**
     * @var string $contextLabel
     */
    public $contextLabel = '';
    
    /**
     * @var string $appName
     */
    private $appName = '';
    
    /**
     * @var int user_id
     */
    private $user_id;
    
    /**
     * EmailUtil constructor.
     * @param $type
     * @param $role
     * @param Community $community
     * @param $userName
     * @param $managerName
     * @param null $url
     * @param null $user_id
     * @throws \yii\base\InvalidConfigException
     */
    function __construct($type, $role, Community $community, $userName, $managerName, $url = null, $user_id = null)
    {
        $this->type = $type;
        $this->role = $role;
        $this->community = $community;
        $this->userName = $userName;
        $this->managerName = $managerName;
        $this->user_id = $user_id;
        
        if ($this->community->context != Community::className()) {
            //it's a community created by another plugin
            $this->isCommunityContext = false;
            $context = \Yii::createObject($this->community->context);
            $model = $context->findOne(['community_id' => $this->community->id]);
            //mail links will redirect to the model that created the community, not to the community itself
        }
        
        if (!isset($model)) {
            $model = $community;
        }
        $this->contextLabel = $model->getGrammar()->getArticleSingular() . " " . $model->getGrammar()->getModelSingularLabel();
        
        if (isset($url)) {
            $this->url = $url;
        } else {
            $this->url = \Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl());
        }
        $this->appName = \Yii::$app->name;
    }
    
    /**
     *
     * @return string
     */
    public function getSubject()
    {
        /** @var CrudController $controller */
        $controller = \Yii::$app->controller;
        $pathEmail = '@vendor/open20/amos-community/src/views/community/';
        $subject = '';
        $moduleCommmunity = \Yii::$app->getModule(AmosCommunity::getModuleName());
        $pathMailList = [];
        if ($moduleCommmunity && !empty($moduleCommmunity->htmlMailSubject)) {
            foreach ($moduleCommmunity->htmlMailSubject as $type => $path) {
                if (!empty($this->getNumTypeEmail($type))) {
                    $pathMailList[$this->getNumTypeEmail($type)] = $path;
                }
            }
        }
        
        switch ($this->type) {
            case self::REGISTRATION_NOTIFICATION :
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::REGISTRATION_NOTIFICATION]) ? $pathMailList[self::REGISTRATION_NOTIFICATION] : $pathEmail .'email' . DIRECTORY_SEPARATOR . 'registration-notification-subject', ['util' => $this, 'utilAppName' => $this->appName], $this->user_id);
                break;
            case self::REGISTRATION_REQUEST:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::REGISTRATION_REQUEST]) ? $pathMailList[self::REGISTRATION_REQUEST] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'registration-request-subject', ['util' => $this], $this->user_id);
                break;
            case self::INVITATION:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::INVITATION]) ? $pathMailList[self::INVITATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'invitation-subject', ['util' => $this], $this->user_id);
                break;
            case self::ACCEPT_INVITATION:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::ACCEPT_INVITATION]) ? $pathMailList[self::ACCEPT_INVITATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'accept-invitation-subject', ['util' => $this], $this->user_id);
                break;
            case self::REJECT_INVITATION:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::REJECT_INVITATION]) ? $pathMailList[self::REJECT_INVITATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'reject-invitation-subject', ['util' => $this], $this->user_id);
                break;
            case self::REGISTRATION_REJECTED:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::REGISTRATION_REJECTED]) ? $pathMailList[self::REGISTRATION_REJECTED] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'registration-rejected-subject', ['util' => $this], $this->user_id);
                break;
            case self::WELCOME:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::WELCOME]) ? $pathMailList[self::WELCOME] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'welcome-subject', ['util' => $this], $this->user_id);
                break;
            case self::CHANGE_ROLE:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::CHANGE_ROLE]) ? $pathMailList[self::CHANGE_ROLE] : $pathEmail .'email' . DIRECTORY_SEPARATOR . 'change-role-subject', ['util' => $this], $this->user_id);
                break;
            case self::DELETED_COMMUNITY:
                $subject = $controller->renderMailPartial(!empty($pathMailList[self::DELETED_COMMUNITY]) ? $pathMailList[self::DELETED_COMMUNITY] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'deleted-community-subject', ['util' => $this], $this->user_id);
                break;
        }
        return $subject;
    }
    
    /**
     *
     * @return string the rendering result.
     */
    public function getText()
    {
        /** @var $controller CommunityController */
        $controller = \Yii::$app->controller;
        $pathEmail = '@vendor/open20/amos-community/src/views/community/';
        $moduleCommmunity = \Yii::$app->getModule(AmosCommunity::getModuleName());
        $pathMailList = [];
        if ($moduleCommmunity && !empty($moduleCommmunity->htmlMailContent)) {
            foreach ($moduleCommmunity->htmlMailContent as $type => $path) {
                if (!empty($this->getNumTypeEmail($type))) {
                    $pathMailList[$this->getNumTypeEmail($type)] = $path;
                }
            }
        }
        $profile = UserProfile::find()->andWhere(['user_id' => $this->user_id])->one();
        
        $text = '';
        switch ($this->type) {
            case self::REGISTRATION_NOTIFICATION :
                $text = $controller->renderMailPartial(!empty($pathMailList[self::REGISTRATION_NOTIFICATION]) ? $pathMailList[self::REGISTRATION_NOTIFICATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'registration-notification',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::REGISTRATION_REQUEST:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::REGISTRATION_REQUEST]) ? $pathMailList[self::REGISTRATION_REQUEST] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'registration-request',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::INVITATION:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::INVITATION]) ? $pathMailList[self::INVITATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'invitation',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::ACCEPT_INVITATION:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::ACCEPT_INVITATION]) ? $pathMailList[self::ACCEPT_INVITATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'accept-invitation',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::REJECT_INVITATION:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::REJECT_INVITATION]) ? $pathMailList[self::REJECT_INVITATION] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'reject-invitation',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::REGISTRATION_REJECTED:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::REGISTRATION_REJECTED]) ? $pathMailList[self::REGISTRATION_REJECTED] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'registration-rejected',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::WELCOME:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::WELCOME]) ? $pathMailList[self::WELCOME] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'welcome',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::CHANGE_ROLE:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::CHANGE_ROLE]) ? $pathMailList[self::CHANGE_ROLE] : $pathEmail. 'email' . DIRECTORY_SEPARATOR . 'change-role',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
            case self::DELETED_COMMUNITY:
                $text = $controller->renderMailPartial(!empty($pathMailList[self::DELETED_COMMUNITY]) ? $pathMailList[self::DELETED_COMMUNITY] : $pathEmail . 'email' . DIRECTORY_SEPARATOR . 'deleted-community',
                    ['util' => $this, 'userName' => $this->userName, 'profile' => $profile], $this->user_id);
                break;
        }
        return $text;
    }
    
    /**
     * @param $type
     * @return int|null
     */
    public function getNumTypeEmail($type)
    {
        switch ($type) {
            case 'registration-notification':
                return self::REGISTRATION_NOTIFICATION;
                break;
            case 'registration-request':
                return self::REGISTRATION_REQUEST;
                break;
            case 'invitation':
                return self::INVITATION;
                break;
            case 'accept-invitation':
                return self::ACCEPT_INVITATION;
                break;
            case 'reject-invitation':
                return self::REJECT_INVITATION;
                break;
            case 'registration-rejects':
                return self::REGISTRATION_REJECTED;
                break;
            case 'welcome':
                return self::WELCOME;
                break;
            case 'change-role':
                return self::CHANGE_ROLE;
                break;
            case 'deleted-community':
                return self::DELETED_COMMUNITY;
                break;
        }
        return null;
    }
}
