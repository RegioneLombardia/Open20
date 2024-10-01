<?php

use trk\uikit\Uikit;
use app\modules\uikit\Module;


/**
 * @var $this object
 * @var $data array
 * @var $item array
 */

$image = '';
// Image
if ($item['image']) {
    $attrs_image['class'][] = 'el-image h-100';
    $attrs_image['alt'] = Module::t('Immagine del carousel');
  
    if (Uikit::isImage($item['image']) == 'svg') {
        $image = Uikit::image($item['image'], array_merge($attrs_image, ['width' => $item['image_width'], 'height' => $item['image_height']]));
    } elseif ($item['image_width'] || $item['image_height']) {
        $image = Uikit::image($item['image'], $attrs_image);
    } else {
        $image = Uikit::image($item['image'], $attrs_image);
    }
}


?>
<div class="it-single-slide-wrapper">
    <?php if($data['carousel_item_style']=='img-carousel' && ($image)): ?>
        <?php if ($item['link']) : ?>
            <a href="<?= $item['link'] ?>" title="<?= Module::t('Scopri di più') ?>">
        <?php endif; ?>
                <?= $image ?>
        <?php if ($item['link']) : ?>
            </a>
        <?php endif; ?>
    <?php else: ?>
        <?= $this->render('content', compact('item', 'data')) ?>
    <?php endif; ?>
</div>