<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

namespace open20\design\components\bootstrapitalia;

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\ActiveField as yiiActiveField;
use open20\design\components\bootstrapitalia\Select;

class ActiveField extends yiiActiveField
{

    /**
     * override of error options
     * @var string[]
     */
    const ERROROptions = ['class' => 'invalid-feedback d-block server-side-error'];
    
    
    public function init() {
        parent::init();
        $this->errorOptions = self::ERROROptions;
    }

    public function inputCalendar($options = [], $inputFormat = "d/m/Y")
    {
        $this->template = "{input}\n{hint}\n{error}";
        $Id             = Html::getInputId($this->model, $this->attribute);
        $required       = ($this->model->isAttributeRequired($attribute)) ? 'required' : '';
        $name           = isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $this->attribute);
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }
        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $label = Html::activeLabel($this->model, $this->attribute, ['class' => "control-label"]);

        $html = "<div class='it-datepicker-wrapper'>".
            "<input class='form-control it-date-datepicker' name='".$name."' id='".$Id."' type='text'
                      value='".$value."' placeholder='".(isset($options['placeholder']) ? $options['placeholder'] : $inputFormat)."' ".$required.">";

        $html                   = $html.$label."<div class='invalid-feedback'>Per favore inserisci <?= $attribute ?>.</div></div>";
        $this->parts['{input}'] = $html;

        $js = <<< JS
            $('#{$Id}').datepicker({
                inputFormat: ["{$inputFormat}"],
                outputFormat: '{$inputFormat}',
              });
JS;
        $this->form->view->registerJs($js);
        return $this;
    }

    public function textInput($options = [])
    {

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        $input = new Input(['model' => $this->model, 'attribute' => $this->attribute, 'label' => $label, 'options' => $options]);

        $this->template = "{input}\n{hint}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $input->value           = $value;
        $this->parts['{input}'] = $input->run();
        return $this;
    }

    public function searchInput($options = [])
    {

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        $input = new Input(['model' => $this->model, 'attribute' => $this->attribute, 'label' => $label, 'options' => $options]);

        $this->template = "{input}\n{hint}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $input->value           = $value;
        $input->type            = 'search';
        $this->parts['{input}'] = $input->run();
        return $this;
    }

    public function textarea($options = [])
    {

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        $input = new Input(['model' => $this->model, 'attribute' => $this->attribute, 'label' => $label, 'options' => $options]);

        $this->template = "{input}\n{hint}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $input->value           = $value;
        $input->type            = 'textarea';
        $this->parts['{input}'] = $input->run();
        return $this;
    }

    /**
     *
     * @param type $options
     * @return type
     */
    public function passwordInput($options = [])
    {

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        $input          = new Input(['model' => $this->model, 'attribute' => $this->attribute, 'label' => $label, 'options' => $options]);
        $this->template = "{input}\n{hint}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $input->value           = $value;
        $input->type            = 'password';
        $this->parts['{input}'] = $input->run();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function radioList($items, $options = [])
    {
        $radio          = new RadioList(['model' => $this->model, 'attribute' => $this->attribute, 'items' => $items, 'options' => $options]);
        $this->template = isset($options['template'])? $options['template']: "{label}{input}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $radio->value           = $value;
        $this->parts['{input}'] = $radio->run();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function select($items, $options = [])
    {
        $this->template = "{input}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }
        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }
        $select         = new Select(['model' => $this->model, 'attribute' => $this->attribute, 'label' => $label, 'items' => $items, 'options' => $options]);

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $select->value          = $value;
        $this->parts['{input}'] = $select->run();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        }

        $checkbox       = new CheckBox(['model' => $this->model, 'attribute' => $this->attribute, 'label' => $label, 'options' => $options]);
        $this->template = "{input}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $checkbox->value        = $value;
        $this->parts['{input}'] = $checkbox->run();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkboxList($items, $options = [])
    {
        $checkbox       = new CheckBox(['model' => $this->model, 'attribute' => $this->attribute, 'items' => $items,
            'options' => $options]);
        $this->template = "{label}{input}\n{error}";
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        $checkbox->value        = $value;
        $this->parts['{input}'] = $checkbox->run('checkboxList');
        return $this;
    }
}