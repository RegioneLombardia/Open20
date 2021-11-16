<?php
/**
 */

namespace yii\gii\components;

use yii\gii\Generator;
use yii\helpers\Json;

/**
 * @since 2.0
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var Generator
     */
    public $model;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $stickyAttributes = $this->model->stickyAttributes();
        if (in_array($this->attribute, $stickyAttributes, true)) {
            $this->sticky();
        }
        $hints = $this->model->hints();
        if (isset($hints[$this->attribute])) {
            $this->hint($hints[$this->attribute]);
        }
        $autoCompleteData = $this->model->autoCompleteData();
        if (isset($autoCompleteData[$this->attribute])) {
            if (is_callable($autoCompleteData[$this->attribute])) {
                $this->autoComplete(call_user_func($autoCompleteData[$this->attribute]));
            } else {
                $this->autoComplete($autoCompleteData[$this->attribute]);
            }
        }
    }

    /**
     * Makes field remember its value between page reloads
     * @return $this the field object itself
     */
    public function sticky()
    {
        $this->options['class'] .= ' sticky';

        return $this;
    }

    /**
     * Makes field auto completable
     * @param array $data auto complete data (array of callables or scalars)
     * @return $this the field object itself
     */
    public function autoComplete($data)
    {
        static $counter = 0;
        $this->inputOptions['class'] .= ' typeahead typeahead-' . (++$counter);
        foreach ($data as &$item) {
            $item = ['word' => $item];
        }
        $this->form->getView()->registerJs("yii.gii.autocomplete($counter, " . Json::htmlEncode($data) . ");");

        return $this;
    }
}
