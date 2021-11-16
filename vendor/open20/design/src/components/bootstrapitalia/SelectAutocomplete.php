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
use yii\helpers\Json;
use yii\db\Expression;

/**
 * 
 */
class SelectAutocomplete extends Widget {

    /**
     * Source for autocomplete. It must be url to get autocomlete items.
     * @var string
     */
    public $source = '';
    public $fieldname = '';
    public $link = '';
    public $image = '';

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
     * @var string label for attribute
     */
    public $label = null;

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
    public $inline = true;

    /**
     * @var array the list of items for radio input 
     */
    public $items = [];

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init() {
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
    protected function hasModel() {
        return $this->model instanceof Model && $this->attribute !== null;
    }

    public function run() {
        $classInline = '';

        if ($this->model === null) {
            $inputId = $this->attribute . '-id';
        } else {
            $inputId = BaseHtml::getInputId($this->model, $this->attribute) . '-id';
        }

        if ($this->model === null) {
            $name = $this->name;
        } else {
            $name = BaseHtml::getInputName($this->model, $this->attribute);
        }

        if ($this->inline) {
            $classInline = 'form-check-inline';
        }

        if ($this->source) {
            $rows = (new \yii\db\Query())
                    ->select(['source.' . $this->fieldname . ' as text', new Expression('"#" as link')])
                    ->from(['source' => $this->source])
                    ->all();

            $escaped_data = htmlspecialchars(json_encode($rows), ENT_QUOTES, 'UTF-8');
            $dataautocomplete = [$escaped_data];
        }
        return $this->render('select_autocomplete', [
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'name' => $name,
                    'inline' => $this->inline,
                    'options' => $this->options,
                    'classInline' => $classInline,
                    'items' => $this->items,
                    'dataautocomplete' => $dataautocomplete[0],
                    'inputId' => !empty($this->id) ? $this->id : $inputId,
        ]);
    }

}
