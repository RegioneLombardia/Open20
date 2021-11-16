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
use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;



$hideCategory = false;
$newsCategories = NewsUtility::getNewsCategories();
if (count($newsCategories) == 1) {
  $hideCategory = true;
} else {
  $category = $model->newsCategorie->titolo;
}

/**
 * @var \open20\amos\news\models\News $model
 */
?>

<div class="listview-container news-item  nop">
    <div class="container-news col-xs-12 nop">
        <div class="card-wrapper">
            <div class="card card-img">
                <div class="img-responsive-wrapper w-100 pr-xl-3">
                    <div class="image-wrapper position-relative w-100 h-100">
                        <?php
                            $url = '/img/img_default.jpg';
                            if (!is_null($model->newsImage)) {
                                $url = $model->newsImage->getUrl('item_news', false, true);
                            }
                            $contentImage = Html::img($url, [
                                'class' => 'full-width',
                                'alt' => AmosNews::t('amosnews', 'Vai alla notizia ' .  $model->titolo)
                            ]);
                        ?>
                        <?= Html::a($contentImage, $model->getFullViewUrl(), ['title' => 'Vai alla notizia ' .  $model->titolo]) ?>
                        <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/news/news/update?id=" . $model->id,
                            'actionDelete' => "/news/news/delete?id=" . $model->id,
                            'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa notizia?'),
                            'modelValidatePermission' => 'NewsValidate'
                        ]) ?>
                        <?= NewsWidget::widget(['model' => $model]); ?>
                        <div class="card-calendar d-flex flex-column justify-content-center position-absolute rounded-0">
                            <span class="card-day font-weight-bold text-600 lead"><?= Html::tag('strong', \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'd')) ?></span>
                            <span class="card-month text-uppercase font-weight-bold text-600 small"><?= Html::tag('strong', \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'MMM')) ?></span>
                            <span class="card-year font-weight-light text-600 small"><?= \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'y') ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body pl-0">
                    <?= 
                        ItemAndCardHeaderWidget::widget([
                                'model' => $model,
                                'publicationDateNotPresent' => true,
                                'showPrevalentPartnershipAndTargets' => true,
                                'enableLink' => !(CurrentUser::isPlatformGuest())
                            ]
                        ) 
                    ?>
                    <hr class="w-75 my-2 ml-0">
                    <?php if (!$hideCategory) : ?>
                    <p class="card-category font-weight-normal mb-3"><?= $model->newsCategorie->titolo ?></p>
                    <?php endif ?>
                    <?= Html::a(Html::tag('h3', $model->titolo,['class' => 'card-title font-weight-bold']), $model->getFullViewUrl(), ['class' => 'link-list-title', 'title' => 'Vai alla notizia ' .  $model->titolo]) ?>
                    <p class="card-description font-weight-light"><?= $model->descrizione_breve ?></p>
                    <a class="read-more" href="<?= $model->getFullViewUrl() ?>" title="Vai alla news <?= $model->titolo ?>">
                        <?= Html::tag('span', AmosNews::t('amosnews', 'Leggi'), ['class' => 'text']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>