<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\CwhUtility;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\core\utilities\CurrentUser;
use open20\design\assets\ShimmerDesignAsset;
ShimmerDesignAsset::register($this);

/**
 * @var open20\gamification\models\Gamification $model
 */

if (strlen($model->descrizione) > 150)
    $model->descrizione = strip_tags($model->descrizione, 0, 147) . '...';

?>

<div class="listview-container row">
    <div class="container-news col-md-4">
        <div class="card-wrapper">
            <div class="card card-img">
                <div class="img-responsive-wrapper w-100 pr-xl-3">
                    <div class="image-wrapper position-relative w-100 h-100">
                        <?php
                        $url = '/img/img_default.jpg';
                        if (!is_null($model->copertina)) {
                            $url = $model->copertina->getWebUrl('copertina', false, true);
                        }
                        $contentImage = Html::img($url, [
                            'class' => 'luogo-image shimmer-image',
                            'alt' => ('Vai al luogo della cultura ' .  $model->nome),
                        ]);
                        ?>
                        <?= Html::a(
                            $contentImage,
                            $model->getFullViewUrl(),
                            [
                                'title' => 'Vai al luogo della cultura ' .  $model->nome,
                                'class' => 'img-shimmer'
                            ]
                        )

                        ?>
                        <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/luoghi-della-cultura/luoghi-della-cultura/update?id=" . $model->id,
                            'actionDelete' => "/luoghi-della-cultura/luoghi-della-cultura/delete?id=" . $model->id,
                            'labelDeleteConfirm' => ('Sei sicuro di voler cancellare questa luogo?'),
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
                    ItemAndCardHeaderWidget::widget(
                        [
                            'model' => $model,
                            'enableLink' => !(CurrentUser::isPlatformGuest()),
                        ]
                    )
                    ?>

                    <?= Html::a(Html::tag('h3', $model->nome, ['class' => 'card-title font-weight-bold']), $model->getFullViewUrl(), ['class' => 'link-list-title', 'title' => 'Vai al luogo della cultura ' .  $model->nome]) ?>
                    <?php if (!empty(CwhUtility::getTargetsString($model))) : ?>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="<?= CwhUtility::getTargetsString($model) ?>">

                            <span class="mdi mdi-account-supervisor-circle text-muted"></span>

                            <span class="sr-only"><?= CwhUtility::getTargetsString($model) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <a class="read-more small" href="<?= $model->getFullViewUrl() ?>" title="Vai al luogo della cultura <?= $model->nome ?>">
                </a>
            </div>
        </div>
    </div>
</div>
</div>
<div class="clearfix"></div>