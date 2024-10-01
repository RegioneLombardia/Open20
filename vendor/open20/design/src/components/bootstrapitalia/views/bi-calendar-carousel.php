<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\web\View;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this); 

$filterList= (isset($filterList))? $filterList :  Module::t('amosdesign', 'Tutto');

$this->registerJs(
  <<<JS

$(".filter-container button").click(function(event) {
  var activeFilter=this.innerText;
  
  if(activeFilter.toUpperCase()=='tutto'.toUpperCase()){
    
    var divToDisplay=$(".event-calendar-text:not(activeFilter)");
    
    for(j=0; j<divToDisplay.length; j++){
      divToDisplay[j].style.display="inline-block";
    }
  }else{
    var divToDisplay=document.getElementsByClassName(activeFilter);
    
    var divToHide=$(".event-calendar-text:not(activeFilter)");
    for(i=0; i<divToHide.length; i++){
      divToHide[i].style.display="none";
    }
    for(j=0; j<divToDisplay.length; j++){
      divToDisplay[j].style.display="inline-block";
    }
  }
});

JS
,  View::POS_READY);


?>

<section class="calendar-section">
  <div class=" pb-5 pt-0">
    <div class="container">
      <div class="row row-title pb-3 justify-content-end">
        <div class="filter-container">
          <?php foreach ($filterList as $i): ?>
            <?php if($i=='Tutto'){ ?>
              <button id="<?= $i ?>" type="button" class="mt-1 btn btn-primary btn-sm"><?= $i ?></button>
            <?php } else { ?>
              <button id="<?= $i ?>" type="button" class="mt-1 btn btn-outline-secondary btn-sm"><?= $i ?></button>
            <?php } ?>
          <?php endforeach ; ?>          
        </div>
      </div>
      <div class="row row-calendar">
        <div class="it-carousel-wrapper it-carousel-landscape-abstract-three-cols it-calendar-wrapper">
          <div class="it-header-block">
            <div class="it-header-block-title">
              <h4 class="mb-0 text-center text-white"><?Module::t('amosdesign', 'Settembre'). " " . "2019"?></h4>
            </div>
          </div>
          <div class="it-carousel-all owl-carousel it-card-bg">
            <div class="it-single-slide-wrapper h-100 ">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom "><?Module::t('amosdesign', '15')?><span><?Module::t('amosdesign', 'lun')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Saldo TASI')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Eventi">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Concerto gratuito piazza XX Settembre')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Consigli-comunali">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Convocazione Consiglio Comunale - Prima seduta')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100 ">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom"><?Module::t('amosdesign', '16')?><span><?Module::t('amosdesign', 'mar')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Saldo TASI')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Eventi">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Concerto gratuito piazza XX Settembre')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom "><?Module::t('amosdesign', '17')?><span><?Module::t('amosdesign', 'mer')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Eventi">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Concerto gratuito piazza XX Settembre')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Consigli-comunali">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Convocazione Consiglio Comunale - Prima seduta')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100 ">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom"><?Module::t('amosdesign', '18')?><span><?Module::t('amosdesign', 'giov')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class="text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Saldo TASI')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Eventi">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Concerto gratuito piazza XX Settembre')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Consigli-comunali">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Convocazione Consiglio Comunale - Prima seduta')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom "><?Module::t('amosdesign', '19')?><span><?Module::t('amosdesign', 'sab')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Saldo TASI')?></a>
                    </div>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Eventi">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Concerto gratuito piazza XX Settembre')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Consigli-comunali">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Convocazione Consiglio Comunale - Prima seduta')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class="text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom "><?Module::t('amosdesign', '20')?><span><?Module::t('amosdesign', 'dom')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100 ">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom"><?Module::t('amosdesign', '21')?><span><?Module::t('amosdesign', 'lun')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Consigli-comunali">
                      <a href="#" class="text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Convocazione Consiglio Comunale - Prima seduta')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="it-single-slide-wrapper h-100">
              <div class="card-wrapper h-100">
                <div class="card card-bg">
                  <div class="card-body">
                    <h5 class="card-title my-0 border-bottom "><?Module::t('amosdesign', '22')?><span><?Module::t('amosdesign', 'mar')?></span></h5>
                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Saldo TASI')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Eventi">
                      <a href="#" class=" text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Concerto gratuito piazza XX Settembre')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Consigli-comunali">
                      <a href="#" class="text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Convocazione Consiglio Comunale - Prima seduta')?></a>
                    </div>

                    <div class="py-3 w-100 border-bottom  event-calendar-text Scadenze">
                      <a href="#" class="text-decoration-none small w-100 h-100 d-inline-block " ><?Module::t('amosdesign', 'Seconda rata TARI')?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>







        