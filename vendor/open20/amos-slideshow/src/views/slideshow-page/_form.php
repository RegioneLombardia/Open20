<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\slideshow\AmosSlideshow;

/**
 * @var yii\web\View $this
 * @var open20\amos\slideshow\models\SlideshowPage $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="slideshow-pages-form col-xs-12">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('generale'); ?>

    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'slideshow_id', ['options' => ['style' => 'display:none;']])->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'ordinal')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-6">
            <?= $form->field($model, 'pageContent')->widget(TextEditorWidget::className(), [
                'clientOptions' => [
                    'placeholder' => AmosSlideshow::t('amosslideshow', '#page_content_placeholder'),
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'extended_valid_elements' => 'iframe[*]',  
                ]
            ]) ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => AmosSlideshow::tHtml('amosslideshow', 'Generale '),
        'content' => $this->blocks['generale'],
    ];
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>
    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
