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
use open20\amos\dashboard\models\AmosWidgets;
use yii\helpers\Url;
use app\components\CmsHelper;
use yii\helpers\Html;



////\bedezign\yii2\audit\web\JSLoggingAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */

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
    if (file_exists($basePath . '/' . $percorso . '/intro/' . $vista . '.php')) {
        $this->params['intro'] = [
            'filename' => $vista
        ];
    }
}
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?php $isLuyaApplication = \Yii::$app instanceof  luya\web\Application; ?>

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "head", [
        'title' => (($isLuyaApplication && Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection'] : $this->title
    ]); ?>
</head>

<body>

    <!-- add for fix error message Parametri mancanti -->
    <input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>" />

    <?php $this->beginBody() ?>
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-skiplink"); ?>

    <?php if ($isLuyaApplication && Yii::$app->isCmsApplication()) { ?>

        <?php
        $currentAsset = isset($currentAsset) ? $currentAsset : open20\amos\layout\assets\BiLessAsset::register($this);
        ?>
        <?= $this->render(
            "parts" . DIRECTORY_SEPARATOR . "bi-less-layout-header",
            [
                'currentAsset' => $currentAsset,
            ]
        ); ?>
        <!--< ?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>-->
    <?php } else { ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "header"); ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>
    <?php } ?>

    <?php if (isset(Yii::$app->params['logo-bordo'])) : ?>

        <div class="container-bordo-logo"><img src="<?= Yii::$app->params['logo-bordo'] ?>" alt=""></div>
    <?php endif; ?>
    <?php
        if(class_exists('\open20\design\utility\DesignUtility') && method_exists('\open20\design\utility\DesignUtility', 'showSidebarRedattore')) {
            list($showSidebarRedattore, $openSidebarRedattoreOnLoadPage) = \open20\design\utility\DesignUtility::showSidebarRedattore($this);
        } else {
            if (isset(\Yii::$app->view->params['showSidebarRedattore']) && !\Yii::$app->user->isGuest) {
                $showSidebarRedattore = (\Yii::$app->view->params['showSidebarRedattore']);
            } else {
                if (isset(\Yii::$app->params['layoutConfigurations']['showSidebarRedattore']) && !\Yii::$app->user->isGuest) {
                    $showSidebarRedattore = (\Yii::$app->params['layoutConfigurations']['showSidebarRedattore']);
                } else {
                    $showSidebarRedattore = false;
                }
            }
        }
    ?>
    <section id="bk-page" class="fullsizeNetworkLayout <?= ($showSidebarRedattore) ? 'main-with-sidebar-redattore' : 'main-with-sidebar-redattore' ?>" role="main">
        <?php if ($showSidebarRedattore) : ?>
            <?= $this->render("@vendor/open20/design/src/views/layouts/parts/bi-sidebar-redattore", [
                'currentAsset' => $currentAsset
            ]); ?>
        <?php endif ?>
        <div class="w-100 main-content">

            <div class="container">


                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>
            </div>
            <div class="dashboard-content">

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope"); ?>

                <!-- <div class="network-breadcrumb">
                < ?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>
            </div> -->

                <?php if ($this instanceof \open20\amos\core\components\AmosView) : ?>
                    <?php $this->beginViewContent() ?>
                <?php endif; ?>

                <?= $content ?>

                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->endViewContent() ?>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <?php if ($isLuyaApplication && Yii::$app->isCmsApplication()) { ?>
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