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
use open20\amos\core\helpers\Html;
use open20\gamification\Module;
use open20\design\assets\ShimmerDesignAsset;

ShimmerDesignAsset::register($this);

/**
 * @var open20\gamification\models\Gamification$model
 * @var string $tipologia
 */

if (strlen($model->descrizione) > 150)
    $model->descrizione = strip_tags($model->descrizione, 0, 147) . '...';

?>

<div class="luoghi-card">

    <div class="card-wrapper">
        <div class="card card-img">
            <div class="img-responsive-wrapper w-100 pr-xl-3">
                <div class="image-wrapper position-relative w-100 h-100">
                    <?php
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->copertina)) {
                        $url = $model->getCopertinaUrl();
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

                    <?php echo ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/luoghi-della-cultura/luoghi-della-cultura/update?id=" . $model->id,
                        'actionDelete' => "/luoghi-della-cultura/luoghi-della-cultura/delete?id=" . $model->id,
                        'labelDeleteConfirm' => ('Sei sicuro di voler cancellare questa luogo?'),
                    ]) ?>
                </div>
            </div>
            <div class="card-body pl-0">
                <div class="title-luogo">
                    <?= Html::a(Html::tag('h5', $model->nome, ['class' => 'card-title bold']), $model->getFullViewUrl(), ['class' => '', 'title' => 'Vai al luogo della cultura ' .  $model->nome]) ?>
                </div>

                <div class="luogo-card"><span class="mdi mdi-map-marker mdi-24px"></span><?= $model->luogo_appartenenza; ?></div>


            </div>
            <div class="footer-card">
                <div class="tipo-luogo">
                    <span class="icon-luogo"><img src="<?= $model->getIconaTipologia($model->id) ?>" alt="<?= Module::t('gamification', 'Immagine della notizia') ?>">
                    </span>
                    <small class="text-muted"><?= $model->getTipologia($model->id); ?></small>
                </div>
                <a class="readmore small" href="<?= $model->getFullViewUrl() ?>" title="Vai al luogo della cultura <?= $model->nome ?>">apri</a>
            </div>
        </div>
    </div>
</div>