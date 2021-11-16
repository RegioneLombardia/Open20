<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var open20\amos\upload\models\FilemanagerMediafile $model
* @var yii\widgets\ActiveForm $form
*/


?>

<div class="filemanager-mediafile-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);  ?>
    <div class="form-actions">

        <?=    Html::submitButton( $model->isNewRecord ?
        Yii::t('amosupload', 'Crea') :
        Yii::t('amosupload', 'Aggiorna'),
        [
        'class' => $model->isNewRecord ?
        'btn btn-success' :
        'btn btn-primary'
        ]); ?>

    </div>


    
        
        <?php  $this->beginBlock('generale'); ?>
                <div class="clearfix"></div>
        <?php  $this->endBlock('generale'); ?>

        <?php   $itemsTab[] = [
        'label' => Yii::t('amosupload', 'Generale '),
        'content' => $this->blocks['generale'],
        ];
         ?>    

    <?=  Tabs::widget(
    [
    'encodeLabels' => false,
    'items' => $itemsTab
    ]
    );
     ?>
    <?php  ActiveForm::end();  ?>
</div>
