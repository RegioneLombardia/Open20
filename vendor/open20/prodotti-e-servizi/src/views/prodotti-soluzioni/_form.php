<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views 
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\prodottiservizi\Module;

/**
* @var yii\web\View $this
* @var open20\prodottiservizi\models\ProdottiSoluzioni $model
* @var yii\widgets\ActiveForm $form
*/

$prodottiServiziModule = Yii::$app->getModule('prodotti-e-servizi');
?>
<div class="prodotti-soluzioni-form col-xs-12 nop">

    <?php     $form = ActiveForm::begin([
    'options' => [
    'id' => 'prodotti-soluzioni_' . ((isset($fid))? $fid : 0),
    'data-fid' => (isset($fid))? $fid : 0,
    'data-field' => ((isset($dataField))? $dataField : ''),
    'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
    'class' => ((isset($class))? $class : '')
    ]
    ]);
     ?>

    <div class="row">
        <div class="m-t-20">
            <div class="content-form-cmsDashSidebarItem">
                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= Module::t('prodottiservizi', 'Informazioni di base') ?></h2>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'icon')->textInput(['maxlength' => true])
                                ->hint("<span class='mdi mdi-information'></span>" . Module::t('prodottiservizi', '#HINT_ICON') . "<a href='{$prodottiServiziModule->materialLink}' target='_blank'>{$prodottiServiziModule->materialLink}</a>") ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <?= RequiredFieldsTipWidget::widget(); ?>
                    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-4 col xs-12"></div>
            </div>
        </div>
        <!--<div class="clearfix"></div>-->
    </div>
</div>
