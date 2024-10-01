<?php
use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\web\View;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$title = (isset($title)) ? $title : Module::t('amosdesign','Gestisci aree di interesse');
$subtitle = (isset($subtitle)) ? $subtitle : Module::t('amosdesign','Puoi scegliere gli argomenti di tuo interesse per ricevere notifiche e notizie mirate all’interno della piattaforma. Dall’elenco a sinistra seleziona le parole chiave ed utilizza la freccia per includerle nell’elenco a destra.');
$sourceTagTitle = (isset($sourceTagTitle)) ? $sourceTagTitle : Module::t('amosdesign','Gli interessi che puoi aggiungere');
$targetTagTitle = (isset($targetTagTitle)) ? $targetTagTitle : Module::t('amosdesign','Gli interessi sulla piatatforma');
$sourceTagSubtitle = (isset($sourceTagSubtitle)) ? $sourceTagSubtitle : Module::t('amosdesign','Seleziona tutto');
$targetTagSubtitle = (isset($targetTagSubtitle)) ? $targetTagSubtitle : Module::t('amosdesign','Seleziona tutto');
$ariaDescribedBy = (isset($ariaDescribedBy)) ? $ariaDescribedBy : Module::t('amosdesign','Inserisci un carattere per iniziare la ricerca');
$infoTooltip = (isset($infoTooltip)) ? $infoTooltip : Module::t('amosdesign','Informazioni per la corretta compilazione');

?>


<?php
                        $this->registerJs(
                            <<<JS

$('input#jsInputSearch').keyup(function() {
    var allSourceTagToChoise = $('.source-tags-to-choose div[id^="tag-form-"]');
    
    var allTargetTagToChoise = $('.target-tags-to-choose div[id^="tag-form-"]');
    var numberOfDigit= this.value.length;
    if(this.value.length > 0) {
       
        $('button#btnjsInputReset').removeClass('d-none');
        var actualValue = this.value.toLowerCase(); 
        
        allSourceTagToChoise.each(function(){
                
                var titleTarget = $(this).find('label span').text().toLowerCase();
               // alert($(this)[0].getElementsByTagName('label')[0].innerHTML)
                if( (titleTarget.indexOf(actualValue)) >= 0 ){
                    var actualSpanString=$(this).find('label span').text();//tutto tx che corrisponde
                    var firstPart=actualSpanString.substr(0, titleTarget.indexOf(actualValue));
                    var commonPart=actualSpanString.substr(titleTarget.indexOf(actualValue), numberOfDigit);
                    var lastPart=actualSpanString.substr(numberOfDigit+titleTarget.indexOf(actualValue));
                    $(this)[0].getElementsByTagName('label')[0].innerHTML='<span>'+firstPart+'</span><span class="bg-tags-list-bg">'+commonPart+'</span><span>'+lastPart+'</span>';

                    $(this).removeClass('d-none');
                    $(this).addClass('d-block');
                } else {
                    $(this).removeClass('d-block');
                    $(this).addClass('d-none');

                }
        });
        allTargetTagToChoise.each(function(){
                var titleTarget = $(this).find('label span').text().toLowerCase();//titolo checkbox
                
                if( (titleTarget.indexOf(actualValue)) >= 0 ){//deve essere solo sottostringa iniziale?
                    var actualHTML=$(this)[0].getElementsByTagName('label')[0].innerHTML;
                    var actualSpanString=$(this).find('label span').text();//tutto tx che corrisponde
                    var firstPart=actualSpanString.substr(0, titleTarget.indexOf(actualValue));
                    var commonPart=actualSpanString.substr(titleTarget.indexOf(actualValue), numberOfDigit);
                    var lastPart=actualSpanString.substr(numberOfDigit+titleTarget.indexOf(actualValue));
                    $(this)[0].getElementsByTagName('label')[0].innerHTML='<span>'+firstPart+'</span><span class="bg-tags-list-bg">'+commonPart+'</span><span>'+lastPart+'</span>';                    
                    
                    $(this).removeClass('d-none');
                    $(this).addClass('d-block');
                } else {
                    $(this).removeClass('d-block');
                    $(this).addClass('d-none');
                }
        });
    } else {
        allSourceTagToChoise.each(function(){
            var actualSpanString=$(this).find('label span').text();
            $(this)[0].getElementsByTagName('label')[0].innerHTML='<span>'+actualSpanString+'</span>';

        });
        allTargetTagToChoise.each(function(){
            var actualSpanString=$(this).find('label span').text();       
            $(this)[0].getElementsByTagName('label')[0].innerHTML='<span>'+actualSpanString+'</span>';

        });
        
        allSourceTagToChoise.removeClass('d-none');
        allTargetTagToChoise.removeClass('d-none');
        $('button#btnjsInputReset').addClass('d-none');

    }
});

$('button#btnjsInputReset').on('click',function(){
    var allSourceTagToChoise = $('.source-tags-to-choose div[id^="tag-form-"]');
    var allTargetTagToChoise = $('.target-tags-to-choose div[id^="tag-form-"]');

    allSourceTagToChoise.each(function(){ $(this).parent().removeClass('d-none') });
    allTargetTagToChoise.each(function(){ $(this).parent().removeClass('d-none') });

    $('input#jsInputSearch').val('');
    $(this).toggleClass('d-none');
});

JS
, View::POS_READY );
?>



<div class="transfer-container it-transfer-block">
    <div class="title-container">
        <p class="h6 text-uppercase text-teriary"><?= $title ?></p>
        <p class="text-muted">
            <?= $subtitle ?>
        </p>
    </div>
    <div class="form-group mb-0 mt-5">
        <div class="input-group">
            <label for="jsInputSearch" class="sr-only"><?=Module::t('amos-design','Ricerca tag')?></label>
            <input type="search" class="form-control small text-teriary" id="jsInputSearch" name="js-input-search" placeholder="Cerca">
            <div class="input-group-append">
                    <button class="btn btn-xs d-none rounded-0" type="reset" id="btnjsInputReset">
                        <svg class="icon icon-tertiary">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-close"></use>
                        </svg>
                        <span class="sr-only"><?=Module::t('amos-design','Pulisci filtro di ricerca')?></span>
                    </button>
            </div>
            
                
            
        </div>
        <div class="described-by">
            <small id="describedBy jsInputSearch" class="form-text text-muted">
                <?= $ariaDescribedBy ?>
                <?php if ($infoTooltip) : ?>
                    <a href="javascript::void(0)" class="bi-form-field-tooltip-info" data-toggle="tooltip" data-html="true" title="<?= $infoTooltip ?>">
                        <span class="icon">
                            <svg class="icon icon-xs">
                                    <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-info-circle"></use>
                            </svg>
                        </span>
                        <span class="sr-only"><?=Module::t('amos-design','Informazioni per la corretta compilazione')?></span>
                    </a>
                <?php endif ?>
            </small>
    </div>
    <div class="row mt-5">
        <div class="col-xs-12 col-md-5 source-tags-to-choose">
            <p class="mb-3 font-weight-bold primary-color text-uppercase"><?= $sourceTagTitle ?></p>
            <div class="it-transfer-wrapper source">
                    <div class="transfer-header">                                     
                        <div class="form-check" aria-describedby="checkbox">
                            <input type="checkbox" id="source-checkbox">
                                <label for="source-checkbox">
                                    <span class="font-weight-semibold text-uppercase text-truncate"> <?= $sourceTagSubtitle ?></span>
                                </label>
                                            
                        </div>
                    </div>
                    <div class="transfer-scroll">
                        <div class="transfer-group">
                            <?php foreach ($source as $sourceElement): ?>
                                <div class="form-check" id="tag-form-<?=$sourceElement['id'] ?>" aria-describedby="checkbox">
                                    <input type="checkbox" id="<?= $sourceElement['id'] ?>">
                                    <label for="<?=$sourceElement['id'] ?>">           
                                        <span><?= $sourceElement['name'] ?></span>           
                                    </label>
                                    <div class="form-check" aria-describedby="checkbox" id="tag-form-<?=$sourceElement[id] ?>">
                                        <input type="checkbox" id="prova<?=$sourceElement['id'] ?>">
                                        <label for="prova<?=$sourceElement['id'] ?>">           
                                            <span>Annidato <?= $sourceElement['name'] ?></span>           
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>       

                        </div>
                    </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-2">
                                <!-- transfer buttons-->
                                <div class="it-transfer-buttons">
                                    <a class="transfer border-0" href="#" aria-label="Sposta avanti">
                                        <svg class="icon icon-lg">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-arrow-right-circle"></use>
                                        </svg>
                                    </a>
                                    <span class="sr-only">Label for aroow right</span>
                                    <a class="backtransfer border-0" href="#" aria-label="Sposta indietro">
                                        <svg class="icon icon-lg">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-arrow-left-circle"></use>

                                        </svg>
                                    </a>
                                    <span class="sr-only">Label for aroow left</span>
                                    <a class="reset border-0" href="#" aria-label="Reset">
                                        <svg class="icon icon-lg">
                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-exchange-circle "></use>

                                        </svg>
                                    </a>
                                    <span class="sr-only">Label for reset icon</span>
                                </div>
        </div>
        <div class="col-xs-12 col-md-5 target-tags-to-choose">
            <p class="mb-3 font-weight-bold primary-color text-uppercase"><?= $targetTagTitle ?></p>
            <div class="it-transfer-wrapper target">
                <div class="transfer-header">
                    <div class="form-check" aria-describedby="checkbox">
                        <input type="checkbox" id="target-checkbox">
                        <label for="target-checkbox">
                            <span class="font-weight-semibold text-uppercase text-truncate"> <?= $targetTagSubtitle ?></span>
                        </label>
                    </div>
                </div>
                <div class="transfer-scroll">
                    <div class="transfer-group">
                    <?php foreach ($target as $targetElement): ?>
                                <div class="form-check" aria-describedby="checkbox" id="tag-form-<?=$sourceElement[id] ?>">
                                    <input type="checkbox" id="<?= $targetElement['id'] ?>">
                                    <label for="<?=$targetElement['id'] ?>">           
                                        <span><?= $targetElement['name'] ?></span>           
                                    </label>

                                    
                                </div>
                    <?php endforeach ; ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cta-container mt-5 d-flex justify-content-between">
        <a title="<?= $manageLinkTitle ?>" class="<?= $manageLinkCssClass ?>" href="<?= $manageLinkHref ?>"><?= $manageLinkLabel ?></a>
        <button class="btn btn-primary" type="submit"><?= $submitButtonLabel ?></button>
        
    </div>  
    
</div>


