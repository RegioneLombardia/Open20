<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor\open20\amos\collaborations\views/views
 */

use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\core\helpers\Html;
use kartik\datecontrol\DateControl;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\collaborations\models\search\CollaborationExpressionsOfInterestSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$colProposalCategory = (Yii::$app->controller->action->id != 'created-by-me') ? 'col-md-3' : 'col-md-4';
$colProposalStatus = (Yii::$app->controller->action->id != 'created-by-me') ? 'col-md-3' : 'col-md-4';
$colProposerFullName = (Yii::$app->controller->action->id != 'created-by-me') ? 'col-md-3' : 'col-md-4';
$colExprOfInterestDate = (Yii::$app->controller->action->id != 'created-by-me') ? 'col-md-3' : 'col-md-4';

?>
<div class="collaboration-expressions-of-interest-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= \Yii::t('amoscollaborations', 'Cerca per'); ?>:
        </h2>
    </div>

    <!-- PROPOSAL TITLE -->
    <div class="col-md-9">
        <?= $form->field($model, 'proposal_title')->textInput(
            [
                'placeholder' => \Yii::t('amoscollaborations', 'Cerca per titolo della proposta')
            ]
        ) ?>
    </div>

    <!-- PROPOSAL CATEGORY -->
    <div class="<?= $colProposalCategory ?>">
        <?= $form->field($model, 'proposal_category')->widget(\kartik\select2\Select2::classname(), [
                'data' => [
                    1 => \Yii::t('amoscollaborations', 'Dalla piattaforma'),
                    2 => \Yii::t('amoscollaborations', 'Dal mondo')
                ],
                'hideSearch' => true,
                'options' =>
                    [
                        'multiple' => false,
                        'placeholder' => \Yii::t('amoscollaborations', 'Cerca per categoria proposta'),
                        'data-toggle' => 'tooltip',
                    ],
                'pluginOptions' =>
                    [
                        'allowClear' => true,
                    ],
            ]
        ); ?>
    </div>

    <!-- PROPOSAL STATUS -->
    <div class="<?= $colProposalStatus ?>">
        <?= $form->field($model, 'collaborationProposalStatus')->widget(\kartik\select2\Select2::className(), [
            'data' => [
                CollaborationProposals::STATUS_DRAFT => \Yii::t('amoscollaborations', 'Bozza'),
                CollaborationProposals::STATUS_TOVALIDATE => \Yii::t('amoscollaborations', '#to_validate'),
                CollaborationProposals::STATUS_PUBLISHED => \Yii::t('amoscollaborations', 'Pubblicata'),
                CollaborationProposals::STATUS_CLOSED => \Yii::t('amoscollaborations', 'Chiusa'),
                //'CollaborationProposalsWorkflow/ARCHIVED' => \Yii::t('amoscollaborations', 'Archiviata'),
                CollaborationProposals::STATUS_REFUSED => \Yii::t('amoscollaborations', '#refused'),
            ],
            'hideSearch' => true,
            'options' => [
                'placeholder' => \Yii::t('amoscollaborations', "Cerca per stato proposta"),
                //'value' => empty(Yii::$app->request->get()['CollaborationProposalsSearch']['status']) ? '' : Yii::$app->request->get()['CollaborationProposalsSearch']['status'],
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    </div>

    <!-- PROPOSER FULL NAME -->
    <?php if (Yii::$app->controller->action->id != 'created-by-me') { ?>
        <div class="<?= $colProposerFullName ?>">
            <?= $form->field($model, 'proposer_full_name')->textInput(
                [
                    'placeholder' => \Yii::t('amoscollaborations', 'Cerca per nome manifestante')
                ]
            ); ?>
        </div>
    <?php } ?>

    <!-- EXPRESSION OF INTEREST CREATION DATE -->
    <div class="<?= $colExprOfInterestDate ?>">
        <?= $form->field($model, 'created_at')->widget(
            DateControl::classname(),
            [
                'options' =>
                    [
                        'layout' => '{input} {picker} ' . (($model->created_at == '') ? '' : '{remove}'),
                    ]
            ]
        )->label(\Yii::t('amoscollaborations', 'Data manifestazione')); ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(\Yii::t('amoscore', 'Reset'), Yii::$app->controller->action->id, ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
