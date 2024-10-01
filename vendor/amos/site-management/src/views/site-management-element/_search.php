<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\site-management-element
 * @category   CategoryName
 */

use amos\sitemanagement\Module;
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\search\SiteManagementElementSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="site-management-element-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= Module::t('amossitemanagement', 'Search by') ?>:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <?= Html::hiddenInput("enableSearch", "1"); ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>


    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'template_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\amos\sitemanagement\models\SiteManagementTemplate::find()->all(), 'id', 'name'),
                'options' => [
                        'placeholder' => Module::t('amossitemanagement', 'Select...')
                ],
            'pluginOptions' => [
                    'allowClear' => true,
            ]
        ]) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'title') ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'description') ?>
    </div>


    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Module::t('amossitemanagement', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('amossitemanagement', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
