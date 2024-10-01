<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\core\components\AmosView;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;
use app\components\CmsHelper;


////\bedezign\yii2\audit\web\JSLoggingAsset::register($this);

/** @var $this \open20\amos\core\components\AmosView */
/** @var $content string */

$urlCorrente = Url::current();
$arrayUrl = explode('/', $urlCorrente);
$countArrayUrl = count($arrayUrl);
$percorso = '';
$i = 0;
$moduloId = Yii::$app->controller->module->id;
$basePath = Yii::$app->getBasePath();
if ($moduloId != 'app-backend' && $moduloId != 'app-frontend') {
    $basePath = \Yii::$app->getModule($moduloId)->getBasePath();
    $percorso .= '/modules/' . $moduloId . '/views/' . $arrayUrl[$countArrayUrl - 2];
} else {
    $percorso .= 'views';
    while ($i < ($countArrayUrl - 1)) {
        $percorso .= $arrayUrl[$i] . '/';
        $i++;
    }
}
if ($countArrayUrl) {
    $posizioneEsclusione = strpos($arrayUrl[$countArrayUrl - 1], '?');
    if ($posizioneEsclusione > 0) {
        $vista = substr($arrayUrl[$countArrayUrl - 1], 0, $posizioneEsclusione);
    } else {
        $vista = $arrayUrl[$countArrayUrl - 1];
    }
    if (file_exists($basePath . '/' . $percorso . '/help/' . $vista . '.php')) {
        $this->params['help'] = [
            'filename' => $vista
        ];
    }
}
?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "head", [
        'title' => ((Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection'] : $this->title
    ]); ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-skiplink"); ?> 

    <?php if (Yii::$app->isCmsApplication()) { ?>
        <?php
        $currentAsset = isset($currentAsset) ? $currentAsset : open20\amos\layout\assets\BiLessAsset::register($this);
        ?>
        <?= $this->render(
            "parts" . DIRECTORY_SEPARATOR . "bi-less-layout-header",
            [
                'currentAsset' => $currentAsset,
            ]
        ); ?>
    <?php } else { ?>
        <div id="headerFixed">
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "header"); ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>
        </div>
    <?php } ?>

    <?php if (isset(Yii::$app->params['logo-bordo'])) : ?>
        <div class="container-bordo-logo"><img src="<?= Yii::$app->params['logo-bordo'] ?>" alt=""></div>
    <?php endif; ?>

    <section id="bk-page" class="fullsizeMainOrganizzazioniLayout" role="main">

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div class="page-content">

            <div class="container <?= (!empty($this->params['containerFullWidth']) && $this->params['containerFullWidth'] == true) ? 'container-full-width' : '' ?>">

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope"); ?>
            </div>


            <div class="page-content mainOrganizzazioniLayout">

                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->beginViewContent() ?>
                <?php endif; ?>
                <?= $content ?>
                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->endViewContent() ?>
                <?php endif; ?>
            </div>
        </div>
    </section>


    <?php if (Yii::$app->isCmsApplication()) { ?>
        <?= $this->render(
            "parts" . DIRECTORY_SEPARATOR . "bi-less-layout-footer",
            [
                'currentAsset' => $currentAsset,
            ]
        ); ?>
       <?php
if (isset(\Yii::$app->view->params['hideCookieBar'])) {
        $hideCookieBarCheck = (\Yii::$app->view->params['hideCookieBar']);
    } else {
        if (isset(\Yii::$app->params['layoutConfigurations']['hideCookieBar'])) {
            $hideCookieBarCheck = (\Yii::$app->params['layoutConfigurations']['hideCookieBar']);
        } else {
            $hideCookieBarCheck = false;
        }
    }
?>
<?php if (!$hideCookieBarCheck) : ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-less-cookiebar", [
                'currentAsset' => $currentAsset,
                'cookiePolicyLink' => \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon']
            ]); ?>
        <?php endif ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-backtotop-button"); ?>
    <?php } else { ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "footer_text"); ?>
    <?php } ?>

    <?php /* echo $this->render("parts" . DIRECTORY_SEPARATOR . "assistance"); */ ?>


    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>