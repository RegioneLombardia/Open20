<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use open20\amos\translation\AmosTranslation;
use open20\amos\translation\models\TranslationConf;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
\open20\amos\translation\assets\AmosTranslationAsset::register($this);
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\registry\models\search\ProfessionalProfilesSearch $model
 */
$url = filter_input(INPUT_GET, 'url');
if (!$url) {
    $url = filter_input(INPUT_POST, 'url');
}
$module = $this->context->module;

$this->title = "$classname #{$source[$pk]}: " . AmosTranslation::t('amostranslation', 'translation into') . " $lang";
$prev = \Yii::$app->request->referrer;
if (strpos($prev, '/translation/default/records') !== false) {
    $this->params['breadcrumbs'][] = ['label' => AmosTranslation::t('amostranslation', 'Translate manager'), 'url' => ['/translation']];
    $this->params['breadcrumbs'][] = ['label' => AmosTranslation::t('amostranslation', 'Translate contents'), 'url' => [$url]];
} else {
    $this->params['breadcrumbs'][] = ['label' => AmosTranslation::t('amostranslation', '#Original_content'), 'url' => $prev];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form">
    <?php  echo $this->render('_language_tr', ['model' => $model]);  
  
    $form = ActiveForm::begin(); 
    if (!$model->isNewRecord): 
        ?>
        <?=
        \open20\amos\core\forms\WorkflowTransitionWidget::widget([
            'form' => $form,
            'model' => $model,           
            'workflowId' => $modelClassName::TR_WORKFLOW,
            'classDivIcon' => 'pull-left',
            'classDivMessage' => 'pull-left message',                    
        ]);
        ?>
        <?php
    endif;
    ?>    

    <?= $form->field($model, $pk)->hiddenInput()->label(false); ?>
    <?php foreach ((array) $stringField as $string) { ?>
        <div class="row">
            <div class="col-lg-12">
                <p><?= AmosTranslation::tHtml('amostranslation', 'Source content of') ?> <strong><?= $model->getAttributeLabel($string) ?></strong><?= ($model->language_source)? " ({$model->language_source})" : ''?></p>
                <p class="bordered-box color-source-content"><?= $source[$string] ?></p>
            </div>
            <div class="col-lg-12">                
                <!--<textarea class="form-control" disabled="disabled">-->
                <p class="bordered-box">
                <?= $model->{$string} ?>
                </p>
                <!--</textarea>-->
            </div>
        </div>
    <?php } ?>
    <?php foreach ((array) $textField as $text) { ?>
        <div class="row">
            <div class="col-lg-12">
                <p><?= AmosTranslation::tHtml('amostranslation', 'Source content of') ?> <strong><?= $model->getAttributeLabel($text) ?></strong><?= ($model->language_source)? " ({$model->language_source})" : ''?></p>
                <p class="bordered-box color-source-content">
            <?= ($module->enableRTE && in_array($text, $rteAttributes)) ? \Yii::$app->formatter->asHtml($source[$text]) : $source[$text] ?>
                </p>
            </div>
            <div class="col-lg-12">
                <!--<textarea rows="4" class="form-control" disabled="disabled">-->
                <div class="bordered-box">
                <?=
                ($module->enableRTE && in_array($text, $rteAttributes)) ? \Yii::$app->formatter->asHtml($model->{$text})
                        : $model->{$text}
                ?>
                </div>
                <!--</textarea>-->
            </div>
        </div>
    <?php } ?>

    <div class="form-group">
        <?=  CloseSaveButtonWidget::widget(['model' => $model]);  ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
