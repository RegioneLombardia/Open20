<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\metadata
 * @category   CategoryName
 */

use amos\sitemanagement\models\MetadataType;
use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\utilities\ArrayUtility;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\Metadata $model
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
            <?= $form->field($model, 'metadata_type_id')->widget(Select::className(), [
                'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(MetadataType::find()->asArray()->all(), 'id', 'type'), 'amossitemanagement'),
                'options' => [
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'multiple' => false,
                    'placeholder' => Module::t('amoscore', 'Select/Choose') . '...',
                    'data-model' => 'metadata_type',
                    'data-field' => 'type'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'key_value')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'content')->textarea(['rows' => 4]) ?>
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
