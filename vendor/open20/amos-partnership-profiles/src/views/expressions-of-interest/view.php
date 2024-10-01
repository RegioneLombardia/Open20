<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\expressions-of-interest
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\partnershipprofiles\controllers\ExpressionsOfInterestController;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\Module;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\ExpressionsOfInterest $model
 */

$this->title = Module::t('amospartnershipprofiles', '#view_expr_of_int');
$this->params['breadcrumbs'][] = $this->title;

/** @var ExpressionsOfInterestController $appController */
$appController = Yii::$app->controller;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => Yii::$app->controller->id . '_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
]);
?>
<?php
if ($model->status != ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT) {
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW,
        'classDivMessage' => 'message'
    ]);
}
?>

<div class="<?= Yii::$app->controller->id ?>-view col-xs-12">
    <?= $this->render('parts/partnership_profile_title', ['model' => $model]) ?>
    <?= ContextMenuWidget::widget([
        'model' => $model,
        'actionModify' => '/partnershipprofiles/expressions-of-interest/update?id=' . $model->id,
        'actionDelete' => '/partnershipprofiles/expressions-of-interest/delete?id=' . $model->id,
        'mainDivClasses' => 'm-t-0 m-b-15'
    ]) ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var ExpressionsOfInterest $model */
                    return $model->getWorkflowStatusLabel();
                }
            ],
            'partnership_offered:html',
            'additional_information:html',
            'clarifications:html',
            'user_network_reference' => [
                'label' => $model->getAttributeLabel('userNetworkReference'),
                'value' => function ($model) {
                    /** @var ExpressionsOfInterest $model */
                    $user_network_reference_classname = $model->user_network_reference_classname;
                    if (!empty($user_network_reference_classname)) {
                        $record = $user_network_reference_classname::findOne($model->user_network_reference_id);
                        if (!empty($record)) {
                            return $record->name;
                        }
                    }
                    return '';
                }
            ]
        ]
    ]) ?>
    <?= WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        'closeButton' => Html::a(Module::t('amospartnershipprofiles', 'Annulla'), $appController->getViewCloseUrl(), ['class' => 'btn btn-secondary']),
        'initialStatusName' => "DRAFT",
        'initialStatus' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT,
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
