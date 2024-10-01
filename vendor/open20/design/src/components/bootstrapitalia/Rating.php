<?php

namespace open20\design\components\bootstrapitalia;

use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use yii\helpers\VarDumper;

/**
 *
 */
class Rating extends Widget
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
//
//    /**
//     * @var string label for attribute
//     */
//    public $label = null;

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
     * @var int $starsNumber number of star
     */
    public $starsNumber = 5;

    /**
     * @var int $starsNumber number of star
     */
    public $readonly = false;

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

        if (empty($this->options['class'])) {
            $this->options['class'] = 'form-rating-input';
        }

        return $this->render('bi-form-rating', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'name' => $name,
            'value' => $this->value,
            'options' => $this->options,
            'inputId' => !empty($this->id) ? $this->id : $inputId,
//            'label' => $label,
            'readonly' => $this->readonly,
            'starsNumber' => $this->starsNumber
        ]);
    }

}
