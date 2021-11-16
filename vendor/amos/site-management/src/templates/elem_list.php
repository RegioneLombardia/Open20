<?php

?>

<div class="list-elem col-xs-12">
    <div class="image">
        <img src="<?= !empty($widgetsValues['image']) ? $widgetsValues['image']  : '' ?>">
    </div>
    <div class="info">
        <p><?= !empty($widgetsValues['text_editor']) ? $widgetsValues['text_editor'] : '' ?></p>
    </div>
</div>

