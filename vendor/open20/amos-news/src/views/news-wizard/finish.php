<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-wizard
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 * @var string $finishMessage
 */

$this->title = $model;

?>

<div class="row m-b-30">
    <div class="col-xs-12">
        <div class="pull-left">
            <!-- ?= AmosIcons::show('feed', ['class' => 'am-4 icon-calendar-intro m-r-15'], 'dash') ?-->
            <div class="like-widget-img color-primary ">
                <?= \open20\amos\core\icons\AmosIcons::show('feed', [], 'dash'); ?>
            </div>
        </div>
        <div class="text-wrapper">
            <h3><?= $finishMessage ?></h3>
            <h4><?= AmosNews::tHtml('amosnews', "Click on 'back to news' to finish.") ?></h4>
        </div>
    </div>
</div>


<?= \open20\amos\core\forms\WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/news/news-wizard/summary', 'id' => $model->id]),
    'viewPreviousBtn' => false,
    'continueLabel' => AmosNews::tHtml('amosnews', 'Back to news'),
    'finishUrl' => Yii::$app->session->get(AmosNews::beginCreateNewSessionKey())
]) ?>
