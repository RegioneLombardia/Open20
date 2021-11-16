<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    landing
 * @category   CategoryName
 *
 * @var $this \yii\web\View
 * @var $assetBundle \yii\web\AssetBundle
 *
 */

?>

<a class="gototop"><span class="sr-only">go to top</span><span class="glyphicon glyphicon-chevron-up"></span></a>

<footer class="footer">
    <!-- LOGHI -->
    <section class="pre-footer">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </section>
    <!-- END: LOGHI -->

    <div class="container-padding">
        <div class="container-fluid">
            <div class="row">
                <!--<div class="nome-applicativo"><strong></?= \Yii::t('app', 'API Lombardia'); ?></strong></div>-->
                <div class="modulo"><p>Modulo per la gestione di eventi e delle relative anagrafiche</p></div>
                <div class="menu-footer">
                    <ul>
                    <?php
                        $numItems = count(Yii::$app->menu->findAll(['depth' => 1, 'container' => 'footer']));
                        $i = 0;
                        ?>
                        <?php foreach (Yii::$app->menu->findAll(['depth' => 1, 'container' => 'footer']) as $item) : /* @var $item \luya\cms\menu\Item */ ?>                            
                            <li><a class="nav-link" href="<?= $item->link; ?>" title="<?= $item->title ?>"><?= $item->title; ?></a></li>

                        <?php endforeach; ?>
                    </ul>

                </div>
                <div class="copyleft">
                    <p> © <?= \Yii::t('app', 'Copyleft Regione Lombardia tutti i diritti riservati - 80050050154 - Piazza Città di Lombardia 1 - 20124 Milano
                '); ?>
                        

                    </p>

                </div>
            </div>

        </div>
    </div>
</footer>

<?php
$socialModule = \Yii::$app->getModule('social');
if (!empty($socialModule) && class_exists('\kartik\social\GoogleAnalytics')) :
    if (YII_ENV_PROD && !empty($socialModule->googleAnalytics)) :
        echo \kartik\social\GoogleAnalytics::widget([]);
    endif;
endif;
?>