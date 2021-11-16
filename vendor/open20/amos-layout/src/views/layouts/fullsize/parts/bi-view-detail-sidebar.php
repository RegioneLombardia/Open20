<?php

use open20\amos\layout\Module;

$hideAttachment = isset($hideAttachment) ? $hideAttachment : false;
$hideTags = isset($hideTags) ? $hideTags : false;

?>

<div class="bi-view-detail-sidebar">
    <div class="row">
        
        <!--parte allegati-->
        <?php if (!$hideAttachment) : ?>
            <div class="allegati-container col-xs-12">
                <p class="h4 text-uppercase"><?= Module::t('amoslayout', 'Allegati') ?></p>
                <div>
                    <?= $attachments ?>
                </div>
            </div>
        <?php endif ?>

        <?php if (!$hideTags) : ?>
            <!--parte tag di interesse-->
            <div class="tags-container col-xs-12" id="section-tags">
                <p class="h4 text-uppercase"><?= Module::t('amoslayout', 'Tag di interesse') ?></p>
                <div>
                    <?= $tags ?>
                </div>
            </div>
        <?php endif ?>

    </div>
</div>