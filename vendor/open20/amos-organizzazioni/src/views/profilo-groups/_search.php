<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\organizzazioni\Module;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\search\ProfiloGroupsSearch $searchModel
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="profilo-groups-search element-to-toggle" data-toggle-element="form-search">
    <?php
    $form = ActiveForm::begin([
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
            <?= Module::t('amosorganizzazioni', 'Search'); ?>:
        </h2>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['placeholder' => Module::t('amosorganizzazioni', 'Search by group name')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'description')->textInput(['placeholder' => Module::t('amosorganizzazioni', 'Search by group description')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(
                Module::t('amosorganizzazioni', 'Reset'),
                [
                    '/organizzazioni/profilo-groups/' . Yii::$app->controller->action->id,
                    'currentView' => Yii::$app->request->getQueryParam('currentView')
                ],
                ['class' => 'btn btn-secondary'])
            ?>
            <?= Html::submitButton(Module::t('amosorganizzazioni', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
