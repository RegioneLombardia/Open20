<?php

namespace frontend\modules\socialwall\widgets;

use open20\amos\core\widget\WidgetGraphic;
use frontend\modules\socialwall\utility\social_clients\instagram\InstagramClient;
use frontend\modules\socialwall\utility\social_clients\twitter\TwitterClient;
use kartik\base\Widget;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\VarDumper;

class SocialWallChooseContents extends \yii\base\Widget
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
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        parent::init();
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
        return $this->model instanceof Model && $this->attribute !== null;
    }

    public function run()
    {
        $inputId = $this->options['id'];

        if ($this->model === null) {
            $name = $this->name;
//            $label = $this->label;
        } else {
            $name = BaseHtml::getInputName($this->model, $this->attribute);
//            $label = (is_null($this->label)) ? $this->model->getAttributeLabel($this->attribute) : $this->label;
        }

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


        return $this->render('social-wall-choose-contents', [
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
        ]);
    }

}