<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community-wizard
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;

/* @var \open20\amos\community\models\Community $model */

$this->title = AmosCommunity::t('amoscommunity', 'New Community');
if(!is_null($model->parent_id)){
    $this->title = AmosCommunity::t('amoscommunity', '#new_subcommunity');
}
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'id' => 'community_form_' . $model->id,
        'class' => 'form',
        'enctype' => 'multipart/form-data',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
]);
?>

<div class="community-tag">
    <div class="row">
        <div class="col-xs-12">
            <?php
            $moduleTag = \Yii::$app->getModule('tag');
            if (isset(\Yii::$app->controller->model) && isset($moduleTag) && in_array(get_class(\Yii::$app->controller->model), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
                echo \open20\amos\tag\widgets\TagWidget::widget([
                    'model' => $model,
                    'attribute' => 'tagValues',
                    'form' => \yii\base\Widget::$stack[0],
                    //'singleFixedTreeId' => 2
                ]);
            }
            ?>
        </div>
    </div>
</div>

<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl([
        '/community/community-wizard/access-type',
        'id' => $model->id
    ]),
    'cancelUrl' => Yii::$app->session->get(AmosCommunity::beginCreateNewSessionKey()),
    'contentAlreadyExists' => true
]) ?>

<?php ActiveForm::end(); ?>
