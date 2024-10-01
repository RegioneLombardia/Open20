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
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ArrayUtility;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\search\MetadataSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="<?= Yii::$app->controller->id ?>-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <?= Html::hiddenInput("enableSearch", "1") ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= Module::tHtml('amoscore', 'Search by'); ?>:
        </h2>
    </div>

    <div class="col-md-4">
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

    <div class="col-md-4">
        <?= $form->field($model, 'key_value')->textInput(['placeholder' => Module::t('amossitemanagement', 'Search by key')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'content')->textInput(['placeholder' => Module::t('amossitemanagement', 'Search by content')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Module::tHtml('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::tHtml('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
