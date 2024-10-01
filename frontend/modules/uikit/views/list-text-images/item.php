<?php

use trk\uikit\Uikit;

use open20\design\utility\DesignIcon;
use open20\design\assets\BootstrapItaliaDesignAsset;


$currentAsset = BootstrapItaliaDesignAsset::register($this);

/**
 * @var $this object
 * @var $data array
 * @var $item array
 */

$attrs_grid = [];
$attrs_cell = [];

$imageClass='';
if($item['rounded_img']){
    $imageClass='rounded';
}

























?>
<?php if($item['link_image']): ?>
    <div class="<?= ($data['vertical_element'])? 'col-md-12' : 'col-md-3 justify-content-start' ?> d-flex justify-content-center mb-3 mb-md-0">
        <?php if($item['link'] ): ?>
            <?= \yii\helpers\Html::a(\yii\helpers\Html::img($item['link_image'],['class'=>'img-fluid ' .$imageClass, 'alt'=>'Immagine dell\'elemento']),$item['link'],['target'=>$item['link_target']]) ?>    
        <?php else: ?>
                <?= \yii\helpers\Html::img($item['link_image'],['class'=>'img-fluid '.$imageClass, 'alt'=>'Immagine dell\'elemento']) ?>    
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="<?=(!($item['link_image']) || $data['vertical_element'] )? 'col-md-12 justify-content-center' : 'col-md-9 justify-content-md-start text-md-left' ?> d-flex flex-column justify-content-center text-center"> 
    <?php if($item['link']): ?>
        <a href="<?= $item['link'] ?>" target="<?= ($item['link_target'])? '_blank' : '_self'; ?>" title="<?= ($item['link_target'])? 'Apri in una nuova scheda' : 'Apri'; ?> <?= $item['title']?>" class="title-one-line <?= ($data['vertical_element'])? '' : 'justify-content-md-start' ?> justify-content-center link-list-title  mr-md-3 mb-0">
    <?php endif; ?>
    <p class="h4">    
        <?= $item['title'] ?>
        <?php if($item['link_target']): ?>
            <?= DesignIcon::show('open-in-new', DesignIcon::ICON_MD, 'icon icon-xs', $currentAsset)?>
        <?php endif; ?>
    </p>
    <?php if($item['link']): ?>
        </a>
    <?php endif; ?>
    <?php if($item['subtitle']): ?>
        <p class="lead  mb-2"><?= $item['subtitle'] ?></p>  
    <?php endif; ?>
    <?php if($item['content']): ?>
        <p class="mb-0 title-two-line-desktop">
            <?= $item['content'] ?>
        </p>
    <?php endif; ?>

</div>



