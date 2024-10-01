<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\layout\assets\FormAsset;
use open20\amos\layout\assets\IEAssets;
use open20\amos\layout\assets\ExitFormWithoutSaveAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;
use app\components\CmsHelper;

//\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
/* @var $this \yii\web\View */
/* @var $content string */


$urlCorrente   = Url::current();
$arrayUrl      = explode('/', $urlCorrente);
$countArrayUrl = count($arrayUrl);
$percorso      = '';
$i             = 0;
$moduloId      = Yii::$app->controller->module->id;
$basePath      = Yii::$app->getBasePath();
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
$showSidebarForm = (isset($this->params['showSidebarForm'])) ? $this->params['showSidebarForm'] : false;

FormAsset::register($this);
if (isset(\Yii::$app->params['layoutConfigurations']['ExitFormWithoutSave'])) {
    ExitFormWithoutSaveAsset::register($this);
}
IEAssets::register($this);
?>
<?php $isLuyaApplication = \Yii::$app instanceof  luya\web\Application; ?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?=
    $this->render(
        "parts" . DIRECTORY_SEPARATOR . "head",
        [
            'title' => ((Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection']
                : $this->title
        ]
    );
    ?>
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
        <div id="headerFixed">
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "header"); ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>
        </div>
    <?php } ?>

    <?php if (isset(Yii::$app->params['logo-bordo'])) : /* && \Yii::$app->params['logo-bordo'] == TRUE) */ ?>
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

    <section id="bk-page" class="fullsizeFormLayout <?= ($showSidebarRedattore) ? 'main-with-sidebar-redattore' : 'main-with-sidebar-redattore' ?>" role="main">

        <?php if ($showSidebarRedattore) : ?>
            <?= $this->render("@vendor/open20/design/src/views/layouts/parts/bi-sidebar-redattore", [
                'currentAsset' => $currentAsset
            ]); ?>
        <?php endif ?>
        <div class="w-100 main-content">
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div id="record_form" class="container <?= (!empty($this->params['containerFullWidth']) && $this->params['containerFullWidth'] == true) ? 'container-full-width' : '' ?>">

            <div class="page-content">
                <div class="<?= ($showSidebarForm) ? 'layout-sidebarForm' : 'layout-standardForm' ?>">
                    <?php
                    if ($showSidebarForm) {
                        echo $this->render("parts" . DIRECTORY_SEPARATOR . "bi-less-sidebarForm", ['viewParams' => \Yii::$app->getView()->params['bi-menu-sidebar']]);
                    }
                    ?>

                    <div class="<?= ($showSidebarForm) ? 'main-with-sidebar flexbox flexbox-column' : 'w-100' ?>">
                        <?php if ($showSidebarForm) { ?>
                            <a class="btn-sidebar btn-lg" role="button" title="Visualizza sidebar" data-toggle="collapse" href="#sidebarForm" aria-expanded="false" aria-controls="sidebarForm">
                                <span class="mdi mdi-24px mdi-apps mdi-flip-h"></span>
                            </a>
                        <?php } ?>
                        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                        <?php if (
                            !empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine']
                            == WidgetAbstract::ENGINE_ROWS
                        ) : ?>

                            <?php
                            $isLayoutInScope = false;
                            $moduleCwh = \Yii::$app->getModule('cwh');
                            if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                                $scope = $moduleCwh->getCwhScope();
                                $isLayoutInScope = (!empty($scope)) ? true : false;
                            }
                            ?>

                            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope", ['isLayoutInScope' => $isLayoutInScope]); ?>
                        <?php endif; ?>

                        <div class="page-header">

                            <?php
                            if (is_array($this->params['titleButtons']) && !empty($this->params['titleButtons'])) {
                                echo "<div class='w-100 text-right m-b-20'>";
                                foreach ($this->params['titleButtons'] as $button) {
                                    echo $button;
                                }
                                echo "</div>";
                            }
                            ?>

                            <?php if (!is_null($this->title)) : ?>
                                <h1 class="title"><?= Html::encode($this->title) ?></h1>
                                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "textHelp"); ?>
                            <?php endif; ?>

                        </div>

                        <?= $content ?>
                    </div>
                </div>
            </div>
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
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "sponsors"); ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "footer_text"); ?>
    <?php } ?>

    <?php /* echo $this->render("parts" . DIRECTORY_SEPARATOR . "assistance"); */ ?>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>