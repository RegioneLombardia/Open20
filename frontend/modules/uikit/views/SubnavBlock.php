<?php

use trk\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

/**
 * @var $this object
 * @var $data array
 */

$id    = $data['id'];
$class = $data['class'];
$attrs = $data['attrs'];
$attrs_subnav = [];
$attrs_link = [];

// Style
$attrs_subnav['class'][] = 'uk-subnav uk-margin-remove-bottom';
$attrs_subnav['class'][] = $data['subnav_style'] ? "uk-subnav-{$data['subnav_style']}" : '';
// Flex alignment
if ($data['text_align'] && $data['text_align_breakpoint']) {
    $attrs_subnav['class'][] = "uk-flex-{$data['text_align']}@{$data['text_align_breakpoint']}";
    if ($data['text_align_fallback']) {
        $attrs_subnav['class'][] = "uk-flex-{$data['text_align_fallback']}";
    }
} else if ($data['text_align']) {
    $attrs_subnav['class'][] = "uk-flex-{$data['text_align']}";
}
// Link
$attrs_link['class'][] = 'el-link';
// $attrs_link['class'][] = $data['link_style'] ? "uk-link-{$data['link_style']}" : '';
// Margin
$attrs_subnav['uk-margin'] = true;
?>
<div<?= Uikit::attrs(compact('id', 'class'), $attrs) ?>>
<?php $length=count($data['items']);
$exposedSubnav=array_slice($data['items'], 0, 4); 
$hideSubnav=array_slice($data['items'], 4); 


?>
<div class="advanced-subnav right-subnav">
    <div class="always-show-menu link-list-wrapper">
        <ul<?= Uikit::attrs($attrs_subnav) ?>>
            <?php foreach ($exposedSubnav as $item) :
                $attrs_link['href'] = $item['link'];
                $attrs_link['target'] = $item['link_target'] ? $item['link_target'] : '';
                $attrs_link['uk-scroll'] = strpos($item['link'], '#') === 0;
                ?>
                <li class="el-item my-2">
                
                    <?php if ($item['link']) : ?>
                        <a<?= Uikit::attrs($attrs_link) ?>><?= $item['content'] ?></a>
                    <?php else : ?>
                        <a class="el-content uk-disabled"><?= $item['content'] ?></a>
                    <?php endif ?>
                    
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php if(count($hideSubnav)>0){ ?>
        <div class="link-list-wrapper">
            <ul class="link-list collapse  mb-0" id="menu-lista-altre" style="">
                <?php foreach ($hideSubnav as $item) :
                    $attrs_link['href'] = $item['link'];
                    $attrs_link['target'] = $item['link_target'] ? $item['link_target'] : '';
                    $attrs_link['uk-scroll'] = strpos($item['link'], '#') === 0;
                    ?>
                    <li class="el-item my-2">
                    
                        <?php if ($item['link']) : ?>
                            <a<?= Uikit::attrs($attrs_link) ?>><?= $item['content'] ?></a>
                        <?php else : ?>
                            <a class="el-content uk-disabled"><?= $item['content'] ?></a>
                        <?php endif ?>
                        
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
     
        <p class="espandi-voci px-4 small">  
            <a href="#menu-lista-altre" id="menu-lista-button" role="button" aria-expanded="false" aria-controls="menu-lista-altre" data-toggle="collapse" class="collapsed link-altre-voci d-flex align-items-center text-decoration-none">
            <svg class="icon icon-primary  mr-1 espandi-icon icon-sm" role="img" aria-label="Numero risposte">
                                    <use xlink:href="<?=$bootstrapItaliaAsset->baseUrl?>/sprite/material-sprite.svg#dots-vertical"></use>
                                </svg>
            <svg class="icon  d-flex icon-primary mr-1 chiudi-icon icon-sm" role="img" aria-label="Numero risposte">
                                    <use xlink:href="<?=$bootstrapItaliaAsset->baseUrl?>/sprite/material-sprite.svg#dots-horizontal"></use>
                                </svg>  
                    
                        <span class="menu-lista-apri" title="Espandi">Espandi</span>
                        <span class="menu-lista-riduci" title="Riduci">Riduci</span>
                    </a>
        
        </p>
    <?php } ?>
    </div>  
</div>