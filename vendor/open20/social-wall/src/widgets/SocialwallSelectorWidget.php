<?php

namespace open20\socialwall\widgets;

use open20\amos\core\widget\WidgetGraphic;
use open20\utility\social_clients\instagram\InstagramClient;
use open20\utility\social_clients\twitter\TwitterClient;
use kartik\base\Widget;
use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallLiveChoices;
use open20\socialwall\Module;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\VarDumper;

class SocialwallSelectorWidget extends \yii\base\Widget
{
    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model = null;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;

    /**
     * @var string id html for input
     */
    public $id;

    /**
     * @var string the input value.
     */
    public $value;

    /**
     * @var array the HTML attributes for the input tag.
     */
    public $options = [];

    /**
     * @var array the HTML attributes for the text input.
     */
    public $inputOptions = [];

    /**
     * @var array the HTML attributes for the button.
     */
    public $buttonOptions = [];

    /**
     * @var String Explicit module id for fetching data from db
     */
    public $explicitModuleId = null;

    /**
     * @var int Explicit module record id for fetching data from db
     */
    public $explicitModuleRecordId = null;

    public $form;
    public $formField;

    private $tags;

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();

        if (!$this->hasModel()) {
            throw new InvalidConfigException("'model' property must be specified and not null.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
    }

    public $enabledSocialFeeds = [
        'instagram',
        'twitter',
    ];

    public $socialLabels = [
        'instagram' => 'Instagram',
        'twitter' => 'Twitter',
    ];

    /**
     * @return bool whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return !empty($this->model) && ($this->model instanceof Model);
    }

    private $socialwallModel;
    private function getSocialwallSelectionModels() {
        $found = false;

        // Check if model is enabled in socialwall config
        if(!empty(\Yii::$app->getModule(Module::getModuleName())) &&
            !empty(\Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable) &&
            is_array(\Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable)) {
            if(array_key_exists(get_class($this->model), \Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable)) {
                $moduleMappedId = \Yii::$app->getModule(Module::getModuleName())->modulesIdClassMapEnable[get_class($this->model)];

                $this->socialwallModel = Socialwall::findOne(['module_id' => $moduleMappedId, 'module_record_id' => $this->model]);

                $found = true;
            }
            if (!empty($this->explicitModuleId) && !empty($this->explicitModuleRecordId)) {
                $this->socialwallModel = Socialwall::findOne(['module_id' => $this->explicitModuleId, 'module_record_id' => $this->explicitModuleRecordId]);

                $found = true;
            }
        }

        // Find related socialwall data
        if($found) {
            if(!empty($this->socialwallModel)) {
                $this->socialwallModel->socialwallType = $this->socialwallModel->type;
                $this->socialwallModel->socialwallLiveChoices = ArrayHelper::getColumn(SocialwallLiveChoices::findAll(['socialwall_id' => $this->socialwallModel->id]), 'socialwall_tokens_id');
                $this->socialwallModel->socialwallLiveTags = SocialwallLiveChoices::findOne(['socialwall_id' => $this->socialwallModel->id])->keywords;
            } else {
                $this->socialwallModel = new Socialwall();
                $this->socialwallModel->update = true;
            }
        }

        return $found;
    }

    public function run()
    {
        if($this->getSocialwallSelectionModels()) {
            $inputId = $this->options['id'];

            $name = BaseHtml::getInputName($this->model, $this->attribute);

            if (!empty($this->options['value'])) {
                $this->value = $this->options['value'];
            }

            // default values
            if (!isset($this->options['required'])) {
                $isRequired = isset($this->model) ? $this->model->isAttributeRequired($this->attribute) : false;
                $this->options['required'] = $isRequired;
            }

            if (empty($this->options['inputClass'])) {
                $this->options['inputClass'] = '';
            }


            return $this->render('socialwall-selector', [
                'model' => $this->model,
                'attribute' => $this->attribute,
                'name' => $name,
                'value' => $this->value,
                'options' => $this->options,
                'inputOptions' => $this->inputOptions,
                'buttonOptions' => $this->buttonOptions,
                'inputId' => !empty($this->id) ? $this->id : $inputId,
                'enabledSocialFeeds' => $this->enabledSocialFeeds,
                'socialLabels' => $this->socialLabels,
                'form' => $this->form,
                'formField' => $this->formField,
                'explicitModuleId' => $this->explicitModuleId,
                'explicitModuleRecordId' => $this->explicitModuleRecordId,

                'socialwallModel' => $this->socialwallModel,
            ]);
        } else {
            return "";
        }
    }

}