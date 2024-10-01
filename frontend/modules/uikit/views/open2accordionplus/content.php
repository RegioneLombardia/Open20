<?php

use trk\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;
use app\modules\uikit\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

/**
 * @var $this object
 * @var $data array
 * @var $item array
 */


?>
<?php if ($item['content']) : ?>
    <div>
        <?= $item['content'] ?>
    </div>
<?php endif ?>
<?php if ($item['accordion_image']) : ?>
    <div>
        <img class="img-fluid mt-3 <?= $item['accordion_image_class']?>" 
        src="<?= $item['accordion_image'] ?>"
        alt="<?=Module::t('Immagine accordion')?>"
        
        >
    </div>
<?php endif ?>
<?php if ($item['read_more_link']) : ?>
    <div class="d-flex  justify-content-end">
        <a href="<?= $item['read_more_link'] ?>" target="<?= $item['link_target'] ?>" title="<?=$item['accordion_read_more_link_text'] ? $item['accordion_read_more_link_text'] : Module::t('Leggi di più');?>">
            <?=$item['accordion_read_more_link_text'] ? $item['accordion_read_more_link_text'] : $item['read_more_link'];?>
            <?= DesignIcon::show('it-arrow-right', DesignIcon::ICON_BI, 'icon icon-primary icon-sm', $currentAsset); ?>
        </a>
    </div>
       
<?php endif;


