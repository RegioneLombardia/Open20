<?php
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\helpers\Url;
use open20\design\utility\DesignIcon;
use open20\design\Module;

/** @var array $data */
/** @var array $items */

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$tot =count($items);
$i= 0;
$minDepth=$items[0]->depth;
$maxDepth=$items[count($items)-1]->depth;
$childDivOpened = 0;

$js = <<<JS

var collapseButton = $('.left-page-menu-container #indexMenuButton[data-toggle="collapse"]');
var collapseContent = $('.left-page-menu-container #indexMenuCollapse.collapse');

var collapseCurrentButton = $('.lvl-current >.collapse-header button[data-toggle="collapse"]');
var collapseCurrentContent = $('.lvl-current >.collapse');


window.addEventListener("resize", getMobileVisualization);
window.addEventListener("load", getMobileVisualization);

function getMobileVisualization(e){
    if (window.innerWidth <= 768) { 
        /**/
        collapseButton.attr("aria-expanded","false");
        collapseContent.removeClass("show");
        
    }else{
        collapseButton.attr("aria-expanded","true");
        collapseContent.addClass("show");
        collapseCurrentButton.attr("aria-expanded","true");
        collapseCurrentContent.addClass("show");
    }
}
        
$(document).on('click', '.trigger-link', function(e){
    e.stopPropagation();    
});
        
JS;
    \Yii::$app->controller->view->registerJs($js,  \yii\web\View::POS_END);
?>

<?php if($data['menu_style'] == 'dropdown') : ?>
    <aside class="left-page-menu-container dropdown-style-menu">
        <a id="indexMenuButton" href="#indexMenuCollapse" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="indexMenuCollapse" class="text-uppercase text-decoration-none font-weight-semibold mb-0 d-flex align-items-center justify-content-between">
            <?= Module::t('amosdesign', 'In questa sezione') ?>
            <span class="mdi mdi-chevron-down"></span>
        </a>
        <div id="indexMenuCollapse" class="collapse show navbar it-navscroll-wrapper navbar-expand-lg w-100 p-0">
            <div class="menu-wrapper w-100 py-0">
                <ul id="left-page-menu" class="collapse-div left-page-menu link-list collapse show  ">
                    <?php while ($i < $tot) : ?>
                        <?php 
                            $currentLink=$items[$i]->link;
                            if (!strstr($currentLink, 'http')) {
                                $currentLink=\Yii::$app->params['platform']['frontendUrl'].$items[$i]->link;
                            } 
                        ?>
                        <li class="nav-item <?='lvl-'. $items[$i]->depth?> <?=($items[$i]->id == Yii::$app->menu->current->id)? 'lvl-current' : ''?>">
                        <!-- ci sono figli -->
                        <?php if(!is_null($items[$i + 1]->depth) && $items[$i]->depth < $items[$i + 1]->depth) : ?>
                            <?php $childDivOpened++; ?>
                            <div class="collapse-header" id="heading<?= $items[$i]->id ?>">
                                <button class="p-0 border-0 inner-link" data-toggle="collapse" data-target="#collapse<?= $items[$i]->id ?>" aria-expanded="false" aria-controls="collapse<?= $items[$i]->id ?>">
                                    <a title="Vai alla pagina <?= $items[$i]->title ?>" href="<?= $currentLink ?>" class="trigger-link"><?= $items[$i]->title ?></a>
                                </button>
                            </div>

                            <div id="collapse<?= $items[$i]->id ?>" class="collapse collapse-inner" role="region" aria-labelledby="heading<?= $items[$i]->id ?>" >
                                <div class="collapse-body p-0 ">
                                    <ul>
                        <!-- non ci sono figli -->
                        <?php else : ?>
                                <div class="inner-link">
                                    <a title="Vai alla pagina <?= $items[$i]->title ?>" href="<?= $currentLink ?>"><?= $items[$i]->title ?></a>
                                </div>
                            </li>
                            <?php if(!is_null($items[$i + 1]->depth)){ ?>
                                <?php for($k=0; $k < abs($items[$i]->depth - $items[$i + 1]->depth); $k++) : ?>

                                    <?php if($childDivOpened > 0) :
                                        $childDivOpened--; ?>
                                        </ul>
                                        </div>
                                        </div>
                                    <?php endif; ?>
                                    </li>
                                <?php endfor; ?>
                            <?php } ?>
                        <?php endif; ?>

                        <?php $i++; ?>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </aside>

<?php else: ?>
    <aside class="left-page-menu-container lateral-index-style-menu position-sticky z-index-1">
        <a id="indexMenuButton" href="#indexMenuCollapse" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="indexMenuCollapse" class="text-uppercase text-decoration-none font-weight-semibold mb-0 d-flex align-items-center justify-content-between">
            <?= Module::t('amosdesign', 'In questa sezione') ?>
            <span class="mdi mdi-chevron-down"></span>
        </a>
        <nav id="indexMenuCollapse" class="collapse show navbar it-navscroll-wrapper navbar-expand-lg w-100 p-0">
            <div class="menu-wrapper w-100 py-0">
                <ul id="left-page-menu" class="left-page-menu link-list collapse show">
                    <?php foreach ($items as $item) : ?>
                        <?php 
                            $currentLink=$item->link;
                            if (!strstr($currentLink, 'http')) {
                                $currentLink=\Yii::$app->params['platform']['frontendUrl'].$item->link;
                            } 
                        ?>

                        <li class="nav-item active <?='lvl-'. $item->depth?> <?=($item->id == Yii::$app->menu->current->id)? 'lvl-current' : ''?>">
                            <a title="Vai alla pagina <?= $items[$i]->title ?>" class="inner-link"  href='<?=$currentLink ?>'><?= $item->title ?></a>

                        </li>
                        <?php if($item->id == Yii::$app->menu->current->id){?>
                            <div class="position-relative">
                                <hr class="menu-line d-none d-md-block">
                                <?= DesignIcon::show('rhombus-medium', DesignIcon::ICON_MD, 'icon icon-primary icon-current d-none d-md-block', $bootstrapItaliaAsset)?>
                            </div>

                        <?php } ?>
                    <?php endforeach ?>
                </ul>
            </div>
        </nav>
    </aside>
<?php endif; ?>