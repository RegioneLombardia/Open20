<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 * @var yii\widgets\ActiveForm $form
 * @var string $viewPublish
 * @var string $viewPublishRequest
 * @var string $viewPublishLabel
 * @var string $viewPublishId
 */

$formId = 'news-wizard-form';
$statusFieldId = Html::getInputId($model, 'status');

$js = "
    function setStatusAndSubmit(statusToSet) {
        $('#" . $statusFieldId . "').val(statusToSet);
        $('#" . $formId . "').submit();
    }
    
    $('#draft-btn').on('click', function (event) {
        setStatusAndSubmit('" . News::NEWS_WORKFLOW_STATUS_BOZZA . "');
    });
    $('#request-publish-btn').on('click', function (event) {
        setStatusAndSubmit('" . News::NEWS_WORKFLOW_STATUS_DAVALIDARE . "');
    });
    $('#publish-btn').on('click', function (event) {
        setStatusAndSubmit('" . News::NEWS_WORKFLOW_STATUS_VALIDATO . "');
    });
";

$this->title = AmosNews::t('amosnews', '#news_wizard_page_title');
$hideWorkflow = isset(Yii::$app->params['hideWorkflowTransitionWidget']) && Yii::$app->params['hideWorkflowTransitionWidget'];

?>

<div class="col-xs-12">
    <div class="news-wizard-summary-description">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosNews::t('amosnews', 'Details') ?>
                    </h3>
                    <dl>
                        <dt><?= $model->getAttributeLabel('titolo') ?></dt>
                        <dd><?= $model->titolo ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('sottotitolo') ?></dt>
                        <dd><?= $model->sottotitolo ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione_breve') ?></dt>
                        <dd><?= $model->descrizione_breve ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asHtml($model->descrizione) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('news_categorie_id') ?></dt>
                        <dd><?= $model->newsCategorie->titolo ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('comments_enabled') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asBoolean($model->comments_enabled) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('data_pubblicazione') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asDate($model->data_pubblicazione) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('data_rimozione') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asDate($model->data_rimozione) ?></dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>

    <div class="news-wizard-summary-publication">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosNews::tHtml('amosnews', 'Publication') ?>
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
                                    /** @var News $model */
                                    $content = Html::beginTag('dl');
                                    $content .= Html::beginTag('dt') . AmosNews::tHtml('amosnews', 'Published by') . Html::endTag('dt');
                                    $content .= Html::beginTag('dd') . $model->createdUserProfile;
                                    $content .= Html::endTag('dl');
                                    return $content;
                                },
                                '{publishingRules}' => function ($model, $widget) {
                                    /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                                    /** @var News $model */
                                    $content = Html::beginTag('dl');
                                    $content .= Html::beginTag('dt') . AmosNews::tHtml('amosnews', 'Publication rule') . Html::endTag('dt');
                                    $content .= Html::beginTag('dd') . \open20\amos\cwh\utility\CwhUtil::getPublicationRuleLabel($model->regola_pubblicazione) . Html::endTag('dd');
                                    $content .= Html::endTag('dl');
                                    return $content;
                                },
                                '{targetAdv}' => function ($model, $widget) {
                                    /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                                    /** @var News $model */
                                    $targets = $model->destinatari;
                                    $publicationRule = \open20\amos\cwh\utility\CwhUtil::getPublicationRuleLabel($model->regola_pubblicazione);
                                    $targetsString = $widget->getNodesAsString($targets);
                                    $content = Html::beginTag('dl');
                                    $content .= Html::beginTag('dt') . AmosNews::tHtml('amosnews', 'Recipients') . Html::endTag('dt');
                                    $content .= Html::beginTag('dd') . $publicationRule . (count($targets) ? ' ' . AmosNews::t('amosnews', 'in') . ' ' . $targetsString : '');
                                    $content .= Html::endTag('dd');
                                    $content .= Html::endTag('dl');
                                    return $content;
                                }
                            ]
                        ]); ?>
                    <?php endif; ?>
                    <?php
                    $moduleTag = Yii::$app->getModule('tag');
                    if ( isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
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
                    <?= Html::tag('div', AmosIcons::show('square-check', ['class' => 'am-2'], 'dash') . '<p class="icon-inline">' . AmosNews::tHtml('amosnews', $viewPublishLabel) . '</p>', ['id' => $viewPublishId, 'class' => 'btn btn-navigation-primary publish-icon']) ?>
                </div>
                <div class="col-xs-12 col-sm-6 text-center m-t-30">
                    <?= Html::tag('div', AmosIcons::show('square-editor', ['class' => 'am-2'], 'dash') . '<p class="icon-inline">' . AmosNews::tHtml('amosnews', 'Save as draft') . '</p>', ['id' => 'draft-btn', 'class' => 'btn btn-secondary save-draft-icon']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>

<?= \open20\amos\core\forms\WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/news/news-wizard/publication', 'id' => $model->id]),
    'cancelUrl' => $hideWorkflow ? '' : Yii::$app->session->get(AmosNews::beginCreateNewSessionKey()),
    'contentAlreadyExists' => true,
    'viewContinueBtn' => $hideWorkflow,
    'finishUrl' => $hideWorkflow ?  Yii::$app->getUrlManager()->createUrl(['/news/news-wizard/finish', 'id' => $model->id]) : ''
]) ?>

<?php

/**
 * Don't move this javascript register! It does not work at the beginning of the view!!!!!!!
 */
$this->registerJs($js, View::POS_READY);

?>
