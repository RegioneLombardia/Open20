<?php

use trk\uikit\Uikit;
?>
<?php
if ($data['showthumb']) {
    ?>
    <li class="lSliderItem sliderItemThumb" data-thumb="<?= $item['image'] ?>"
        data-caption="<?= base64_encode($item['thumbcontent']) ?>">
            <?php
        } else {
            ?>
    <li class="lSliderItem sliderItemDot">
        <?php }
    ?>
    <?php if(!empty($item['image'])): ?>
        <img src="<?= $item['image'] ?>" />
    <?php endif; ?>
    <div class="caption">
        <div class="el-content">
            <?= $item['content'] ?>
        </div>
    </div>
</li>