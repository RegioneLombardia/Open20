<?php
/* versione piattaforma */
$versione = Yii::t('amoscore', 'Versione') . ' ' . ((isset(\Yii::$app->params['versione'])) ? \Yii::$app->params['versione'] : '1.0');

use yii\helpers\Html;
?>

<footer id="footerContent" class="it-footer w-100 mt-auto z-index-1">
    <div class="it-footer-main py-4 bg-tertiary">
        <div class="container noprint">
            <div class="row">
                <div class="col-md-11">
                    <div class="noprint container-title">
                        <div role="contentinfo" aria-label="Titolo del footer" class="py-1 pt-3 py-md-4">
                            <p dir="ltr" class="equal-font-size-h5 footer-title"><strong><?= Html::encode(Yii::$app->name) ?></strong></p>
                        </div>
                    </div>
                    <div class="py-1 py-md-4">
                        <div class="row">
                            <?php if (isset($cmsFooterMenu)) { ?>
                                <div class="col-12">
                                    <div class="link-list-wrapper">
                                        <?= $cmsFooterMenu ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if ($showSocial) : ?>
                    <?php
                    echo $this->render("bi-footer-social", [
                        'currentAsset' => $currentAsset,
                    ]);
                    ?>
                <?php endif ?>

            </div>
            <div role="contentinfo" aria-label="Copyleft" class="py-1 py-md-4">
                <hr class="border-white">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="d-none" dir="ltr"><small>© Copyleft Regione Lombardia tutti i diritti Riservati CF 80050050154 - Piazza Città di Lombardia, 1 20124 Milano v.8.3.04-278156-06052019</small></p>
                        <p class="white-color" dir="ltr"><small>Powered by Open 2.0</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
if ($socialModule = \Yii::$app->getModule('social') && class_exists('\kartik\social\GoogleAnalytics')):
    if (YII_ENV_PROD && !empty($socialModule->googleAnalytics)):
        echo \kartik\social\GoogleAnalytics::widget([]);
    endif;
endif;
?>