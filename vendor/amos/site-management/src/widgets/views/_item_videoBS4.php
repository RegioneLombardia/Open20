<?php
$fullscreen = (!empty($optionsVideo['fullscreen']) && $optionsVideo['fullscreen']) ? 'allowfullscreen' : '';
$autoplay = (!empty($optionsVideo['autoplay']) && $optionsVideo['autoplay']) ? 'autoplay;' : '';
?>
<div class="item item-video <?= $active? "active" : "" ?>">
    <?php if($element->path_video) { ?>
        <video class="uploaded-video" width="100%" height="100%" controls>
            <source src="<?=$element->path_video?>" type="video/mp4">
        </video>
    <?php } else {?>
        <iframe class="youtube-video" title="youtube video" id="headerVideo<?=$element->id?>" style="width:100%; border:0;" src="<?=$element->urlEmbeddedVideo?>" allow="<?= $autoplay  ?> encrypted-media" <?= $fullscreen?>></iframe>
    <?php }?>
</div>