<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\views\collaboration-proposals
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\Module;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$moduleTag = \Yii::$app->getModule('tag');

$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

$colCategoryId = Yii::$app->controller->action->id == 'manage' ? 'col-md-4' : 'col-md-6';
$colCollaborationTypeId = Yii::$app->controller->action->id == 'manage' ? 'col-md-4' : 'col-md-6';
?>

<div class="<?= Yii::$app->controller->id ?>-search element-to-toggle" data-toggle-element="form-search">

    <?php
    $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);  ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= \Yii::t('amoscollaborations', 'Cerca per'); ?>:
        </h2>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'title')->textInput([
                'placeholder' => \Yii::t('amoscollaborations', 'Cerca per titolo')
        ]); ?>
    </div>

    <div class="<?= $colCategoryId ?>">
        <?= $form->field($model, 'category_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => $model->getCategoryLabel(),
                'hideSearch' => true,
                'options' =>
                    [
                        'multiple' => false,
                        'placeholder' => \Yii::t('amoscollaborations', 'Cerca per categoria'),
                        'data-toggle' => 'tooltip',
                    ],
                'pluginOptions' =>
                    [
                        'allowClear' => true,
                    ],
            ]
        ); ?>
    </div>

    <div class="<?= $colCollaborationTypeId ?>">
        <?= $form->field($model, 'collaboration_type_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => ArrayHelper::map(\open20\amos\collaborations\models\CollaborationType::find()->all(), 'id', 'name'),
                'hideSearch' => true,
                'options' =>
                    [
                        'multiple' => false,
                        'placeholder' => \Yii::t('amoscollaborations', 'Cerca per tipo di collaborazione'),
                        'data-toggle' => 'tooltip',
                    ],
                'pluginOptions' =>
                    [
                        'allowClear' => true,
                    ],
            ]
        ); ?>
    </div>

    <?php if (Yii::$app->controller->action->id == 'manage') { ?>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
                'data' => [
                    CollaborationProposals::STATUS_DRAFT => \Yii::t('amoscollaborations', 'Bozza'),
                    CollaborationProposals::STATUS_TOVALIDATE => \Yii::t('amoscollaborations', '#to_validate'),
                    CollaborationProposals::STATUS_PUBLISHED => \Yii::t('amoscollaborations', 'Pubblicata'),
                    CollaborationProposals::STATUS_CLOSED => \Yii::t('amoscollaborations', 'Chiusa'),
                    // 'CollaborationProposalsWorkflow/ARCHIVED' => \Yii::t('amoscollaborations', 'Archiviata'),
                    CollaborationProposals::STATUS_REFUSED => \Yii::t('amoscollaborations', '#refused'),
                ],
                'hideSearch' => true,
                'options' => [
                    'placeholder' => \Yii::t('amoscollaborations', "Cerca per stato"),
                    'value' => empty(Yii::$app->request->get()['CollaborationProposalsSearch']['status']) ? '' : Yii::$app->request->get()['CollaborationProposalsSearch']['status'],
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]) ?>
        </div>
    <?php } ?>

    <div class="col-md-12">
        <?= $form->field($model, 'short_summary')
            ->textInput([
                    'placeholder' => \Yii::t('amoscollaborations', 'Cerca per sintesi')
            ]); ?>
    </div>


    <?php
    if (!Yii::$app->user->isGuest) {
        if (isset($moduleTag) && in_array('open20\amos\collaborations\models\CollaborationProposals', $moduleTag->modelsEnabled) && $moduleTag->behaviors) { ?>
            <div class="col-xs-12">
                <?php
                $params = \Yii::$app->request->getQueryParams();
                echo \open20\amos\tag\widgets\TagWidget::widget([
                    'model' => $model,
                    'attribute' => 'tagValues',
                    'form' => $form,
                    'isSearch' => true,
                    'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : [],
                    'enableAjax' => true
                ]);
                ?>
            </div>
        <?php }
    } ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(\Yii::t('amoscore', 'Reset'), Yii::$app->controller->action->id, ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(\Yii::t('amoscollaborations', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Advanced search<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>
</div>
