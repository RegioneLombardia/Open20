<?php

use trk\uikit\Uikit;

/**
 * @var $this object
 * @var $data array
 */

$id    = $data['id'];
$class = $data['class'];
$attrs = $data['attrs'];
$attrs_slideshow_items = [];
$attrs_container = [];
// Slideshow
$options = [];
if (!$data['slideshow_height']) {
    $options[] = $data['slideshow_ratio'] ? "ratio: {$data['slideshow_ratio']}" : '';
    $options[] = $data['slideshow_min_height'] ? "minHeight: {$data['slideshow_min_height']}" : '';
    $options[] = $data['slideshow_max_height'] ? "maxHeight: {$data['slideshow_max_height']}" : '';
}
$options[] = $data['slideshow_animation'] ? "animation: {$data['slideshow_animation']}" : '';
$options[] = $data['slideshow_velocity'] ? "velocity: {$data['slideshow_velocity']}" : '';
if ($data['slideshow_autoplay']) {
    $options[] = 'autoplay: true';
    $options[] = !$data['slideshow_autoplay_pause'] ? 'pauseOnHover: false' : '';
    $options[] = $data['slideshow_autoplay_interval'] ? "autoplayInterval: {$data['slideshow_autoplay_interval']}000" : '';
}
$attrs['uk-slideshow'] = implode(';', array_filter($options)) ?: true;
// Slideshow items
$attrs_slideshow_items['class'][] = 'uk-slideshow-items';
$attrs_slideshow_items['class'][] = $data['slideshow_box_shadow'] ? "uk-box-shadow-{$data['slideshow_box_shadow']}" : '';
// Height Viewport
if ($data['slideshow_height']) {
    $options = ['offset-top: true'];
    $options[] = $data['slideshow_min_height'] ? "minHeight: {$data['slideshow_min_height']}" : '';
    switch ($data['slideshow_height']) {
        case 'percent':
            $options[] = 'offset-bottom: 20';
            break;
        case 'section':
            $options[] = 'offset-bottom: !.uk-section +';
            break;
    }
    $attrs_slideshow_items['uk-height-viewport'] = implode(';', array_filter($options));
}
// Container
$attrs_container['class'][] = 'uk-position-relative';
$attrs_container['class'][] = $data['slidenav'] && $data['slidenav_hover'] ? 'uk-visible-toggle' : '';
?>
<?php if ($canSeeBlock): ?>
    <div class="it-carousel-wrapper it-carousel-landscape-abstract-<?= $data['numbers_items'] ?>-cols owl-carousel-design">
        <div class="it-carousel-all owl-carousel owl-loaded owl-drag">
            <div class="owl-stage-outer">
                <div class="owl-stage">
                    <?php foreach ($data['items'] as $i => $item) : ?>
                        <div class="owl-item ">
                            <?= $this->render('slideshow/item', compact('item', 'i', 'data')) ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endif ?>
        <?php if ($data['slidenav']) : ?>
            <?= $this->render('slideshow/slidenav', compact('item', 'data')) ?>
        <?php endif ?>
        <?php if ($data['nav'] && !$data['nav_below']) : ?>
            <?= $this->render('slideshow/nav', compact('item', 'data')) ?>
        <?php endif ?>
    </div>
    <?php if ($data['nav'] && $data['nav_below']): ?>
        <?= $this->render('slideshow/nav', compact('item', 'data')) ?>
    <?php endif ?>
</div>