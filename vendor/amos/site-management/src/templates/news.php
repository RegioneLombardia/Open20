<?php

use amos\sitemanagement\Module;

?>

<div class="wrap-evidence">
    <div class="box">
        <h1><?= !empty($widgetsValues['title']) ? $widgetsValues['title'] : '' ?></h1>
        <p class="code"><?= !empty($widgetsValues['data']) ? $widgetsValues['data'] : '' ?></p>
        <img class="img-responsive image-evidence"
             src="<?= !empty($widgetsValues['image']) ? $widgetsValues['image'] : '' ?>"
             alt="<?= !empty($widgetsValues['title']) ? $widgetsValues['title'] : '' ?>">
    </div>
    <div class="wrap-item-description">
        <div class="title"><?= Module::t('amossitemanagement', 'NEWS') ?></div>
        <p><?= !empty($widgetsValues['description']) ? $widgetsValues['description'] : '' ?></p>
        <a href="<?= !empty($widgetsValues['link_forward']) ? $widgetsValues['link_forward'] : '' ?>"
           title="<?= Module::t('amossitemanagement', '#go_details') ?>">
            <span class="glyphicon glyphicon-chevron-right"></span><span
                    class="sr-only"><?= Module::tHtml('amossitremanagement', '#go_details') ?></span></a>
    </div>
</div>