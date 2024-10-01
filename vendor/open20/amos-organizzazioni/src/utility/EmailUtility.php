<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\utility
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\core\utilities\Email;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\Module;

/**
 * Class EmailUtility
 * @package open20\amos\organizzazioni\utility
 */
class EmailUtility
{
    const REGISTRATION_NOTIFICATION = 1;
    const REGISTRATION_REQUEST = 2;
    const INVITATION = 3;
    const ACCEPT_INVITATION = 4;
    const WELCOME = 5;
    const CHANGE_ROLE = 6;
    const REJECT_INVITATION = 7;
    const REGISTRATION_REJECTED = 8;
    const DELETED_ORGANIZATION = 9;

    const MAIL_PART_SUBJECT = 'subject';
    const MAIL_PART_TEXT = 'text';

    /**
     * @var CrudController $controller
     */
    protected $controller = null;

    /**
     * @var Module|null $organizationsModule
     */
    protected $organizationsModule = null;

    /**
     * @var string $status
     */
    public $type = '';

    /**
     * @var string $role
     */
    public $role = '';

    /**
     * @var Profilo|ProfiloSedi $model
     */
    public $model;

    /**
     * @var \ReflectionClass $reflectionClass
     */
    private $reflectionClass;

    /**
     * @var string $userName
     */
    public $userName = '';

    /**
     * @var string $refereeName
     */
    public $refereeName = '';

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
    public $appName = '';

    /**
     * @var int user_id
     */
    protected $user_id;

    /**
     * @var array $emailConfs
     */
    protected $emailConfs = [];

    /**
     * @var string $pathEmail
     */
    protected $pathEmail = '';

    /**
     * @var array $pathMailList
     */
    protected $pathMailList = [];

    /**
     * EmailUtility constructor.
     * @param string $type
     * @param string $role
     * @param Profilo|ProfiloSedi $model
     * @param string $userName
     * @param string $refereeName
     * @param string|null $url
     * @param int|null $user_id
     * @throws \ReflectionException
     */
    function __construct($type, $role, $model, $userName, $refereeName, $url = null, $user_id = null)
    {
        $this->controller = \Yii::$app->controller;
        $this->organizationsModule = Module::instance();
        $this->type = $type;
        $this->role = $role;
        $this->model = $model;
        $this->reflectionClass = new \ReflectionClass($this->model);
        $this->userName = $userName;
        $this->refereeName = $refereeName;
        $this->user_id = $user_id;
        /** @var ModelGrammarInterface $grammar */
        $grammar = $this->model->getGrammar();
        if ($this->model instanceof Profilo) {
            $this->pathEmail = '@vendor/open20/amos-organizzazioni/src/views/profilo/';
            $articleSingular = $grammar->getArticleSingular();
            $this->contextLabel = $articleSingular . (substr($articleSingular, -1) == "'" ? '' : ' ') . $grammar->getModelSingularLabel();
        } elseif ($this->model instanceof ProfiloSedi) {
            $this->pathEmail = '@vendor/open20/amos-organizzazioni/src/views/profilo-sedi/';
            $this->contextLabel = $grammar->getArticleSingular() . ' ' . $grammar->getModelSingularLabel();
        }
        $this->appName = \Yii::$app->name;

        $this->emailConfs = [
            self::REGISTRATION_NOTIFICATION => [
                'subject' => 'registration-notification-subject',
                'text' => 'registration-notification'
            ],
            self::REGISTRATION_REQUEST => [
                'url' => '/myactivities/my-activities/index',
                'subject' => 'registration-request-subject',
                'text' => 'registration-request'
            ],
            self::INVITATION => [
                'url' => '/myactivities/my-activities/index',
                'subject' => 'invitation-subject',
                'text' => 'invitation'
            ],
            self::ACCEPT_INVITATION => [
                'subject' => 'accept-invitation-subject',
                'text' => 'accept-invitation'
            ],
            self::WELCOME => [
                'url' => '/dashboard',
                'subject' => 'welcome-subject',
                'text' => 'welcome'
            ],
            self::CHANGE_ROLE => [
                'subject' => 'change-role-subject',
                'text' => 'change-role'
            ],
            self::REJECT_INVITATION => [
                'subject' => 'reject-invitation-subject',
                'text' => 'reject-invitation'
            ],
            self::REGISTRATION_REJECTED => [
                'subject' => 'registration-rejected-subject',
                'text' => 'registration-rejected'
            ],
            self::DELETED_ORGANIZATION => [
                'subject' => 'deleted-organization-subject',
                'text' => 'deleted-organization'
            ]
        ];

        if (isset($url)) {
            $this->url = $url;
        } elseif (isset($this->emailConfs[$type]['url'])) {
            $this->url = \Yii::$app->urlManager->createAbsoluteUrl($this->emailConfs[$type]['url']);
        } else {
            $this->url = \Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl());
        }
    }

    /**
     * @param string $type
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
                return self::DELETED_ORGANIZATION;
                break;
        }
        return null;
    }

    /**
     * @param int $typeNum
     * @return int|null
     */
    public function getTextTypeEmail($typeNum)
    {
        switch ($typeNum) {
            case self::REGISTRATION_NOTIFICATION:
                return 'registration-notification';
                break;
            case self::REGISTRATION_REQUEST:
                return 'registration-request';
                break;
            case self::INVITATION:
                return 'invitation';
                break;
            case self::ACCEPT_INVITATION:
                return 'accept-invitation';
                break;
            case self::REJECT_INVITATION:
                return 'reject-invitation';
                break;
            case self::REGISTRATION_REJECTED:
                return 'registration-rejects';
                break;
            case self::WELCOME:
                return 'welcome';
                break;
            case self::CHANGE_ROLE:
                return 'change-role';
                break;
            case self::DELETED_ORGANIZATION:
                return 'deleted-organization';
                break;
        }
        return null;
    }

    /**
     * This method returns all configurations for emails.
     * @return array
     */
    public function getEmailTypesConf()
    {
        return $this->emailConfs;
    }

    /**
     * @return array
     */
    public function getAllEmailTypes()
    {
        return array_keys($this->emailConfs);
    }

    /**
     * @param string $mailPartType
     */
    protected function makePathMailList($mailPartType)
    {
        $moduleProperty = '';
        if ($mailPartType == static::MAIL_PART_SUBJECT) {
            $moduleProperty = 'htmlMailSubject';
        } elseif ($mailPartType == static::MAIL_PART_TEXT) {
            $moduleProperty = 'htmlMailContent';
        }
        if (
            !empty($moduleProperty) &&
            !is_null($this->organizationsModule) &&
            !empty($this->organizationsModule->{$moduleProperty}) &&
            isset($this->organizationsModule->{$moduleProperty}[$this->reflectionClass->getShortName()]) &&
            !empty($this->organizationsModule->{$moduleProperty}[$this->reflectionClass->getShortName()])
        ) {
            $confs = $this->organizationsModule->htmlMailSubject[$this->reflectionClass->getShortName()];
            foreach ($confs as $type => $path) {
                if (!empty($this->getNumTypeEmail($type))) {
                    $this->pathMailList[$this->getNumTypeEmail($type)] = $path;
                }
            }
        }
    }

    /**
     * This method renders a part of a notification email.
     * @param int $typeNum
     * @param string $mailPartType
     * @param UserProfile|null $profile
     * @return string
     */
    protected function renderMailPart($typeNum, $mailPartType, $profile = null)
    {
        if (!isset($this->emailConfs[$typeNum])) {
            return '';
        }
        $viewToRender = (!empty($this->pathMailList[$typeNum]) ?
            $this->pathMailList[$typeNum] :
            $this->pathEmail . 'email' . DIRECTORY_SEPARATOR . $this->emailConfs[$typeNum][$mailPartType]);
        $viewParams = ['util' => $this];
        if (!is_null($profile)) {
            $viewParams['profile'] = $profile;
        }
        $text = $this->controller->renderMailPartial($viewToRender, $viewParams, $this->user_id);
        return $text;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $this->makePathMailList(static::MAIL_PART_SUBJECT);
        return $this->renderMailPart($this->type, static::MAIL_PART_SUBJECT);
    }

    /**
     * @return string the rendering result.
     */
    public function getText()
    {
        $this->makePathMailList(static::MAIL_PART_TEXT);
        $profile = UserProfile::find()->andWhere(['user_id' => $this->user_id])->one();
        return $this->renderMailPart($this->type, static::MAIL_PART_TEXT, $profile);
    }

    /**
     * This method send an email. If "from" param is null the method uses the "email-assistenza" platform param.
     * If "email-assistenza" platform param is not set the method uses the assistance email.
     * @param string $from
     * @param string|array $to
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     * @return bool
     */
    public function sendMail($from, $to, $subject, $text, $files, $bcc)
    {
        if (is_null($from)) {
            if (isset(\Yii::$app->params['email-assistenza'])) {
                // Use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            } else {
                $assistance = isset(\Yii::$app->params['assistance']) ? \Yii::$app->params['assistance'] : [];
                $from = isset($assistance['email']) ? $assistance['email'] : '';
            }
        }
        if (is_string($to)) {
            $tos = [$to];
        }
        return Email::sendMail($from, $tos, $subject, $text, $files, $bcc, [], 0, false);
    }

    /**
     * This method returns an array of the legal representative and
     * operative referee emails of the organization passed by param.
     * @param int $organizationId
     * @return UserProfile[]|bool
     */
    public function getOrganizationRefereesMailList($organizationId)
    {
        $organizationReferees = OrganizzazioniUtility::getOrganizationReferees($organizationId);
        $emails = [];
        foreach ($organizationReferees as $userProfile) {
            $emails[] = $userProfile->user->email;
        }
        return $emails;
    }
}
