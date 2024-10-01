<?php

use amos\sitemanagement\Module;
?>

<div class="wrap-evidence">
    <div class="box">
        <h1><?= !empty($widgetsValues['title']) ? $widgetsValues['title'] : '' ?></h1>
        <p class="code"><?= !empty($widgetsValues['code']) ? $widgetsValues['code'] : '' ?></p>
        <img class="img-responsive image-evidence"
             src="<?= !empty($widgetsValues['image']) ? $widgetsValues['image'] : '' ?>"
             alt="<?= !empty($widgetsValues['title']) ? $widgetsValues['title'] : '' ?>">
    </div>
    <div class="wrap-item-description">
        <div class="title"><?= !empty($widgetsValues['special']) ? $widgetsValues['special'] : '' ?></div>
        <p><?= !empty($widgetsValues['description']) ? $widgetsValues['description'] : '' ?></p>
        <a href="<?= !empty($widgetsValues['link_forward']) ? $widgetsValues['link_forward'] : '' ?>"
           title="<?= Module::t('amossitrmanagement', '#go_details') ?>">
            <span class="glyphicon glyphicon-chevron-right"></span><span
                    class="sr-only"><?= Module::tHtml('amossitemanagement', '#go_details') ?></span></a>
    </div>
</div>