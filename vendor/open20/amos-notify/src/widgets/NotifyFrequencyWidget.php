<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\widgets;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use kartik\select2\Select2;
use yii\base\Widget;

/**
 * Class NotifyFrequencyWidget
 * @package open20\amos\notificationmanager\widgets
 */
class NotifyFrequencyWidget extends Widget
{
    /**
     * @var string $layout It's permitted to set {emailNotifyFrequency} and {smsNotifyFrequency}. Default to {emailNotifyFrequency}.
     */
    public $layout = '{emailNotifyFrequency}';
    
    /**
     * @var UserProfile $model
     */
    private $model;
    
    /**
     * @var array $containerOptions Options array
     */
    private $containerOptions = [];
    
    /**
     * @var array $emailContainerOptions Options array
     */
    private $emailContainerOptions = [];
    
    /**
     * @var array $smsContainerOptions Options array
     */
    private $smsContainerOptions = [];
    
    /**
     * @var NotificationConf|null $notificationConf
     */
    private $notificationConf = null;

    /**
     * @var null
     */
    private $defaultValueEmail = null;
    /**
     * @var AmosNotify $notifyModule
     */
    protected $notifyModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->notifyModule = AmosNotify::instance();

        if (is_null($this->model)) {
            throw new \Exception(AmosNotify::t('amosnotify', 'NotifyFrequencyWidget: missing model'));
        }

        /** @var NotificationConf $notificationConfModel */
        $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
        $this->notificationConf = $notificationConfModel::findOne(['user_id' => $this->model->user_id]);
    }
    
    /**
     * @return null
     */
    public function getDefaultValueEmail()
    {
        return $this->defaultValueEmail;
    }

    /**
     * @param null $defaultValueEmail
     */
    public function setDefaultValueEmail(NotificationsConfOpt $defaultValueEmail)
    {
        $this->defaultValueEmail = $defaultValueEmail;
    }
    
    /**
     * @return UserProfile
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * @param UserProfile $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
    
    /**
     * @return array
     */
    public function getContainerOptions()
    {
        return $this->containerOptions;
    }
    
    /**
     * @param array $containerOptions
     */
    public function setContainerOptions($containerOptions)
    {
        $this->containerOptions = $containerOptions;
    }
    
    /**
     * @return array
     */
    public function getEmailContainerOptions()
    {
        return $this->emailContainerOptions;
    }
    
    /**
     * @param array $emailContainerOptions
     */
    public function setEmailContainerOptions($emailContainerOptions)
    {
        $this->emailContainerOptions = $emailContainerOptions;
    }
    
    /**
     * @return array
     */
    public function getSmsContainerOptions()
    {
        return $this->smsContainerOptions;
    }
    
    /**
     * @param array $smsContainerOptions
     */
    public function setSmsContainerOptions($smsContainerOptions)
    {
        $this->smsContainerOptions = $smsContainerOptions;
    }
    
    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }
    
    /**
     * @return string
     */
    public static function emailFrequencySelectorId()
    {
        return 'email-frequency-selector-id';
    }
    
    /**
     * @return string
     */
    public static function emailFrequencySelectorName()
    {
        return 'email_frequency_selector_name';
    }
    
    /**
     * @return string
     */
    public static function smsFrequencySelectorId()
    {
        return 'sms-frequency-selector-id';
    }
    
    /**
     * @return string
     */
    public static function smsFrequencySelectorName()
    {
        return 'sms_frequency_selector_name';
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $content = Html::beginTag('div', $this->getContainerOptions());
        $content .= preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        $content .= Html::endTag('div');
        return $content;
    }
    
    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{emailNotifyFrequency}':
                return $this->renderEmailNotifyFrequencySelector();
            case '{smsNotifyFrequency}':
                return $this->renderSmsNotifyFrequencySelector();
            default:
                return false;
        }
    }
    
    /**
     * Render the email notify frequency selector.
     * @return string
     */
    public function renderEmailNotifyFrequencySelector()
    {
        /** @var NotificationsConfOpt $notificationConfOpt */
        $notificationConfOpt = $this->notifyModule->createModel('NotificationsConfOpt');
        $html = Html::beginTag('div', $this->getEmailContainerOptions());
        $widgetConf = [
            'id' => self::emailFrequencySelectorId(),
            'name' => self::emailFrequencySelectorName(),
            'data' => $notificationConfOpt::emailFrequencyValueAndLabels(),
            'options' => [
                'lang' => substr(\Yii::$app->language, 0, 2),
                'multiple' => false,
                'placeholder' => AmosNotify::t('amosnotify', 'Select/Choose') . '...',
            ]
        ];
        if (!is_null($this->notificationConf)) {
            $widgetConf['value'] = $this->notificationConf->email;
        }else{
            if(!empty($this->defaultValueEmail)){
                $widgetConf ['value'] = $this->defaultValueEmail;
            }else{
                $widgetConf ['value'] = NotificationsConfOpt::EMAIL_DAY;
            }

        }
        $html .= Select2::widget($widgetConf);
        $html .= Html::endTag('div');
        return $html;
    }
    
    /**
     * Render the sms notify frequency selector.
     * @return string
     */
    public function renderSmsNotifyFrequencySelector()
    {
        /** @var NotificationsConfOpt $notificationConfOpt */
        $notificationConfOpt = $this->notifyModule->createModel('NotificationsConfOpt');
        $html = Html::beginTag('div', $this->getSmsContainerOptions());
        $widgetConf = [
            'id' => self::smsFrequencySelectorId(),
            'name' => self::smsFrequencySelectorName(),
            'data' => $notificationConfOpt::smsFrequencyValueAndLabels(),
            'options' => [
                'lang' => substr(\Yii::$app->language, 0, 2),
                'multiple' => false,
                'placeholder' => AmosNotify::t('amosnotify', 'Select/Choose') . '...',
            ]
        ];

        if (!is_null($this->notificationConf)) {
            $widgetConf['value'] = $this->notificationConf->sms;
        }
        $html .= Select2::widget($widgetConf);
        $html .= Html::endTag('div');
        return $html;
    }
}
