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
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\search\PageContentSearch $model
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
        <?= $form->field($model, 'title')->textInput(['placeholder' => Module::t('amossitemanagement', 'Search by title')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'tag')->textInput(['placeholder' => Module::t('amossitemanagement', 'Search by tag')]) ?>
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
