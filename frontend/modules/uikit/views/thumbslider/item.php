<?php

use trk\uikit\Uikit;
use app\modules\uikit\Module;


if (!empty($item['category'])) {
    $categoryColor = '#000';
    if (!empty($item['category_color'])) {
        $categoryColor = $item['category_color'];
    }
}


$titleThumb = base64_encode($item['content']);
?>
<?php
if (!$data['hidethumb']) {
?>
    <li class="lSliderItem sliderItemThumb" data-link="<?= $item['link'] ?>" data-thumb="<?= $item['image'] ?>" data-caption="<?= $titleThumb ?>" data-category="<?= $item['category'] ?>" data-category-color="<?= $categoryColor ?>">

    <?php
} else {
    ?>
    <li class="lSliderItem sliderItemDot">
    <?php }
    ?>
    <img src="<?= $item['image'] ?>" alt="<?= Module::t('Immagine dello slider') ?>" />
    <div class="caption">
        <div class="el-content container text-white">
            <?php
            if (!empty($item['link'])) {
            ?>
                <div>
                    <div class="h1">
                        <a href="<?= $item['link'] ?>" target="<?= $item['link_target'] ?>" title="Vai alla pagina <?= strip_tags($item['content']) ?>">
                            <?= $item['content'] ?>
                        </a>
                    </div>
                    
                </div>
            <?php
            } else {
            ?>
                <?= $item['content'] ?>
            <?php }
            ?>
        </div>
    </div>
    </li>