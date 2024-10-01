<?php
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\helpers\Url;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$level = 0;

?>

<div class="row row border-top row-column-border row-column-menu-left">
    <div class="col-lg-3 col-md-4 index-left-container">
        <!--parte indice di sx uguale a cagliari-->
        
        <!--parte indice di sx--><!--class="sticky-wrapper navbar-wrapper vertical-menu-left-part"-->
        <aside  id="menu-sinistro" >
            <!-- it-top-navscroll-->
            <nav class="navbar it-navscroll-wrapper navbar-expand-lg w-100">
                <!--mobile-->
                <!--<button class="custom-navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" data-target="#navbarNav"><span class="it-list"></span>Indice della pagina</button>-->
                
                <div id="navbarNav" class="w-100">
                    <!--<div class="overlay"></div>
                    <div class="close-div sr-only">
                        <button class="btn close-menu" type="button">
                            <span class="it-close"></span>Chiudi
                        </button>
                    </div>
                    <a class="it-back-button" href="#">
                        <svg class="icon icon-primary align-top">
                        <use xlink:href=" '.$bootstrapItaliaAsset->baseUrl.'/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-left"></use>
                        </svg>
                        <span>Torna indietro</span>
                    </a>-->

                    <div class="menu-wrapper">
                        <div class="link-list-wrapper menu-link-list">
                        
                            <a data-toggle="collapse" href="#lista-paragrafi" role="button" aria-expanded="true" aria-controls="lista-paragrafi" class="no_toc text-decoration-none">
                                <h3 id="titolo-indice" class="primary-color position-relative d-flex align-items-center justify-content-between">Indice della pagina
                                    <svg class="icon icon-primary">
                                        <use xlink:href=" <?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#chevron-down"></use>
                                    </svg>
                                </h3>
                                
                            </a>
                            


                            <!--<h3 class="no_toc">Indice della pagina</h3>-->
                            <ul id="lista-paragrafi" class="link-list collapse show">                                
                                <?php foreach ($items as $item) : ?>
                                <li class="nav-item active">
                                    <a href='<?= \Yii::$app->params['platform']['frontendUrl'] . Url::current()?>#<?= str_replace(' ', '', $item->title) ?>'><?= $item->title ?></a>
                                </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>
    </div>
    <div class="col-lg-9 col-md-8  border-top-0 it-page-sections-container index-right-container">
        <!--parte contenuto di dx-->
        <div id='subnav-c75' class='breadcrumb text-600'>
            <ul class='uk-subnav uk-margin-remove-bottom lvl-<?= $level?>'>
                <?php
                
                foreach ($items as $item) 
                {
                    if($level < $maxlevel){
                        echo $this->render('verticalmenulist/item', compact('item', 'data', 'maxlevel', 'level'));
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>