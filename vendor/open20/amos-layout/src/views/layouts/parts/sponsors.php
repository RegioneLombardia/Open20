<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

use open20\amos\layout\assets\BaseAsset;

$asset = BaseAsset::register($this);
?>
<?php if ((isset(Yii::$app->params['footerSponsors'])) && (Yii::$app->params['footerSponsors'])): ?>
    <div class="footer-sponsor-container">
        <div class="footer-sponsors col-xs-12">
            <div class="sponsor">
                <img src="<?= $asset->baseUrl ?>/img/sponsors/unione-eu.jpg">
            </div>
            <div class="sponsor">
                <img src="<?= $asset->baseUrl ?>/img/sponsors/logo-rep.jpg">
            </div>
            <div class="sponsor">
                <img src="<?= $asset->baseUrl ?>/img/sponsors/logo-regione.jpg">
            </div>
            <div class="sponsor">
                <img src="<?= $asset->baseUrl ?>/img/sponsors/logo-fesr.jpg">
            </div>
        </div>
    </div>
<?php endif; ?>