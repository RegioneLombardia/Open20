<?php

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use amos\sitemanagement\Module;


/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\search\SiteManagementContainerElementMmSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="site-management-container-element-mm-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);

    echo Html::hiddenInput("enableSearch", "1");
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>

    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'id') ?></div><div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'container_id') ?></div><div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'element_id') ?></div><div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'created_at') ?></div><div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'updated_at') ?></div>    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Module::t('amossitemanagement', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('amossitemanagement', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
<!--a><p class="text-center">Ricerca avanzata<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>

</div>
