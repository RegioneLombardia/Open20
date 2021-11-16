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
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\discussioni\AmosDiscussioni;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = AmosDiscussioni::t('amosdiscussioni', '#discussions_wizard_page_title');

?>

<div class="discussioni-topic-wizard-introduction col-xs-12 nop">
    <div class="col-lg-12 nop">
        <div class="pull-left">
            <!--?= AmosIcons::show('comment', ['class' => 'am-4 icon-calendar-intro'], 'dash') ?-->
            <div class="like-widget-img color-primary ">
                <?= AmosIcons::show('comment', [], 'dash'); ?>
            </div>
        </div>
        <div class="text-wrapper">
            <?= AmosDiscussioni::t('amosdiscussioni', 'Pubblica discussioni con gli utenti della piattaforma o con gruppi specifici di utenti. Gli utenti possono partecipare inserendo contributi o rispondendo a quelli inseriti da altri.') ?>
            <div class="nop col-xs-12">
                <p><?= AmosDiscussioni::t('amosdiscussioni', 'In the following steps you have to insert') ?>:</p>
                <div>
                    <ol>
                        <li>
                            <?= AmosDiscussioni::tHtml('amosdiscussioni', "informazioni che descrivono la discussione (titolo, testo)") ?>
                        </li>
                        <li>
                            <?= AmosDiscussioni::tHtml('amosdiscussioni', "informazioni per la pubblicazione e la visibilità") ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 nop">
            <p>
                <strong><?= AmosDiscussioni::t('amosdiscussioni', 'In order to create a discussion, press CONTINUE (in the lower right corner)') ?></strong>
            </p>
        </div>
    </div>
    
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'discussioni-topic-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <br>
    <br>
    <div class="nop col-xs-12 text-uppercase m-t-15 m-b-25">
        <strong><?= Html::a(AmosDiscussioni::tHtml('amosdiscussioni', 'Skip wizard. Fill directly the discussion.'), ['/discussioni/discussioni-topic/create']) ?></strong>
    </div>
    <br>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'viewPreviousBtn' => false,
        'cancelUrl' => Yii::$app->session->get(AmosDiscussioni::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
