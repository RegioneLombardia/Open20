<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var string $finishMessage
 */

$this->title = $model;

?>

<div class="row m-b-30">
    <div class="col-xs-12">
        <div class="pull-left">
            <!-- ?= AmosIcons::show('file-text-o', ['class' => 'am-4 icon-calendar-intro m-r-15'], 'dash') ?-->
            <div class="like-widget-img color-primary ">
                <?= \open20\amos\core\icons\AmosIcons::show('file-text-o', [], 'dash'); ?>
            </div>
        </div>
        <div class="text-wrapper">
            <h3><?= $finishMessage ?></h3>
            <h4><?= AmosDocumenti::tHtml('amosdocumenti', '#BACK_TO_DOC_STR') ?></h4>
        </div>
    </div>
</div>


<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/documenti/documenti-wizard/summary', 'id' => $model->id]),
    'viewPreviousBtn' => false,
    'continueLabel' => AmosDocumenti::tHtml('amosdocumenti', '#BACK_TO_DOC_BTN'),
    'finishUrl' => Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey())
]) ?>
