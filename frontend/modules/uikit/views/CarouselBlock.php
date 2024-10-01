<?php
/* @var $this \luya\cms\base\PhpBlockView */

use luya\lazyload\LazyLoad;
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch ($visibility) {
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;
        break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest;
        $n_class = $this->varValue('addclass');
        if ($canSeeBlock && !empty($n_class)) {
            $canSeeBlock = BaseUikitBlock::visivility($n_class);
        }
        break;
}

$images = $this->extraValue('images');
$indicators = null;
$counter = 0;

if (!empty($images)) {
    $this->registerJs("$('.carousel').carousel(" . $this->extraValue('jsConfig', '') . ");");
    $id = $this->extraValue('id');
    $hasImages = false;
    foreach ($images as $image) {
        if (isset($image['image'])) {
            $hasImages = true;
            break;
        }
    }
    ?>
    <?php if ($canSeeBlock) { ?>

        <?php if ($hasImages) { ?>
            <div id="<?= $id ?>" class="carousel<?= $this->cfgValue('blockCssClass', null, ' {{blockCssClass}}') ?> slide<?= $this->cfgValue('crossfade', null, ' carousel-fade'); ?><?= $this->cfgValue('row', null, ' row') ?>" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    foreach ($images as $image) {
                        if (isset($image['image'])) {
                            $counter++;
                            $isActiveClass = $counter == 1 ? 'active' : '';
                            $indicators .= '<li data-target="#' . $id . '" data-slide-to="' . $counter . (!empty($isActiveClass) ? '" class="' . $isActiveClass : '') . '"></li>';
                            ?>
                            <div class="carousel-item<?= (!empty($isActiveClass) ? ' ' . $isActiveClass : '') ?>">
                                <?php if (!empty($image['link'])) { ?>
                                    <a href="<?= $image['link'] ?>"<?= !empty($image['title']) ? ' title="' . $image['title'] . '">' : '>' ?>
                                <?php } ?>
                                <?php
                                if ($this->cfgValue('lazyload', false)) {
                                    $options['src'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
                                    if (!empty($image['title'])) {
                                        $options['alt'] = $image['title'];
                                        $options['title'] = $image['title'];
                                    }
                                    echo LazyLoad::widget([
                                        'src' => $image['image']->source,
                                        'extraClass' => 'd-block w-100',
                                        'width' => $image['image']->itemArray['resolution_width'],
                                        'height' => $image['image']->itemArray['resolution_height'],
                                        'options' => $options
                                    ]);
                                } else {
                                    ?>
                                       <img class="d-block w-100" src="<?= $image['image']->source ?>"<?= !empty($image['title']) ? ' alt="' . $image['title'] . '">' : '>' ?>
                                   <?php } ?>
                                   <?php if (!empty($image['title']) || !empty($image['caption'])) { ?>
                                       <div class="carousel-caption<?= (!empty($this->cfgValue('captionCssClass'))) ? ' ' . $this->cfgValue('captionCssClass') : '' ?>">
                                           <?php if (!empty($image['title'])) { ?>
                                            <h5 class="carousel-caption-title"><?= $image['title'] ?></h5>
                                            <?php
                                        }
                                        if (!empty($image['caption'])) {
                                            ?>
                                            <p class="carousel-caption-text"><?= $image['caption'] ?></p>
                                        <?php } ?>
                                    <?php }
                                    ?>
                            </div>
                            <?php if (!empty($image['link'])) {
                                ?>
                                </a>
                            <?php } ?>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?= ($this->cfgValue('indicators', null) && $counter > 1) ? '<ol class="carousel-indicators">' . $indicators . '</ol>' : '' ?>
            <?=
            $this->cfgValue(
                    'controls',
                    null,
                    '<a class="carousel-control-prev" href="#' . $this->extraValue('id') . '" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#' . $this->extraValue('id') . '" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>'
            );
            ?>
            </div>
            <?php
        }
    }
} 
