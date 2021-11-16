<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var \open20\amos\core\forms\ActiveForm $form
 * @var \open20\amos\events\models\Event $model
 * @var string $viewPublish
 * @var string $viewPublishRequest
 */

$js = "
    function setStatusAndSubmit(statusToSet) {
        $('#event-status').val(statusToSet);
        $('#event-wizard-form').submit();
    }
    
    $('#draft-btn').on('click', function (event) {
        setStatusAndSubmit('" . Event::EVENTS_WORKFLOW_STATUS_DRAFT . "');
    });
    $('#request-publish-btn').on('click', function (event) {
        setStatusAndSubmit('" . Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST . "');
    });
    $('#publish-btn').on('click', function (event) {
        setStatusAndSubmit('" . Event::EVENTS_WORKFLOW_STATUS_PUBLISHED . "');
    });
";

$hideWorkflow = isset(\Yii::$app->params['hideWorkflowTransitionWidget']) && \Yii::$app->params['hideWorkflowTransitionWidget'];

$this->title = AmosEvents::t('amosevents',"Nuovo Evento");
?>

<div class="col-xs-12">
    <div class="event-wizard-summary-description">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosEvents::t('amosevents', 'Description') ?>
                    </h3>
                    <dl>
                        <dt><?= $model->getAttributeLabel('title') ?></dt>
                        <dd><?= $model->title ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('summary') ?></dt>
                        <dd><?= $model->summary ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('description') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asHtml($model->description) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('eventType') ?></dt>
                        <dd><?= !empty($model->eventType) ? $model->eventType->title : '-' ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('event_location') ?></dt>
                        <dd><?= ($model->event_location ? $model->event_location : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'Country') ?></dt>
                        <dd><?= (!is_null($model->countryLocation) ? $model->countryLocation->nome : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'Province') ?></dt>
                        <dd><?= (!is_null($model->provinceLocation) ? $model->provinceLocation->nome : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'City') ?></dt>
                        <dd><?= (!is_null($model->cityLocation) ? ($model->event_address_cap ? $model->event_address_cap . ', ' : '') . $model->cityLocation->nome : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('event_address') ?></dt>
                        <dd><?= ($model->event_address ? $model->event_address . ($model->event_address_house_number ? ', ' . $model->event_address_house_number : '') : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'Begin Date') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asDate($model->begin_date_hour) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'Begin Hour') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asTime($model->begin_date_hour) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'End Date') ?></dt>
                        <dd><?= ($model->end_date_hour ? Yii::$app->getFormatter()->asDate($model->end_date_hour) : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'End Hour') ?></dt>
                        <dd><?= ($model->end_date_hour ? Yii::$app->getFormatter()->asTime($model->end_date_hour) : '-') ?></dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>

    <div class="event-wizard-summary-organizational-data">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosEvents::tHtml('amosevents', 'Organizational data') ?>
                    </h3>
                    <dl>
                        <dt><?= $model->getAttributeLabel('registration_limit_date') ?></dt>
                        <dd><?= ($model->registration_limit_date ? Yii::$app->getFormatter()->asDate($model->registration_limit_date) : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('seats_available') ?></dt>
                        <dd><?= ($model->seats_available ? $model->seats_available : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= AmosEvents::tHtml('amosevents', 'Membership Type') ?></dt>
                        <dd><?= (!is_null($model->eventMembershipType) ? $model->eventMembershipType->title : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('paid_event') ?></dt>
                        <dd><?= (!is_null($model->paid_event) ? Yii::$app->getFormatter()->asBoolean($model->paid_event) : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('publish_in_the_calendar') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asBoolean($model->publish_in_the_calendar) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('event_commentable') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asBoolean($model->event_commentable) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('event_management') ?></dt>
                        <dd><?= Yii::$app->getFormatter()->asBoolean($model->event_management) ?></dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>

    <div class="event-wizard-summary-publication">
        <div class="centered-details col-xs-12">
            <div class="row">
                <section>
                    <h3>
                        <?= AmosEvents::tHtml('amosevents', 'Publication') ?>
                    </h3>
                    <dl>
                        <dt><?= $model->getAttributeLabel('publication_date_begin') ?></dt>
                        <dd><?= ($model->publication_date_begin ? Yii::$app->getFormatter()->asDate($model->publication_date_begin) : '-') ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('publication_date_end') ?></dt>
                        <dd><?= ($model->publication_date_end ? Yii::$app->getFormatter()->asDate($model->publication_date_end) : '-') ?></dd>
                    </dl>
                    <?= \open20\amos\events\widgets\EventsPublishedByWidget::widget([
                        'entities' => $model->destinatari,
                        'publicationRule' => $model->regola_pubblicazione
                    ]) ?>
                    <?php
                    // TODO use PublishedByWidget! Delete the above widget when PublishedByWidget is correctly configured.
                    //                    echo\open20\amos\core\forms\PublishedByWidget::widget([
                    //                        'model' => $model,
                    //                        'layout' => '<dl><dt>' .  AmosEvents::tHtml('amosevents', 'Recipients') . '</dt><dt>{event_published_by}</dt></dl><dl><dt>' .  AmosEvents::tHtml('amosevents', 'Published by') . '</dt><dt>{event_publisher}</dt></dl>',
                    //                        'renderSections' => [
                    //                            '{event_published_by}' => function ($model, $widget) {
                    //                                /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                    //                                /** @var Event $model */
                    //                                return $model->getRegolaPubblicazione();
                    //                            },
                    //                            '{event_publisher}' => function ($model, $widget) {
                    //                                /** @var \open20\amos\core\forms\PublishedByWidget $widget */
                    //                                /** @var Event $model */
                    //                                return '';
                    //                            }
                    //                        ]
                    //                    ]);
                    ?>
                    <?php $moduleTag = Yii::$app->getModule('tag'); ?>
                    <?php if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
                        <div class="clearfix"></div>
                        <?= ShowUserTagsWidget::widget([
                            'userProfile' => $model->id,
                            'className' => $model->className()
                        ]); ?>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>

    <?php if(!$hideWorkflow){ ?>
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'event-wizard-form',
                'class' => 'form',
                'enableClientValidation' => true,
                'errorSummaryCssClass' => 'error-summary alert alert-error'
            ]
        ]); ?>

        <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>

        <div class="row">
            <div class="col-sm-3 col-xs-6 col-sm-offset-3 text-center <?= $viewPublish ?>">
                <?= Html::button(AmosIcons::show('square-check', ['class' => 'am-3'], 'dash') .
                    '<div class="icon-text">' . AmosEvents::tHtml('amosevents', 'Publish') . '</div>', ['id' => 'publish-btn', 'class' => 'like-widget-img publish-icon']) ?>
            </div>
            <div class="col-sm-3 col-xs-6 col-sm-offset-3 text-center <?= $viewPublishRequest ?>">
                <?= Html::button(AmosIcons::show('square-check', ['class' => 'am-3'], 'dash') .
                    '<div class="icon-text">' . AmosEvents::tHtml('amosevents', 'Request publish') . '</div>', ['id' => 'request-publish-btn', 'class' => 'like-widget-img publish-icon']) ?>
            </div>
            <div class="col-sm-3 col-xs-6 text-center">
                <?= Html::button(AmosIcons::show('square-editor', ['class' => 'am-3'], 'dash') .
                    '<div class="icon-text">' . AmosEvents::tHtml('amosevents', 'Save as draft') . '</div>', ['id' => 'draft-btn', 'class' => 'like-widget-img save-draft-icon']) ?>
            </div>
        </div>

        <?php ActiveForm::end();
    } else { ?>
    <?php
    echo \open20\amos\core\forms\WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/event-wizard/publication', 'id' => $model->id]),
        'cancelUrl' => $hideWorkflow ? '' : Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey()),
        'contentAlreadyExists' => true,
        'viewContinueBtn' => $hideWorkflow,
        'finishUrl' => $hideWorkflow ? Yii::$app->getUrlManager()->createUrl(['/events/event-wizard/finish', 'id' => $model->id]) : ''
    ]) ?>
    <?php } ?>
</div>


<?php

/**
 * Don't move this javascript register! It does not work at the beginning of the view!!!!!!!
 */
$this->registerJs($js, View::POS_READY);

?>
