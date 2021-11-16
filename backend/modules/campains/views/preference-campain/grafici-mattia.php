<?php
    use kartik\widgets\SwitchInput;
    use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
    $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
?>

<div class="altre-notizie neutral-1-bg-a1 p-4">
    <div>
        <div class="row pl-2 pr-3 mb-3 align-items-center">
            <div class="col-4 col-xs-8 col-md-9 pl-0 section-title text-uppercase font-weight-bold">Altre notizie</div>
            <div class="col-xs-2 col-lg-1 col-4 ml-auto d-flex justify-content-center justify-content-md-around">
                <div class="arrow d-flex">
                    <a class="btn btn-xs btn-icon p-1 mr-auto" href="#" title="" data-toggle="tooltip" data-original-title="Sposta sezione altre notizie in su">
                        <svg class="icon arrow-icon icon-black">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
                        </svg>
                    </a>
                    <a class="btn btn-xs btn-icon p-1 ml-auto" href="#" title="" data-toggle="tooltip" data-original-title="Sposta sezione altre notizie in giù">
                        <svg class="icon arrow-icon icon-black">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
                <a class="btn btn-xs btn-icon p-1" href="/events/event-dashboard/view?id=22" title="" data-toggle="tooltip" data-original-title="Modifica sezione">
                    <span class="rounded-icon rounded-secondary p-1">
                        <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                    </span>
                    <span class="sr-only">Modifica sezione</span>
                </a>
                <a class="btn btn-xs btn-icon p-1" href="/events/event-dashboard/delete-event?id=68" title="" data-toggle="tooltip" data-original-title="Elimina sezione">
                    <span class="rounded-icon rounded-danger p-1">
                        <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                    </span><span class="sr-only">Elimina sezione</span>
                </a>
            </div>
        </div>
    </div>
    <div class="news-contenuto-container">
        <div class="news contenuto">
            <div class="row px-3 py-2 mb-2 align-items-center">
                <div class="text-left text-md-center col-3 col-xs-2 col-lg-1"><img src="https://picsum.photos/1600/900?random=3" alt="immagine contenuto" class="img-fluid"></div>
                <div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">Contenuto</div>
                <div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">Notizia Secondaria</div>
                <div class="col-xs-1 col-4 ml-auto d-flex justify-content-start justify-content-md-center">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Disattiva contenuto in evidenza">
                        <svg class="icon star-icon active">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
                        </svg>
                    </a>
                </div>
                <div class="col-xs-2 col-lg-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <div class="arrow d-flex">
                        <a class="btn btn-xs btn-icon mr-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta contenuto notizia secondaria in su">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
                            </svg>
                        </a>
                        <a class="btn btn-xs btn-icon ml-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta contenuto notizia secondaria in giù">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Modifica contenuto">
                        <span class="rounded-icon rounded-secondary p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                        </span>
                        <span class="sr-only">Modifica contenuto</span>
                    </a>
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Elimina contenuto">
                        <span class="rounded-icon rounded-danger p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                        </span>
                        <span class="sr-only">Elimina contenuto</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="news contenuto">
            <div class="row px-3 py-2 align-items-center mb-4">
                <div class="text-left text-md-center col-3 col-xs-2 col-lg-1"></div>
                <div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">Contenuto</div>
                <div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">Una notizia interessante</div>
                <div class="col-xs-1 col-4 ml-auto d-flex justify-content-start justify-content-md-center">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Attiva contenuto in evidenza">
                        <svg class="icon star-icon disabled">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
                        </svg>
                    </a>
                </div>
                <div class="col-xs-2 col-lg-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <div class="arrow d-flex">
                        <a class="btn btn-xs btn-icon mr-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta contenuto una notizia interessante in su">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
                            </svg>
                        </a>
                        <a class="btn btn-xs btn-icon ml-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta contenuto una notizia interessante in giù">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Modifica contenuto">
                        <span class="rounded-icon rounded-secondary p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                        </span>
                        <span class="sr-only">Modifica contenuto</span>
                    </a>
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Elimina contenuto">
                        <span class="rounded-icon rounded-danger p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                        </span>
                        <span class="sr-only">Elimina contenuto</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="news-separatore-container">
        <div class="news separatore mt-5">
            <div class="row px-3 py-2 align-items-center mt-4">
                <div class="text-left text-md-center col-3 col-xs-2 col-lg-1">
                    <svg class="icon icon-black">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_space_bar"></use>
                    </svg>
                </div>
                <div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">Separatore</div>
                <div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">Dalla rete</div>
                <div class="col-xs-2 col-lg-1 col-4 ml-auto d-flex justify-content-center justify-content-md-around">
                    <div class="arrow d-flex">
                        <a class="btn btn-xs btn-icon mr-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta separatore dalla rete in su">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
                            </svg>
                        </a>
                        <a class="btn btn-xs btn-icon ml-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta separatore dalla rete in giù">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Modifica separatore">
                        <span class="rounded-icon rounded-secondary p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                        </span>
                        <span class="sr-only">Modifica separatore</span>
                    </a>
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Elimina separatore">
                        <span class="rounded-icon rounded-danger p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                        </span>
                        <span class="sr-only">Elimina separatore</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="news-contenuto-esterno-container">
        <div class="news contenuto-esterno">
            <div class="row px-3 py-2 align-items-center mt-2">
                <div class="text-left text-md-center col-3 col-xs-2 col-lg-1">
                    <svg class="icon icon-black">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_rss_feed"></use>
                    </svg>
                </div>
                <div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">Cruscotto lavoro</div>
                <div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">SMART WORKING: UNA SCELTA PER IL FUTURO</div>
                <div class="col-xs-1 col-4 ml-auto d-flex justify-content-start justify-content-md-center">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Attiva contenuto in evidenza">
                        <svg class="icon star-icon disabled">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
                        </svg>
                    </a>
                </div>
                <div class="col-xs-2 col-lg-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <div class="arrow d-flex">
                        <a class="btn btn-xs btn-icon mr-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta smart working: una scelta per il futuro in su">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
                            </svg>
                        </a>
                        <a class="btn btn-xs btn-icon ml-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta smart working: una scelta per il futuro in giù">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Modifica contenuto esterno">
                        <span class="rounded-icon rounded-secondary p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                        </span>
                        <span class="sr-only">Modifica contenuto esterno</span>
                    </a>
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Elimina contenuto esterno">
                        <span class="rounded-icon rounded-danger p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                        </span>
                        <span class="sr-only">Elimina contenuto esterno</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="news contenuto-esterno">
            <div class="row px-3 py-2 align-items-center mt-2">
                <div class="text-left text-md-center col-3 col-xs-2 col-lg-1">
                    <svg class="icon icon-black">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_rss_feed"></use>
                    </svg>
                </div>
                <div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">Bandi on line</div>
                <div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">Uovo e gallina</div>
                <div class="col-xs-1 col-4 ml-auto d-flex justify-content-start justify-content-md-center">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Attiva contenuto in evidenza">
                        <svg class="icon star-icon disabled">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
                        </svg>
                    </a>
                </div>
                <div class="col-xs-2 col-lg-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <div class="arrow d-flex">
                        <a class="btn btn-xs btn-icon mr-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta uovo e gallina su">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
                            </svg>
                        </a>
                        <a class="btn btn-xs btn-icon ml-auto p-1" href="#" title="" data-toggle="tooltip" data-original-title="Sposta uovo e gallina giù">
                            <svg class="icon arrow-icon icon-black">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Modifica contenuto esterno">
                        <span class="rounded-icon rounded-secondary p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use></svg>
                        </span>
                        <span class="sr-only">Modifica contenuto esterno</span>
                    </a>
                    <a class="btn btn-xs btn-icon p-1" href="#" title="" data-toggle="tooltip" data-original-title="Elimina contenuto esterno">
                        <span class="rounded-icon rounded-danger p-1">
                            <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use></svg>
                        </span>
                        <span class="sr-only">Elimina contenuto esterno</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="py-2 d-flex flex-wrap flex-column flex-md-row align-items-start mt-2">
        <button class="btn btn-xs btn-news-yellow btn-icon mr-md-4 mb-3 mb-md-0 ">
            <svg class="icon icon-white">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_add_box"></use>
            </svg>
            <span class="text-uppercase text-white">Inserisci nuovo contenuto</span>
        </button>
        <button class="btn btn-xs btn-news-pink btn-icon mr-md-4 mb-3 mb-md-0 ">
            <svg class="icon icon-white">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_rss_feed"></use>
            </svg>
            <span class="text-uppercase text-white">Inserisci contenuto esterno</span>
        </button>
        <button class="btn btn-xs btn-news-gray btn-icon">
            <svg class="icon icon-white">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_space_bar"></use>
            </svg>
            <span class="text-uppercase text-white">Inserisci separatore</span>
        </button>
    </div>
    
</div>

<div class="my-5 w-100"></div>

<div class="invio-monitoraggio">
    <h5 class="primary-color font-weight-bold">Campagna test</h5>
    <div class="row variable-gutters flex-wrap">
        <div class="col-md-6 px-3 mb-4 mb-md-0">
            <div class="neutral-1-bg-a1 h-100 w-100 px-3">
                <div class="d-flex flex-wrap py-4 align-items-center">
                    <div class="d-flex mr-auto pr-2">
                        <svg class="icon mr-2">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_email"></use>
                        </svg>
                        <p class="h5 font-weight-bold mb-0">Newsletter</p>
                    </div>
                    
                    <div class="progress-bar-container">
                        <div class="progress-bar-label text-left">
                            <span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 col-xs-4">
                        <p class="text-muted text-uppercase font-weight-bold">Destinatari</p>
                    </div>
                    <div class="col-7 col-xs-8">
                        <p class="font-weight-bold">1270</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 col-xs-4">
                        <p class="text-muted text-uppercase font-weight-bold">Data inizio</p>
                    </div>
                    <div class="col-7 col-xs-8">
                        <p>13/03/2020 ore 13.34</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 col-xs-4">
                        <p class="text-muted text-uppercase font-weight-bold">Data fine</p>
                    </div>
                    <div class="col-7 col-xs-8">
                        <p>-</p>
                    </div>
                </div>
                <hr class="w-100 border-black">
                <div class="d-flex align-items-center mb-4">
                    <svg class="icon mr-2">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_equalizer"></use>
                    </svg>
                    <p class="text-uppercase font-weight-bold mb-0">Monitoraggio Newsletter</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="text-left">email inviate</p>
                    </div>
                    <div class="col-6">
                        <p class="text-left text-sm-right">1034 su 1270 <strong>(75%)</strong></p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="text-left">email consegnate</p>
                    </div>
                    <div class="col-6">
                        <p class="text-left text-sm-right">922 su 1270 <strong>(85%)</strong></p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="text-left">email aperte</p>
                    </div>
                    <div class="col-6">
                        <p class="text-left text-sm-right">422 su 1270 <strong>(35%)</strong></p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="text-left">email con almeno 1 click</p>
                    </div>
                    <div class="col-6">
                        <p class="text-left text-sm-right">44 su 1270 <strong>(5%)</strong></p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <p class="text-left">email rimbalzate</p>
                    </div>
                    <div class="col-6">
                        <p class="text-left text-sm-right">5 su 1270 <strong>(0,4%)</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 px-3 mb-4 mb-md-0">
            <div class="neutral-1-bg-a1 h-100 w-100 px-3">
                <div class="d-flex flex-wrap py-4 align-items-center">
                    <div class="d-flex mr-auto pr-2">
                        <svg class="icon mr-2">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_chat"></use>
                        </svg>
                        <p class="h5 font-weight-bold mb-0">SMS</p>
                    </div>
                    <a class="btn btn-sm btn-primary py-md-1 px-md-4" href="#">Invia</a>
                </div>
                <div class="row">
                    <div class="col-5 col-xs-4">
                        <p class="text-muted text-uppercase font-weight-bold">Destinatari</p>
                    </div>
                    <div class="col-7 col-xs-8">
                        <p class="font-weight-bold">272</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 col-xs-4">
                        <p class="text-muted text-uppercase font-weight-bold">Data inizio</p>
                    </div>
                    <div class="col-7 col-xs-8">
                        <p>-</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 col-xs-4">
                        <p class="text-muted text-uppercase font-weight-bold">Data fine</p>
                    </div>
                    <div class="col-7 col-xs-8">
                        <p>-</p>
                    </div>
                </div>
                <hr class="w-100 border-black">
                <div class="d-flex align-items-center mb-4">
                    <svg class="icon mr-2">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_equalizer"></use>
                    </svg>
                    <p class="text-uppercase font-weight-bold mb-0">Monitoraggio sms</p>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-6">
                        <p class="text-left">sms inviati</p>
                    </div>
                    <div class="col-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-5 w-100"></div>

<div class="tematiche-container">
    <div class="col-md-8 offset-md-4">
        <div class="d-flex align-items-center mb-4">
            <svg class="icon icon-lg mr-2">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_person"></use>
            </svg>
            <p class="font-weight-bold mb-0">Target: <span class="text-uppercase">cittadino</span></p>
        </div>
        <div class="tematiche">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted font-weight-bold text-uppercase">tematiche</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">email</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">sms</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">tel</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Innovazione</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Innovazione',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email1" type="checkbox">
                            <label class ="form-check-label" for="email1"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms1" type="checkbox">
                            <label class ="form-check-label" for="sms1"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono1" type="checkbox">
                            <label class ="form-check-label" for="telefono1"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Ambiente e sviluppo sostenibile</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Ambiente e sviluppo sostenibile',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email2" type="checkbox">
                            <label class ="form-check-label" for="email2"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms2" type="checkbox">
                            <label class ="form-check-label" for="sms2"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono2" type="checkbox">
                            <label class ="form-check-label" for="telefono2"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Cultura, tempo libero e turismo</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Cultura, tempo libero e turismo',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email3" type="checkbox">
                            <label class ="form-check-label" for="email3"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms3" type="checkbox">
                            <label class ="form-check-label" for="sms3"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono3" type="checkbox">
                            <label class ="form-check-label" for="telefono3"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Mobilità, trasporti e blocchi del traffico</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Mobilità, trasporti e blocchi del traffico',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email4" type="checkbox">
                            <label class ="form-check-label" for="email4"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms4" type="checkbox">
                            <label class ="form-check-label" for="sms4"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono4" type="checkbox">
                            <label class ="form-check-label" for="telefono4"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Informazioni istituzionali</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Informazioni istituzionali',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email5" type="checkbox">
                            <label class ="form-check-label" for="email5"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms5" type="checkbox">
                            <label class ="form-check-label" for="sms5"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono5" type="checkbox">
                            <label class ="form-check-label" for="telefono5"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Istruzione, formazione e lavoro</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Istruzione, formazione e lavoro',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email6" type="checkbox">
                            <label class ="form-check-label" for="email6"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms6" type="checkbox">
                            <label class ="form-check-label" for="sms6"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono6" type="checkbox">
                            <label class ="form-check-label" for="telefono6"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Bandi, contributi e concorsi</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Bandi, contributi e concorsi',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email7" type="checkbox">
                            <label class ="form-check-label" for="email7"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms7" type="checkbox">
                            <label class ="form-check-label" for="sms7"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono7" type="checkbox">
                            <label class ="form-check-label" for="telefono7"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Allerta e info di pubblica utilità</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Allerta e info di pubblica utilità',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email8" type="checkbox">
                            <label class ="form-check-label" for="email8"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms8" type="checkbox">
                            <label class ="form-check-label" for="sms8"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono8" type="checkbox">
                            <label class ="form-check-label" for="telefono8"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Salute e prevenzione</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Salute e prevenzione',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email9" type="checkbox">
                            <label class ="form-check-label" for="email9"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms9" type="checkbox">
                            <label class ="form-check-label" for="sms9"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono9" type="checkbox">
                            <label class ="form-check-label" for="telefono9"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Sport</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Sport',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email10" type="checkbox">
                            <label class ="form-check-label" for="email10"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms10" type="checkbox">
                            <label class ="form-check-label" for="sms10"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono10" type="checkbox">
                            <label class ="form-check-label" for="telefono10"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Agricoltura e alimentazione</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Agricoltura e alimentazione',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email11" type="checkbox">
                            <label class ="form-check-label" for="email11"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms11" type="checkbox">
                            <label class ="form-check-label" for="sms11"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono11" type="checkbox">
                            <label class ="form-check-label" for="telefono11"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Sociale</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Sociale',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email12" type="checkbox">
                            <label class ="form-check-label" for="email12"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms12" type="checkbox">
                            <label class ="form-check-label" for="sms12"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono12" type="checkbox">
                            <label class ="form-check-label" for="telefono12"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="dashed my-5">
    <div class="col-md-8 offset-md-4">
        <div class="d-flex align-items-center mb-4">
            <svg class="icon icon-lg mr-2">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_business"></use>
            </svg>
            <p class="font-weight-bold mb-0">Target: <span class="text-uppercase">azienda</span></p>
        </div>
        <div class="tematiche">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted font-weight-bold text-uppercase">tematiche</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">email</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">sms</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">tel</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Innovazione</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Innovazione',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email13" type="checkbox">
                            <label class ="form-check-label" for="email13"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms13" type="checkbox">
                            <label class ="form-check-label" for="sms13"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono13" type="checkbox">
                            <label class ="form-check-label" for="telefono13"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Ambiente e sviluppo sostenibile</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Ambiente e sviluppo sostenibile',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email14" type="checkbox">
                            <label class ="form-check-label" for="email14"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms14" type="checkbox">
                            <label class ="form-check-label" for="sms14"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono14" type="checkbox">
                            <label class ="form-check-label" for="telefono14"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Cultura, tempo libero e turismo</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Cultura, tempo libero e turismo',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email15" type="checkbox">
                            <label class ="form-check-label" for="email15"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms15" type="checkbox">
                            <label class ="form-check-label" for="sms15"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono15" type="checkbox">
                            <label class ="form-check-label" for="telefono15"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Mobilità, trasporti e blocchi del traffico</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Mobilità, trasporti e blocchi del traffico',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email16" type="checkbox">
                            <label class ="form-check-label" for="email16"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms16" type="checkbox">
                            <label class ="form-check-label" for="sms16"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono16" type="checkbox">
                            <label class ="form-check-label" for="telefono16"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Informazioni istituzionali</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Informazioni istituzionali',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email17" type="checkbox">
                            <label class ="form-check-label" for="email17"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms17" type="checkbox">
                            <label class ="form-check-label" for="sms17"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono17" type="checkbox">
                            <label class ="form-check-label" for="telefono17"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Istruzione, formazione e lavoro</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Istruzione, formazione e lavoro',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email18" type="checkbox">
                            <label class ="form-check-label" for="email18"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms18" type="checkbox">
                            <label class ="form-check-label" for="sms18"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono18" type="checkbox">
                            <label class ="form-check-label" for="telefono18"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Bandi, contributi e concorsi</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Bandi, contributi e concorsi',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email19" type="checkbox">
                            <label class ="form-check-label" for="email19"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms19" type="checkbox">
                            <label class ="form-check-label" for="sms19"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono19" type="checkbox">
                            <label class ="form-check-label" for="telefono19"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Allerta e info di pubblica utilità</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Allerta e info di pubblica utilità',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email20" type="checkbox">
                            <label class ="form-check-label" for="email20"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms20" type="checkbox">
                            <label class ="form-check-label" for="sms20"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono20" type="checkbox">
                            <label class ="form-check-label" for="telefono20"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Commercio</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Commercio',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email21" type="checkbox">
                            <label class ="form-check-label" for="email21"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms21" type="checkbox">
                            <label class ="form-check-label" for="sms21"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono21" type="checkbox">
                            <label class ="form-check-label" for="telefono21"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Agricoltura</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Agricoltura',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email22" type="checkbox">
                            <label class ="form-check-label" for="email22"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms22" type="checkbox">
                            <label class ="form-check-label" for="sms22"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono22" type="checkbox">
                            <label class ="form-check-label" for="telefono22"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="dashed my-5">
    <div class="col-md-8 offset-md-4">
        <div class="d-flex align-items-center mb-4">
            <svg class="icon icon-lg mr-2">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_assignment_ind"></use>
            </svg>
            <p class="font-weight-bold mb-0">Target: <span class="text-uppercase">ente ed operatore</span></p>
        </div>
        <div class="tematiche">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted font-weight-bold text-uppercase">tematiche</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">email</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">sms</p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase">tel</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Innovazione</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Innovazione',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email23" type="checkbox">
                            <label class ="form-check-label" for="email23"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms23" type="checkbox">
                            <label class ="form-check-label" for="sms23"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono23" type="checkbox">
                            <label class ="form-check-label" for="telefono23"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Ambiente e sviluppo sostenibile</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Ambiente e sviluppo sostenibile',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email24" type="checkbox">
                            <label class ="form-check-label" for="email24"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms24" type="checkbox">
                            <label class ="form-check-label" for="sms24"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono24" type="checkbox">
                            <label class ="form-check-label" for="telefono24"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Cultura, tempo libero e turismo</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Cultura, tempo libero e turismo',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email25" type="checkbox">
                            <label class ="form-check-label" for="email25"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms25" type="checkbox">
                            <label class ="form-check-label" for="sms25"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono25" type="checkbox">
                            <label class ="form-check-label" for="telefono25"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Mobilità, trasporti e blocchi del traffico</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Mobilità, trasporti e blocchi del traffico',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email26" type="checkbox">
                            <label class ="form-check-label" for="email26"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms26" type="checkbox">
                            <label class ="form-check-label" for="sms26"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono26" type="checkbox">
                            <label class ="form-check-label" for="telefono26"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Informazioni istituzionali</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Informazioni istituzionali',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email27" type="checkbox">
                            <label class ="form-check-label" for="email27"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms27" type="checkbox">
                            <label class ="form-check-label" for="sms27"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono27" type="checkbox">
                            <label class ="form-check-label" for="telefono27"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Istruzione, formazione e lavoro</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Istruzione, formazione e lavoro',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email28" type="checkbox">
                            <label class ="form-check-label" for="email28"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms28" type="checkbox">
                            <label class ="form-check-label" for="sms28"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono28" type="checkbox">
                            <label class ="form-check-label" for="telefono28"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Bandi, contributi e concorsi</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Bandi, contributi e concorsi',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email29" type="checkbox">
                            <label class ="form-check-label" for="email29"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms29" type="checkbox">
                            <label class ="form-check-label" for="sms29"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono29" type="checkbox">
                            <label class ="form-check-label" for="telefono29"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Allerta e info di pubblica utilità</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Allerta e info di pubblica utilità',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email30" type="checkbox">
                            <label class ="form-check-label" for="email30"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms30" type="checkbox">
                            <label class ="form-check-label" for="sms30"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono30" type="checkbox">
                            <label class ="form-check-label" for="telefono30"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Sport</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Sport',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email31" type="checkbox">
                            <label class ="form-check-label" for="email31"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms31" type="checkbox">
                            <label class ="form-check-label" for="sms31"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono31" type="checkbox">
                            <label class ="form-check-label" for="telefono31"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Sociale</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Sociale',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email32" type="checkbox">
                            <label class ="form-check-label" for="email32"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms32" type="checkbox">
                            <label class ="form-check-label" for="sms32"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono32" type="checkbox">
                            <label class ="form-check-label" for="telefono32"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Sicurezza</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Sicurezza',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email33" type="checkbox">
                            <label class ="form-check-label" for="email33"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms33" type="checkbox">
                            <label class ="form-check-label" for="sms33"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono33" type="checkbox">
                            <label class ="form-check-label" for="telefono33"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-check-group d-flex">
                        <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold">Territorio</label>
                        <?php
                            echo SwitchInput::widget([
                                'name' => 'Territorio',
                                'pluginOptions' => ['size' => 'mini'],
                            ]); 
                        ?>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="email34" type="checkbox">
                            <label class ="form-check-label" for="email34"><span class="label-text text-muted small">Email</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="sms34" type="checkbox">
                            <label class ="form-check-label" for="sms34"><span class="label-text text-muted small">SMS</span></label>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="form-group">
                        <div class="form-check text-md-center">
                            <input class ="form-check-input" id="telefono34" type="checkbox">
                            <label class ="form-check-label" for="telefono34"><span class="label-text text-muted small">Tel</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <button id="SalvaS" type="button" class="mt-4 btn btn-primary btn-lg float-right">SALVA</button>
        </div>
    </div>

</div>