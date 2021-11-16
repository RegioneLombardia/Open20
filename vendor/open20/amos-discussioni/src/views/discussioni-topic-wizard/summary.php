<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\views\discussioni-topic-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 * @var yii\widgets\ActiveForm $form
 * @var string $viewPublish
 * @var string $viewPublishRequest
 * @var string $viewPublishLabel
 * @var string $viewPublishId
 */

$formId = 'discussioni-topic-wizard-form';
$statusFieldId = Html::getInputId($model, 'status');

$js = "
    function setStatusAndSubmit(statusToSet) {
        $('#" . $statusFieldId . "').val(statusToSet);
        $('#" . $formId . "').submit();
    }
    
    $('#draft-btn').on('click', function (event) {
        setStatusAndSubmit('" . DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA . "');
    });
    $('#request-publish-btn').on('click', function (event) {
        setStatusAndSubmit('" . DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE . "');
    });
    $('#publish-btn').on('click', function (event) {
        setStatusAndSubmit('" . DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA . "');
    });
";

$this->title = AmosDiscussioni::t('amosdiscussioni', '#discussions_wizard_page_title');
$hideWorkflow = isset(Yii::$app->params['hideWorkflowTransitionWidget']) && Yii::$app->params['hideWorkflowTransitionWidget'];

?>

<div class="col-xs-12">
    <div class="discussioni-topic-wizard-summary-description">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosDiscussioni::t('amosdiscussioni', 'Details') ?>
                    </h3>
                    <dl>
                        <dt><?= $model->getAttributeLabel('titolo') ?></dt>
                        <dd><?= $model->titolo ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('testo') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asHtml($model->testo) ?></dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>

    <div class="discussioni-topic-wizard-summary-publication">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosDiscussioni::tHtml('amosdiscussioni', 'Publication') ?>
                    </h3>
                    <?php
                    $moduleCwh = Yii::$app->getModule('cwh');
                    ?>
                    <?php if (isset($moduleCwh)): ?>
                        <?= PublishedByWidget::widget([
                            'model' => $model,
                            'layout' => '{publisher}{publishingRules}{targetAdv}',
                            'renderSections' => [
                                '{publisher}' => function ($model, $widget) {
                                    /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                                    /** @var DiscussioniTopic $model */
                                    $content = Html::beginTag('dl');
                                    $content .= Html::beginTag('dt') . AmosDiscussioni::tHtml('amosdiscussioni', 'Published by') . Html::endTag('dt');
                                    $content .= Html::beginTag('dd') . $model->createdUserProfile;
                                    $content .= Html::endTag('dl');
                                    return $content;
                                },
                                '{publishingRules}' => function ($model, $widget) {
                                    /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                                    /** @var DiscussioniTopic $model */
                                    $content = Html::beginTag('dl');
                                    $content .= Html::beginTag('dt') . AmosDiscussioni::tHtml('amosdiscussioni', 'Publication rule') . Html::endTag('dt');
                                    $content .= Html::beginTag('dd') . $model->getRegolaPubblicazione() . Html::endTag('dd');
                                    $content .= Html::endTag('dl');
                                    return $content;
                                },
                                '{targetAdv}' => function ($model, $widget) {
                                    /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                                    /** @var DiscussioniTopic $model */
                                    $targets = $model->getTargets();
                                    $publicationRule = $model->getRegolaPubblicazione();
                                    $targetsString = $widget->getNodesAsString($targets);
                                    $content = Html::beginTag('dl');
                                    $content .= Html::beginTag('dt') . AmosDiscussioni::tHtml('amosdiscussioni', 'Recipients') . Html::endTag('dt');
                                    $content .= Html::beginTag('dd') . $publicationRule . (count($targets) ? ' ' . AmosDiscussioni::t('amosdiscussioni', 'in') . ' ' . $targetsString : '') . Html::endTag('dd');
                                    $content .= Html::endTag('dl');
                                    return $content;
                                }
                            ]
                        ]); ?>
                    <?php endif; ?>
                    <?php $moduleTag = Yii::$app->getModule('tag'); ?>
                    <?php if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
                        <?= Html::tag('div', '', ['class' => 'clearfix']) ?>
                        <?= ShowUserTagsWidget::widget([
                            'userProfile' => $model->id,
                            'className' => $model->className()
                        ]); ?>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>

    <?php if(!$hideWorkflow): ?>
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => $formId,
                'class' => 'form',
                'enableClientValidation' => true,
                'errorSummaryCssClass' => 'error-summary alert alert-error'
            ]
        ]); ?>

        <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 col-sm-6 text-center m-t-30">
                    <?= Html::tag('div', AmosIcons::show('square-check', ['class' => 'am-2'], 'dash') . '<p class="icon-inline">' . AmosDiscussioni::tHtml('amosdiscussioni', $viewPublishLabel) . '</p>', ['id' => $viewPublishId, 'class' => 'btn btn-navigation-primary publish-icon']) ?>
                </div>
                <div class="col-xs-12 col-sm-6 text-center m-t-30">
                    <?= Html::tag('div', AmosIcons::show('square-editor', ['class' => 'am-2'], 'dash') . '<p class="icon-inline">' . AmosDiscussioni::tHtml('amosdiscussioni', 'Save as draft') . '</p>', ['id' => 'draft-btn', 'class' => 'btn btn-secondary save-draft-icon']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>

<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/discussioni/discussioni-topic-wizard/publication', 'id' => $model->id]),
    'cancelUrl' => $hideWorkflow ? '' : Yii::$app->session->get(AmosDiscussioni::beginCreateNewSessionKey()),
    'finishUrl' =>  $hideWorkflow ? Yii::$app->getUrlManager()->createUrl(['/discussioni/discussioni-topic-wizard/finish', 'id' => $model->id]) :'',
    'viewContinueBtn' => $hideWorkflow,
    'contentAlreadyExists' => true
]) ?>

<?php

/**
 * Don't move this javascript register! It does not work at the beginning of the view!!!!!!!
 */
$this->registerJs($js, View::POS_READY);

?>
