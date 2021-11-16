<?php 
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
?>



         
<div id="collapseDiv1-lft" class="collapse-div collapse-left-icon accordion-dettaglio-campagna" role="tablist">
  <div class="collapse-header" id="heading2-lft">
    <button class="campain-accordion text-decoration-none font-weight-normal border-top-0" data-toggle="collapse" data-target="#collapse2-lft" aria-expanded="false" aria-controls="collapse2-lft">
      <div class="information-campain d-flex align-items-center flex-wrap position-relative">
        <div class="col-8 col-sm-5 col-lg-3 order-1 d-flex align-items-start align-items-md-center">
            <a class="accordion-icon btn btn-xs btn-icon p-1">
                <span class="rounded-icon rounded-primary p-1">
                        <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use></svg>
                </span>
            </a>
            <a class="mb-0 ml-2" href="#">Campagna Test</a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 order-4 order-lg-2 d-flex align-items-center ">
            <p class=" text-muted mb-0 small">Mario Rossi</p>
            <p class=" text-muted ml-2 ml-md-5 mb-0 small">Sviluppo</p>
        </div>
        <p class="col-12 col-sm col-lg-3 order-5 order-lg-3 text-left text-sm-right text-lg-left text-muted small mb-0">12/03/2020 ore 13.34</p>
        
        <div class="cta-container order-2 order-lg-4 col-4 col-sm-2 col-lg-1 d-flex align-items-center justify-content-end justify-content-lg-start">  
            <a class="btn btn-xs btn-icon p-1" href="/events/event-dashboard/view?id=22" title="" data-toggle="tooltip" data-original-title="Modifica">
                <span class="rounded-icon rounded-secondary p-1">
                    <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                </span>
                <span class="sr-only">Modifica </span>
            </a>
            <a class="btn btn-xs btn-icon p-1" href="/events/event-dashboard/delete-event?id=68" title="" data-toggle="tooltip" data-original-title="Elimina">
                <span class="rounded-icon rounded-danger p-1">
                    <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                </span><span class="sr-only">Elimina</span>
            </a>
        </div>
        <p class="status-container col-12 col-sm-5 col-lg-2 order-5 order-sm-3 order-lg-5 text-muted small mb-0 d-flex justify-content-sm-end ">Inviata parzialmente</p>        
      </div>
    </button>
  </div>
  <div id="collapse2-lft" class="collapse" role="tabpanel" aria-labelledby="heading2-lft">
    <div class="collapse-body d-flex flex-column bg-light-grey py-3">
        <div class="detail-intestation d-flex align-items-center p-2">
        
            <span class="btn-xs btn-icon p-1 rounded-icon rounded-secondary rounded-circle bg-secondary p-1 ">
                <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_search"></use></svg>
            </span>
        
            <p class="ml-2 text-uppercase mb-0 small font-weight-bold">Dettagli</p>
        </div>
        <div class="detail-value-intestation d-block d-md-flex flex-column flex-md-row justify-content-between align-items-start p-2">
            <div class="canale-container col-12 col-md-3 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary p-1 text-uppercase mb-0 small font-weight-bold full-size-bg-100">Canale</p>
                <p class="text-black mb-0 p-1 small font-weight-bold">Newsletter</p>
            </div>

            <div class="destinatari-container col-12 col-md-3 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary text-uppercase p-1 mb-0 small font-weight-bold full-size-bg-100">Destinatari</p>
                <p class=" text-black p-1 mb-0 small">1270</p>
            </div>

            <div class="data-inizio-container col-12 col-md-2 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary text-uppercase p-1 mb-0 small font-weight-bold full-size-bg-100">Data inizio</p>
                <p class=" text-black p-1 mb-0 small">13/03/2020 ore 14:30</p>
            </div>

            <div class="data-fine-container col-12 col-md-2 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary text-uppercase p-1 mb-0 small font-weight-bold full-size-bg-100">Data fine</p>
                <p class=" text-black p-1 mb-0 small">13/03/2020 ore 14:30</p>
            </div>            

            <div class="invio-container col-12 col-md-2 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary p-1 pl-md-5 text-uppercase mb-0 small font-weight-bold full-size-bg-100">Invio</p>
                <div>
                    <div class="progress-bar-label p-1 pl-md-5 text-left"><span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                    </div>
                    <div class="m-1 progress ml-md-5">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>    
        </div>         
    </div>
  </div>
  
</div>


