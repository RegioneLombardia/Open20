<?php

use trk\uikit\Uikit;
use open20\design\utility\DesignIcon;
use open20\design\assets\BootstrapItaliaDesignAsset;


$currentAsset = BootstrapItaliaDesignAsset::register($this);

$itemExternalClass='it-grid-item-wrapper';
if(!empty($item['content']) && $item['superimposed_caption']==1): 
    $itemExternalClass= $itemExternalClass.' '.'it-grid-item-overlay';
endif;
?>

<style>
    .it-grid-item-wrapper  .it-griditem-text-wrapper  svg.icon{
        fill:currentColor;
        height:16px;
        width:16px;
    }
    <?php if(!empty($item['img_dark'])): ?>
        .img-wrapper.img-dark::before{
            content:'';
            display: block;
            background-color: rgba(23, 50, 77, 0.54);
            position: absolute;
            top: 0;
            bottom: auto;
            left: 0;
            right: 0;
            z-index: 10;
            width: 100%;
            height: 100%;
            z-index:0;
        }
    <?php endif; ?>
</style>
<div class="<?= $itemExternalClass ?>"> 
    <?php if ($item['lightbox']): ?>
        <a href="javascript:void(0)" data-toggle="modal" data-target="#galleryModal<?=$item['contatore']?>">
    <?php elseif (!empty($item['link'])): ?>
        <a href="<?= $item['link'] ?>" target="<?= ($item['link_target'])? '_blank' : '_self'; ?>" title="<?= ($item['link_target'])? 'Apri in una nuova scheda' : 'Apri'; ?> <?= strip_tags($item['content'])?>">
    <?php endif; ?>
        <div class="img-responsive-wrapper">
            <div class="img-responsive">
                <div class="img-wrapper <?= (!empty($item['img_dark']))? 'img-dark' : ''?>">
                    <img src="<?=  $item['image']  ?>" alt="<?=  strip_tags($item['content'])?>">
                </div>
            </div>
        </div>
        <?php if(!empty($item['content'])) : ?>
            <p class="it-griditem-text-wrapper d-inline py-3 px-2" >
                <?= $item['content'] ?>
                <?php if($item['link_target']): ?>
                    <?= DesignIcon::show('open-in-new', DesignIcon::ICON_MD, 'icon icon-xs', $currentAsset)?>
                <?php endif; ?>
            </p>
        <?php endif; ?>

    <?php if (!empty ($item['link']) || ($item['lightbox'])): ?>
        </a>
    <?php endif; ?>
</div>


<div class="modal fade galleryModal<?=$item['contatore']?>" tabindex="-1" role="dialog" id="galleryModal<?=$item['contatore']?>" aria-labelledby="galleryModal<?=$item['contatore']?>">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-transparent">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <?= DesignIcon::show('it-close', DesignIcon::ICON_BI, 'icon icon-lg icon-white', $currentAsset)?>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($item['link'])): ?>
                    <a href="<?= $item['link'] ?>">
                <?php endif; ?>
                        <img src="<?=  $item['image']  ?>" class="el-image w-100" alt="<?= strip_tags($item['content']) ?>" >
                <?php if (!empty($item['link'])): ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>