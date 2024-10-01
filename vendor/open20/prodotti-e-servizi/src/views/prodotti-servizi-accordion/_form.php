<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views 
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;

/**
* @var yii\web\View $this
* @var open20\prodottiservizi\models\ProdottiServiziAccordion $model
* @var yii\widgets\ActiveForm $form
*/


 ?>
<div class="prodotti-servizi-accordion-form col-xs-12 nop">

    <?php     $form = ActiveForm::begin([
    'options' => [
    'id' => 'prodotti-servizi-accordion_' . ((isset($fid))? $fid : 0),
    'data-fid' => (isset($fid))? $fid : 0,
    'data-field' => ((isset($dataField))? $dataField : ''),
    'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
    'class' => ((isset($class))? $class : '')
    ]
    ]);
     ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    
    <div class="row">
        <div class="col-xs-12">
            <h2 class="subtitle-form">Settings</h2>
            <div class="col-md-8 col xs-12">
                <?= RequiredFieldsTipWidget::widget(); ?>
                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>
    </div>

</div>
