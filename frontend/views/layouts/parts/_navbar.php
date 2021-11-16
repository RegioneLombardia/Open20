<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    landing
 * @category   CategoryName
 *
 *
 * @var $this \yii\web\View
 * @var $assetBundle \yii\web\AssetBundle
 *
 */

use app\assets\ResourcesAsset;
use app\assets\BootstrapItaliaAsset;
use app\components\CmsHelper;
use luya\cms\menu\Item;
use luya\cms\widgets\LangSwitcher;

$resourceAsset = ResourcesAsset::register($this);
//$resourceBootstrapItalia = BootstrapItaliaAsset::register($this);
?>
<div class="topbar">
  <div class="container-padding">
    <div class="logo-bianco">
      <a href="https://www.regione.lombardia.it/" title="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
        <img src="<?= $assetBundle->baseUrl ?>/img/logo_regione_lombardia-bianco.png" alt="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
      </a>
    </div>
  </div>
</div>
<div class="header-center-wrapper">
  <div class="container-padding">
    <div>
      <div>
        <div class="header-center-content-wrapper">
          <!--LOGO-->
          <div class="newloghi">
            <a href="https://www.regione.lombardia.it/" class="logo logo-regione" title="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
              <img src="<?= $assetBundle->baseUrl ?>/img/logo_regione_lombardia.svg" alt="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
            </a>
           
          </div>
          <!--END: LOGO-->
         
        </div>
      </div>
    </div>
  </div>
</div>
