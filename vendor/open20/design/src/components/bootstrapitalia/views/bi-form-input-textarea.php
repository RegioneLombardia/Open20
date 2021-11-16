<?php

/**
 * @param string $inputId
 * @param Model $model
 */
use yii\helpers\Html;


$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';
$placeholder = (isset($options['placeholder'])) ? 'placeholder="' . $options['placeholder'] . '"' : false;
$rows = (isset($options['rows'])) ? $options['rows'] : '3';

?>

<textarea id="<?= $inputId ?>" name="<?= $name ?>" rows="<?= $rows ?>" <?=$placeholder?> $required><?=$value?></textarea>
<label for="<?= $inputId ?>"><?= $label ?><?= ($required) ? Html::tag('span','*',['class'=>'required-asterisk']) : ''?></label>
