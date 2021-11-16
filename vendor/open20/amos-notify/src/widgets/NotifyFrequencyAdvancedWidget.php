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
use open20\amos\core\models\ModelsClassname;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationconfNetwork;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use kartik\select2\Select2;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

/**
 * Class NotifyFrequencyWidget
 * @package open20\amos\notificationmanager\widgets
 */
class NotifyFrequencyAdvancedWidget extends Widget
{
    const DEFAULT_EMAIL_FREQUENCY = 2;

     /**
     * @var string $layout It's permitted to set {emailNotifyFrequency} and {smsNotifyFrequency}. Default to {emailNotifyFrequency}.
     */
    public $layout = '{generalNotifyFrequency}';
    
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
    private $defaultValueEmail = self::DEFAULT_EMAIL_FREQUENCY;

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
        if(empty($this->notificationConf)){
            /** @var NotificationConf $notificationConfModel */
            $notificConf = $this->notifyModule->createModel('NotificationConf');
            $notificConf->user_id = $this->model->user_id;
            $this->notificationConf = $notificConf;
        }        
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
    public static function contattiSuggeritiEmailSelectorId()
    {
        return 'contatti-suggeriti-email-selector-id';
    }
    
    /**
     * @return string
     */
    public static function contattiSuggeritiEmailSelectorName()
    {
        return 'contatti_suggeriti_email_selector_name';
    }
    
    /**
     * @return string
     */
    /**
     * @return string
     */
    public static function contenutiSuccessoEmailSelectorId()
    {
        return 'contenuti-successo-email-selector-id';
    }
    
    /**
     * @return string
     */
    public static function contenutiSuccessoEmailSelectorName()
    {
        return 'contenuti_successo_email_selector_name';
    }
    
    /**
     * @return string
     */
    /**
     * @return string
     */
    public static function profiloSuccessoEmailSelectorId()
    {
        return 'profilo-successo-email-selector-id';
    }
    
    /**
     * @return string
     */
    public static function profiloSuccessoEmailSelectorName()
    {
        return 'profilo_successo_email_selector_name';
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
            case '{generalNotifyFrequency}':
                return $this->renderGeneralNotifyFrequencySelector();
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
        if (!is_null($this->notificationConf)  && !$this->notificationConf->isNewRecord) {
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

        if (!is_null($this->notificationConf) &&  !$this->notificationConf->isNewRecord) {
            $widgetConf['value'] = $this->notificationConf->sms;
        }
        $html .= Select2::widget($widgetConf);
        $html .= Html::endTag('div');
        return $html;
    }

    
    /**
     * Render the email "suggested contacts" frequency selector.
     * @return string
     */
    public function renderContattiSuggeritiEmailFrequencySelector()
    {
        $html = Html::beginTag('div', $this->getEmailContainerOptions());
        $widgetConf = [
            'id' => self::contattiSuggeritiEmailSelectorId(),
            'name' => self::contattiSuggeritiEmailSelectorName(),
            'data' => NotificationsConfOpt::emailFrequencyValueAndLabels(false),
            'options' => [
                'lang' => substr(\Yii::$app->language, 0, 2),
                'multiple' => false,
                'placeholder' => AmosNotify::t('amosnotify', 'Select/Choose') . '...',
            ]
        ];
        if (!is_null($this->notificationConf)  && !$this->notificationConf->isNewRecord) {
            $widgetConf['value'] = $this->notificationConf->contatti_suggeriti_email;
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
     * Render the email "Successful Content" frequency selector.
     * @return string
     */
    public function renderContenutiSuccessoEmailFrequencySelector()
    {
        $html = Html::beginTag('div', $this->getEmailContainerOptions());
        $widgetConf = [
            'id' => self::contenutiSuccessoEmailSelectorId(),
            'name' => self::contenutiSuccessoEmailSelectorName(),
            'data' => NotificationsConfOpt::emailFrequencyValueAndLabels(false),
            'options' => [
                'lang' => substr(\Yii::$app->language, 0, 2),
                'multiple' => false,
                'placeholder' => AmosNotify::t('amosnotify', 'Select/Choose') . '...',
            ]
        ];
        if (!is_null($this->notificationConf)  && !$this->notificationConf->isNewRecord) {
            $widgetConf['value'] = $this->notificationConf->contenuti_successo_email;
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
     * Render the email "Successful Profile" frequency selector.
     * @return string
     */
    public function renderProfiloSuccessoEmailFrequencySelector()
    {
        $html = Html::beginTag('div', $this->getEmailContainerOptions());
        $widgetConf = [
            'id' => self::profiloSuccessoEmailSelectorId(),
            'name' => self::profiloSuccessoEmailSelectorName(),
            'data' => NotificationsConfOpt::emailFrequencyValueAndLabels(false),
            'options' => [
                'lang' => substr(\Yii::$app->language, 0, 2),
                'multiple' => false,
                'placeholder' => AmosNotify::t('amosnotify', 'Select/Choose') . '...',
            ]
        ];
        if (!is_null($this->notificationConf)  && !$this->notificationConf->isNewRecord) {
            $widgetConf['value'] = $this->notificationConf->profilo_successo_email;
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
     * Render the email notify frequency selector.
     * @return string
     */
    public function renderGeneralNotifyFrequencySelector()
    {
        /** @var NotificationsConfOpt $notificationConfOpt */
        $notificationConfOpt = $this->notifyModule->createModel('NotificationsConfOpt');
        $widgetConfData = $notificationConfOpt::emailFrequencyValueAndLabels();

        $dataProviderNetwork = null;
        $moduleCommunity = \Yii::$app->getModule('community');
        
        if($moduleCommunity){
            $model = new \open20\amos\community\models\Community();
            $query = $model->getUserNetworkQuery($this->model->user_id);
            $dataProviderNetwork = new ActiveDataProvider([
                'query' => $query,
            ]);
//            foreach ($query->all() as $community){
//                pr($community->name);
//            }
        }
        $notificationNetworkValues = [];
        $modelClassname = ModelsClassname::find()->andWhere(['module' => 'community'])->one();
        if($modelClassname) {
            /** @var NotificationconfNetwork $notificationConfNetworkModel */
            $notificationConfNetworkModel = $this->notifyModule->createModel('NotificationconfNetwork');
            $notificationNetwork = $notificationConfNetworkModel::find()
                ->andWhere(['models_classname_id' => $modelClassname->id])
                ->andWhere(['user_id' => $this->model->user_id])->all();
            foreach ($notificationNetwork as $conf){
                $notificationNetworkValues[$conf->record_id] = $conf->email;
            }
        }

        foreach ($this->notificationConf->notificationLanguagePreference as $langPref){
            $notificationLanguagePreferences []= $langPref->language;
        }

        if(empty($this->notificationConf->notificationLanguagePreference)){
            $notificationLanguagePreferences []= \Yii::$app->language;
        }

        $htmlFrequencySelector = $this->renderEmailNotifyFrequencySelector();
        $htmlContattiSuggeritiEmailFrequencySelector = $this->renderContattiSuggeritiEmailFrequencySelector();
        $htmlContenutiSuccessoEmailFrequencySelector = $this->renderContenutiSuccessoEmailFrequencySelector();
        $htmlProfiloSuccessoEmailFrequencySelector = $this->renderProfiloSuccessoEmailFrequencySelector();
        return $this->render('general_notify', [
            'widget' => $this,
            'htmlFrequencySelector' => $htmlFrequencySelector,
            'htmlContattiSuggeritiEmailFrequencySelector' => $htmlContattiSuggeritiEmailFrequencySelector,
            'htmlContenutiSuccessoEmailFrequencySelector' => $htmlContenutiSuccessoEmailFrequencySelector,
            'htmlProfiloSuccessoEmailFrequencySelector' => $htmlProfiloSuccessoEmailFrequencySelector,
            'notificationConf' => $this->notificationConf,
            'notificationNetworkValues' => $notificationNetworkValues,
            'dataProviderNetwork' => $dataProviderNetwork,
            'widgetConfData'=> $widgetConfData,
            'notificationLanguagePreferences' => $notificationLanguagePreferences,
            'module' => $this->notifyModule,
        ]);
    }
}
