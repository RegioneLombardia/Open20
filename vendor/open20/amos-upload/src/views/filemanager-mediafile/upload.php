<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

use kartik\widgets\ActiveForm; // or yii\widgets\ActiveForm
use kartik\widgets\FileInput;
//use kartik\file\FileInput; //if you have only installed 
// yii2-widget-fileinput in isolation
use yii\helpers\Html;

$form = ActiveForm::begin([
    'options'=>['enctype'=>'multipart/form-data'] // important
]);
 
// your fileinput widget for single file upload
echo $form->field($model, 'file')->widget(FileInput::classname(), [
    'options'=>['accept'=>'image/*'],
    'pluginOptions'=>['allowedFileExtensions'=>[
        'jpg',
        'gif',
        'png'
        ]
    ]]);
 
/**
* uncomment for multiple file upload
*
echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
    'options'=>['accept'=>'image/*', 'multiple'=>true],
    'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png']
]);
*
*/
echo Html::submitButton($model->isNewRecord ? 'Upload' : 'Update', [
    'class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
);
ActiveForm::end();
