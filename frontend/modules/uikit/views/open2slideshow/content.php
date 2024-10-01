<?php

use trk\uikit\Uikit;
use app\modules\uikit\Module;

/**
 * @var $this object
 * @var $data array
 * @var $item array
 */


?>



<div class="card-wrapper card-space h-100">
    <div class="card card-bg">
        <div class="card-body">
            <p class="card-title h5 font-weight-bold">
                <?= $item['title'] ?>
            </p>
            <p class="card-text">
                <?= $item['content'] ?>
            </p>
            <?php if ($item['link']) : ?>
                <a href="<?= $item['link'] ?>" class="read-more" title="<?= Module::t('Scopri di più') ?>">
                    <?= Module::t('Scopri di più') ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</div>








