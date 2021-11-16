<?php

namespace open20\design\components\bootstrapitalia;

use open20\design\components\bootstrapitalia\interfaces\CheckBoxListTopicsIconInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\InputWidget;

/**
 * 
 */
class CheckBoxListTopicsIcon extends Widget
{

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var CheckBoxListTopicsIconInterface[]
     */
    public $choices;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string 
     */
    public $baseIconsUrl = ''; 

    /**
     * @var array the HTML attributes.
     */
    public $options = [];

    /**
     *
     * @var integer
     */
    public $classContainer;

    public function init()
    {
        parent::init();

        if ($this->model === null || !$this->hasModel()) {
            throw new InvalidConfigException("Model not configured.");
        }

        if ($this->choices === null || !is_array($this->choices)) {
            throw new InvalidConfigException("Choices not configured.");
        }


        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        foreach($this->choices as $topicIcon) {
            if (!($topicIcon instanceof CheckBoxListTopicsIconInterface)) {
                throw new InvalidConfigException("Choices not well configured.");
            }
        }

    }

    /**
     * @return bool whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model;
    }

    public function run()
    {
        $inputName = BaseHtml::getInputName($this->model, $this->attribute);

        return $this->render('check_box_list_topics_icon', [
            'choices' => $this->choices,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'inputName' => $inputName,
            'baseIconsUrl' => $this->baseIconsUrl,
            'classCardContainer' => $this->classContainer
        ]);
    }
}