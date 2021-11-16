<?php

use open20\design\assets\AgidDesignAsset;

$agidAsset = AgidDesignAsset::register($this);
?>

<?php
echo $this->render('parts/devs-tools');
?>

<div class="container-fluid mt-5">

  <div class="row justify-content-between">
    <div id="jsMenu" class="col-lg-3" style="position: sticky; top: 6rem; height: calc(100vh - 6rem); overflow-y: auto;">
      <nav class="inline-menu">
        <div class="link-list-wrapper">
          <ul class="link-list">
           
            

            <!--<li><a class="list-item large medium right-icon" href="#collapseTwo" data-toggle="collapse" aria-expanded="false" aria-controls="collapseTwo"><span>Esempio roba annidata</span><svg class="icon icon-xs icon-primary right">
                  <use xlink:href="< ?= $agidAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                </svg></a>
              <ul class="link-sublist collapse" id="collapseTwo">

                <li><a class="list-item" href="#formExample"><span>Singoli componenti</span></a>
                </li>
                <li><a class="list-item" href="#vari-input"><span>Vari input</span></a>
                </li>
                <li><a class="list-item" href="#form-rounded"><span>Stile rounded</span></a>
                </li>
                <li><a class="list-item" href="#toggle"><span>Toggles</span></a>
                </li>
              </ul>
            </li>-->
            <li>
              <a class="list-item large medium right-icon" href="#carousel-novità"><span>Carousel Novità</span></a>
            </li>
           
            <li>
              <a class="list-item large medium right-icon" href="#argomenti-evidenza"><span>Argomenti in evidenza</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#siti-tematici"><span>Siti tematici</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#tag"><span>Tag</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-argomenti"><span>Card argomenti</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-generica"><span>Card generica</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#gallery-immagini"><span>Gallery immagini</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-contatti"><span>Contatti</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#timeline"><span>Timeline</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-informazioni"><span>Card Informazioni</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-dettaglio-argomenti"><span>Card Dettaglio Argomenti</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-costi"><span>Card Costi</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-luogo"><span>Card Luogo</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-documenti"><span>Card Documenti</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#link-condividi"><span>Link Condividi</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#link-azioni"><span>Link Azioni</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-search-result"><span>Card Risultati Ricerca</span></a>
            </li>

            <li>
              <a class="list-item large medium right-icon" href="#card-news"><span>Card News</span></a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <div id="jsMain" class="col-lg-9 px-5">

      <h1 class="no_toc">Componenti AGID</h1>
    <!--sezione carousel per novità-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="carousel-novità" class="pb-2">Carousel Novità</h2>
        </div>
        <div class=" py-5">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-carousel'
            );
            ?>
        </div>
    </section>

    <!--sezione argomenti in evidenza-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="argomenti-evidenza" class="pb-2">Argomenti in evidenza</h2>
        </div>
        <div class="py-5">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-evidence-topic',[
                  'additionalContainerClass' => 'row',
                  'columnWidth' => 'col-lg-4 col-md-6 col-sm-12',
                  'topicTitle' => 'COVID-19',
                  'topicTitleIcon' => 'virus',
                  'topicDescription' => 'Tutte le notizie, informazioni e provvedimenti riguardanti il coronavirus (Covid-19)',
                  'ctaLabel' => 'Esplora argomento'

              ]
            );
            ?>
        </div>
    </section>

    <!--sezione siti tematici-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="siti-tematici" class="pb-2">Siti tematici</h2>
        </div>

        <div class="py-5">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-theme-site',[
                  'additionalContainerClass' => 'row',
                  'columnWidth' => 'col-lg-4 col-md-6 col-sm-12',
                  'siteTitle' => 'COVID-19',
                  'siteImage' => 'https://picsum.photos/200/200',
                  'siteDescription' => 'Tutte le notizie, informazioni e provvedimenti riguardanti il coronavirus (Covid-19)',
                  'siteBg' => 'tertiary'

              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione tag-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="tag" class="pb-2">Tag</h2>
        </div>

        <div class="py-5">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-tag',[
                  'tagName' => 'Tag 1',
                  'tagColor' => 'chip-primary',
              ]
            );
            ?>

            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-tag',[
                  'tagName' => 'Tag 2',
                  'tagColor' => 'chip-secondary',
              ]
            );
            ?>

            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-tag',[
                  'tagName' => 'Tag 2',
                  'tagColor' => 'chip-tertiary',
              ]
            );
            ?>
        </div>
    </section>

    <!--sezione card argomenti (tutti argomenti)-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-argomenti" class="pb-2">Card Argomenti</h2>
        </div>

        <div class="py-5">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-topic',[
                  'additionalContainerClass' => 'row',
                  'columnWidth' => 'col-lg-4 col-md-6 col-sm-12',
                  'topicTitle' => 'Agevolazioni per la casa',
                  'topicLink' => '#',
                  'topicIcon' => 'help-circle-outline'
              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione card generica-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-generica" class="pb-2">Card generica</h2>
          <p>Usate nei dettagli argomenti per eventi, news, bandi..</p>
        </div>

        <div class="py-5 row">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-generic',[
                  'additionalContainerClass' => 'row',
                  'columnWidth' => 'col-lg-4 col-md-6 col-sm-12',
                  'cardCategory' => 'Servizio',
                  'cardTitle' => 'Esempio servizio',
                  'cardDescription' => 'Esempio di card usata per servizio in dettaglio argomento',
                  'cardIcon' => 'bank-outline',
              ]
            );
            ?>

            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-generic',[
                  'additionalContainerClass' => 'row',
                  'columnWidth' => 'col-lg-4 col-md-6 col-sm-12',
                  'cardCategory' => 'Documento',
                  'cardTitle' => 'Esempio documento',
                  'cardDescription' => 'Esempio di card usata per documento in dettaglio argomento',
                  'cardIcon' => 'file-document-outline',
              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione card notizie-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-generica" class="pb-2">Card notizie</h2>
          <p>Usate per news ed eventi</p>
        </div>

        <div class="py-5 row">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-novità',[
                'image' => 'https://picsum.photos/1600/900?random=4',
                'day' =>'01',
                'month' => '02',
                'year' => '2020',
                //'category' => 'categoria di prova',
                'title' => 'titolo della card',
                'showAvatar' => false,
                'hideCategory' => true,
              //'titleSize' => 'xl',
                'descriptionSize' => 'text-serif py-3 flex-grow-1',
                'description' =>  'descrizione',
                'additionalCssExternalClass' => 'd-flex',
                'widthColumn' => 'col-md-4 col-xs-12',
              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione contatti-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-contatti" class="pb-2">Card Contatti</h2>
        </div>

        <div class="py-5 row">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-contatti',[
                'cardTitle' => 'Arciconfraternita del Gonfalone sotto l’invocazione di Sant\'Efisio martire ',
                'cardSubtitle' => 'Via Sant\'Efisio, 14 - 09124',
                'cardContactTelephone' => '+39 070 668632',
                'cardContactSite' => 'www.festadisantelfisio.com',
                'cardContactEmail' => 'arcisantefisio@tiscali.it',
                'columnWidth' => 'col-lg-4 col-md-6 col-sm-12'
              ]
            );
            ?>

        </div>
    </section>


    <!--sezione timeline-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="timeline" class="pb-2">Timeline</h2>
        </div>

        <div class="py-5">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-timeline',[
                  'dataInizioGiorno' => '03',
                  'dataInizioMese' => 'Giu',
                  'dataFineGiorno' => '04',
                  'dataFineMese' => 'Lug',
                  'inizioLabel' => '09:00 Inizio evento',
                  'fineLabel' => '19:00 Fine evento',
              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione card informazioni-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="#card-informazioni" class="pb-2">Card Informazioni</h2>
        </div>

        <div class="py-5 row">
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-info',[
                  'cardInfoText' => 'Per ulteriori informazioni contattare santefisio@comune.cagliari.it',
                  'cardInfoCtaTitle' => 'Contatta l\'ufficio di Sant\'Efisio',
                  'cardInfoCtaText' => 'Ufficio di Sant\'Efisio',
                  'cardInfoCtaUrl' => '#',
                  'columnWidth' => 'col-lg-4 col-md-6 col-sm-12'

              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione costi-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-costi" class="pb-2">Card Costi</h2>
        </div>

        <div class="py-5">
            
              <?php
                echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-costi',[
                  
              ]
            );
            ?>

            
        </div>
    </section>

    <!--sezione card dettaglio argomenti-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-dettaglio-argomenti" class="pb-2">Card Dettaglio Argomenti</h2>
        </div>
        <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-topic-detail',[
                    'cardTitle' => 'Area Lavori pubblici ',
                    'cardSubtitle' => 'Via Roma, 0',
                    'cardImg' => 'https://picsum.photos/200/200',
                    'cardImgSize' =>'size-xl',
                    'cardLateralColor' => 'tertiary',
                    'columnWidth' => 'col-lg-4 col-md-6 col-sm-12'
              ] 
            );
        ?>
            </div>
    </section>


    <!--sezione card luogo-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-luogo" class="pb-2">Card Luogo</h2>
        </div>
        <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-place',[
                    'cardCity' => 'Cagliari',
                    'cardAdress' => 'Via Roma, 0',
                    'cardCAP' => '09124',
                    'moreDetailText' => 'Ulteriori dettagli',
                    'moreDetailUrl' =>'#',
                    'moreDetailTitle' => 'Ulteriori dettagli',
                    'columnWidth' => 'col-lg-4 col-md-6 col-sm-12'
              ] 
            );
        ?>
            </div>
    </section>

    <!--sezione card documenti-->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="card-documenti" class="pb-2">Card Documenti</h2>
        </div>
        <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-document',[
                    'documentTitle' => 'Locandina Sant\'Efisio 2019',
                    'documentUrl' => '#',
                    'documentUrlTitle' => 'Apri il documento',
                    'documentIconColor' => 'primary',
                    'columnWidth' => 'col-lg-4 col-md-6 col-sm-12'
              ] 
            );
        ?>
            </div>
    </section>


    <!--sezione share-link -->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="link-condividi" class="pb-2">Link Condividi</h2>
        </div>
        <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-share-link',[
                    
              ] 
            );
        ?>
            </div>
    </section>

    <!--sezione action-link -->
    <section class="py-5 border-bottom">
        <div>
          <h2 id="link-azioni" class="pb-2">Link Azioni</h2>
        </div>
        <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-action-link',[
                    
              ] 
            );
        ?>
            </div>
    </section>

        <!--sezione card-search-result -->
     <section class="py-5 border-bottom">
        <div>
          <h2 id="card-search-result" class="pb-2">Card Search Results</h2>
        </div>
        <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-card-search-result',[
                    
              ] 
            );
        ?>
            </div>
    </section>

    <!--sezione item-search-result -->
    <section class="py-5 border-bottom">
      <div>
        <h2 id="item-search-result" class="pb-2">Item Search Results</h2>
      </div>
      <div class="py-5 row">
        <?php
            echo $this->render(
              '@vendor/open20/design/src/components/agid/views/agid-item-search-result',[
                    
              ] 
            );
        ?>
      </div>
    </section>

    <!--sezione card-news -->
    <section class="py-5 border-bottom">
      <div>
        <h2 id="card-news" class="pb-2">Card News</h2>
      </div>
      <div class="py-5 row">
        <?php
        
        echo $this->render(
          '@vendor/open20/design/src/components/agid/views/agid-card-news',
        [
            'category' => 'ULTIME DALLA P.A.',
            'image' => 'https://picsum.photos/1600/900?random=2',
            'nameSurname' => 'Paolo Bianchi',
            
            'showAvatar' => true,
            'widthColumn' => 'col-lg-' . $widthColumn . ' col-md-6' . ' col-sm-12',
            //classi per la dimensione font/avatar
            'avatarWrapperSize' => 'sm',

            'descriptionSize' => 'card-text',
            'title' =>'Lorem ipsum dolor sit amet',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'date' => '2020-12-12'
        ]
        );
        ?>
      </div>
    </section>

    <!--sezione photogallery -->
    <section class="py-5 border-bottom">
      <div>
        <h2 id="card-news" class="pb-2">Photogallery</h2>
      </div>
      <div class="py-5 row">
        <?php
        
        echo $this->render(
          '@vendor/open20/design/src/components/agid/views/agid-photogallery',
          [
              'images'=>[
                '1'=>[
                  'imageSrc' =>'https://picsum.photos/1600/900?random=1',
                  'caption' =>'prova didascalia 1',
                  'width' =>'col-12 col-lg-4 col-md-6'
                ],
                '2'=>[
                  'imageSrc' =>'https://picsum.photos/1600/900?random=2',
                  'caption' =>'prova didascalia 2',
                  'width' =>'col-12 col-lg-4 col-md-6'
                ],
                '3'=>[
                  'imageSrc' =>'https://picsum.photos/1600/900?random=3',
                  'caption' =>'prova didascalia 3',
                  'width' =>'col-12 col-lg-4 col-md-6'
                ]

              ]
          ]
        );
        ?>
      </div>
    </section>

    <!--sezione videogallery -->
    <section class="py-5 border-bottom">
      <div>
        <h2 id="card-news" class="pb-2">Videogallery</h2>
      </div>
      <div class="py-5 ">
        <?php
        
        echo $this->render(
          '@vendor/open20/design/src/components/agid/views/agid-videogallery',
          [
              'videos'=>[
                '1'=>[
                  'videoSrc' =>'https://www.youtube.com/embed/RaHmGbBOP84',
                  'width' =>'col-12 col-lg-4 col-md-6'
                ],
                '2'=>[
                  'videoSrc' =>'https://www.youtube.com/embed/RaHmGbBOP84',
                  'width' =>'col-12 col-lg-4 col-md-6'
                ],
                '3'=>[
                  'videoSrc' =>'https://www.youtube.com/embed/RaHmGbBOP84',
                  'width' =>'col-12 col-lg-4 col-md-6'
                ]

              ]
          ]
        );
        ?>
      </div>
    </section>
    

    </div>
  </div>
</div>

