<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\events\AmosEvents;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var \open20\amos\events\models\Event $model
 */
?>
<div class="listview-container news-item grid-item nop">
    <div class="col-xs-12 nop icon-header">
        <div class="col-xs-12 nop top-header">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/events/event-dashboard/view?id=" . $model->id,
                'modelValidatePermission' => 'NewsValidate'
            ]) ?>
            <?= NewsWidget::widget(['model' => $model]); ?>
        </div>
        <div class="news-image">
            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->eventLogo)) {
                $url = $model->eventLogo->getUrl('item_news', false, true);
            }
            $contentImage = Html::img($url, [
                'class' => 'img-responsive',
                'alt' => AmosEvents::t('amosevents', 'Immagine dell\'evento')
            ]);
            ?>
            <?= Html::a($contentImage, \open20\amos\events\utility\EventsUtility::getUrlLanding($model)) ?>
        </div>
        <div class="col-xs-12">
            <?= ItemAndCardHeaderWidget::widget([
                    'model' => $model,
                    'publicationDateNotPresent' => true,
                    'showPrevalentPartnershipAndTargets' => true,
                ]
            ) ?>
        </div>
    </div>
    <div class="col-xs-12 nop icon-body">

        <?php $visible = isset($statsToolbar) ? $statsToolbar : false; ?>
        <?php if ($visible) : ?>
            <div class="col-xs-3 nop counter-column">
                <?php
                echo StatsToolbar::widget([
                    'model' => $model,
                    'layoutType' => StatsToolbar::LAYOUT_VERTICAL,
                    'disableLink' => true,
                ]);
                ?>
            </div>
        <?php endif; ?>

        <div class="<?= ($visible) ? 'col-xs-9 nop' : 'col-xs-12' ?> text-column">
            <?= Html::a(Html::tag('h3', $model->title), $model->getFullViewUrl(), ['class' => 'title']) ?>
<!--            --><?php //Html::tag('p', $model->description, ['class' => 'text']) ?>
            <div>
                <?= !empty($model->begin_date_hour) ? \Yii::$app->formatter->asDate($model->begin_date_hour) : '' ?>
            </div>
            <div>
                <?= $model->eventType->title ?>
            </div>
        </div>
    </div>

</div>
<div class="clearfix"></div>
