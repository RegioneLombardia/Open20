<?php

namespace open20\design\components\bootstrapitalia;

use open20\design\components\bootstrapitalia\interfaces\CheckBoxListTopicsIconInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\InputWidget;

/**
 * 
 */
class SingleSelectionListTopicIcon extends Widget
{

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var CheckBoxListTopicsIconInterface[]
     */
    public $choices = [];

    /**
     * @var CheckBoxListTopicsIconInterface[]
     */
    public $choicesToRemove = [];

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string 
     */
    public $id;

    /**
     * @var string 
     */
    public $linkLabel = null;

    /**
     * @var string 
     */
    public $isActive = true;

    /**
     * 1 - to choice
     * 2 - to remove
     * 
     * @var int 
     */
    public $viewTheme = 1;

    /**
     * @var array the HTML attributes.
     */
    public $options = [];

    /**
     * @var string 
     */
    public $baseIconsUrl = ''; 

    /**
     * @var string 
     */
    public $additionalView = ''; 

    /**
     *
     * @var integer
     */
    public $classContainer;


    /**
     * Undocumented variable
     *
     * @var boolean
     */
    public $isActionDisabled = true;

    public function init()
    {
        parent::init();

        if ($this->choices === null || !is_array($this->choices)) {
            throw new InvalidConfigException("Choices not configured.");
        }


        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        foreach ($this->choices as $topicIcon) {
            if (!($topicIcon instanceof CheckBoxListTopicsIconInterface)) {
                throw new InvalidConfigException("Choices not well configured.");
            }
        }

        foreach ($this->choicesToRemove as $topicIcon) {
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
        if ($this->model === null) {
            $inputId = $this->attribute . '-' . $this->id;
        } else {
            $inputId = BaseHtml::getInputName($this->model, $this->attribute);
        }

        $selectedIds = ArrayHelper::map($this->choicesToRemove, 'id', 'id');
        foreach ($this->choices as $key => $value) {
            if (in_array($value->getId(), $selectedIds)) {
                ArrayHelper::remove($this->choices, $key);
            }
        }

        switch ($this->viewTheme) {
            case 1: 
                $toRender = 'single_selection_list_topics_icon_to_choice';
                break;

            case 2: 
                $toRender = 'single_selection_list_topics_icon_to_remove';
                break;

            default: 
                $toRender = 'single_selection_list_topics_icon_to_choice';
                break;
        }
        
        return $this->render($toRender, [
            'choices' => $this->choices,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'classCardContainer' => $this->classContainer,
            'inputId' => !empty($this->id)? $this->id: $inputId,
            'linkLabel' => $this->linkLabel,
            'baseIconsUrl' => $this->baseIconsUrl,
            'isActionDisabled' => $this->isActionDisabled,
            'additionalView' => $this->additionalView,
            'isActive' => $this->isActive,
        ]);
    }
}
