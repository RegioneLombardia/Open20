<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\page-content
 * @category   CategoryName
 */

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use yii\redactor\widgets\Redactor;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\PageContent $model
 * @var yii\widgets\ActiveForm $form
 */

// Tab ids
$idTabCard = 'tab-card';

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => Yii::$app->controller->id . '_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'enctype' => 'multipart/form-data', // To load images
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
]);
?>

<div class="<?= Yii::$app->controller->id ?>-form col-xs-12 nop">
    <?php // echo $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    <?php $this->beginBlock($idTabCard); ?>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'section_id')->widget(\kartik\select2\Select2::className(),[
                'data' => \yii\helpers\ArrayHelper::map(\amos\sitemanagement\models\SiteManagementSection::getAvailableSections($model)->all(), 'id', 'name'),
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'content')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => Module::tHtml('amoscore', 'Card'),
        'content' => $this->blocks[$idTabCard],
    ];
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
</div>
<?php ActiveForm::end(); ?>
