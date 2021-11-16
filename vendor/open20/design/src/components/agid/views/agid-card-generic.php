<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

$columnWidth = (isset($columnWidth)) ? $columnWidth : 'col-12';
$topicIcon = (isset($topicIcon)) ? $topicIcon : 'help-circle-outline';

$filterString='';

if(is_array($tags)){
    
    foreach($tags as $t){
        
            $filterString=$filterString .'filtro-'. str_replace(' ', '_', strtolower($t->nome)).' ';
        
           
    
        
        
    }
}else{
    
    $tags=str_replace(' ', '_', $tags); 
    $filterString=$filterString .'filtro-'. strtolower($tags). ' ';
}

?>


<div data-filter="<?=$filterString ?>" class="<?= $columnWidth ?> agid-generic-card-container <?= $additionalCssExternalClass ?>">
    <div class="py-3 h-100 d-flex flex-column shadow">
        <?php if($categoryName || $categoryIcon){ ?>
            <div class="categoria-box-card px-4 pb-2 d-flex align-items-center">                                   
                <div>
                    <svg class="icon icon-padded rounded-circle icon-white bg-primary d-flex  mr-1" role="img" aria-label="Numero risposte">
                        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $categoryIcon ?>"></use>
                    </svg>
                </div>
                <div class="text-uppercase font-weight-bold primary-color">
                    <?= $categoryName ?>
                </div> 
                
            </div>
        <?php } ?>
        <div class="px-4 flex-grow-1">
            <div class="h5  ">  
                <a class="text-black" title="Esplora avviso" href="<?= $urlDetail ?>"><?= $cardTitle ?></a>
            </div>
            <div class="text-serif pt-3">
                <div>
                    <?= $cardText ?>
                </div>
                <?php if($refArea){ ?>
                    <div class="mt-3">
                        <p>
                            <strong>Area di riferimento:</strong>
                            <a href="<?=$refAreaLink?>" title="Vai alla pagina <?= $refArea ?>">
                                <?= $refArea ?>
                            </a>
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if($hideCta){ ?>
            <div class="cta-box-card px-4 py-3 uk-section- uk-visible@xl uk-section">
                <div class="uk-container">
                    <div class="read-more ">
                        <a class="text-uppercase font-weight-bold" title="Esplora <?= $cardTitle ?>" href="<?= $urlDetail ?>">Leggi di più →</a> 
                    </div>   
                </div> 
            </div>
        <?php } ?>
    </div>                                   
</div>