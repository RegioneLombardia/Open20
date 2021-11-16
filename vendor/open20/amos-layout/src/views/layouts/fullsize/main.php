<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\core\components\AmosView;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\layout\assets\BiLessAsset;
use app\components\CmsHelper;
use open20\amos\core\helpers\Html;


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

    <?php if (Yii::$app->get('menu', false)) { ?>
        <?php
        $iconSubmenu    = '<span class="am am-chevron-right am-4"> </span>';

        $mainMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['mainCmsMenu'] : 'default';
        $secondaryMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['secondaryCmsMenu'] : 'secondary';
        $footerMenu = (isset(\Yii::$app->params['menuCmsConfigurations']['footerCmsMenu'])) ? \Yii::$app->params['menuCmsConfigurations']['footerCmsMenu'] : 'footer';

        $cmsDefaultMenuCustomClass = 'cms-menu-container-default';
        $cmsSecondaryMenuCustomClass = 'cms-menu-container-secondary';
        $cmsFooterMenuCustomClass = 'cms-menu-container-footer';
        $cmsPluginMenuCustomClass = 'cms-menu-container-plugin';

        $cmsDefaultMenu = CmsHelper::BiHamburgerMenuRender(
            Yii::$app->menu->findAll([
                'depth' => 1,
                'container' => $mainMenu
            ]),
            $iconSubmenu,
            false
        );

        $cmsDefaultMenuFooter = CmsHelper::BiHamburgerMenuRender(
            Yii::$app->menu->findAll([
                'depth' => 1,
                'container' => $mainMenu
            ]),
            $iconSubmenu,
            true
        );

        $cmsFooterMenu  = CmsHelper::BiHamburgerMenuRender(
            Yii::$app->menu->findAll([
                'depth' => 1,
                'container' => $footerMenu
            ]),
            $iconSubmenu,
            true
        );

        if (!\Yii::$app->params['layoutConfigurations']['hideCmsMenuPluginHeader']) {
            $cmsPluginMenu = open20\amos\core\module\AmosModule::getModulesFrontEndMenus();
        }

        if (\Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader']) {
            $cmsSecondaryMenu = CmsHelper::BiHamburgerMenuRender(
                Yii::$app->menu->findAll([
                    'depth' => 1,
                    'container' => $secondaryMenu
                ]),
                $iconSubmenu,
                false
            );
        }

        $cmsDefaultMenu = Html::tag('ul', $cmsDefaultMenu, ['class' => 'navbar-nav' . ' ' . $cmsDefaultMenuCustomClass]);
        $cmsSecondaryMenu = Html::tag('ul', $cmsSecondaryMenu, ['class' => 'navbar-nav' . ' ' . $cmsSecondaryMenuCustomClass]);

        $cmsDefaultMenuFooter = Html::tag('ul', $cmsDefaultMenuFooter, ['class' => 'footer-list link-list' . ' ' . $cmsDefaultMenuCustomClass]);
        $cmsFooterMenu = Html::tag('ul', $cmsFooterMenu, ['class' => 'footer-list link-list' . ' ' . $cmsFooterMenuCustomClass]);
        $cmsFooterMenu = $cmsDefaultMenuFooter . $cmsFooterMenu;

        $cmsPluginMenu = Html::tag('ul', $cmsPluginMenu, ['class' => 'navbar-nav' . ' ' . $cmsPluginMenuCustomClass]);
        if ((!(\Yii::$app->params['layoutConfigurations']['hideCmsMenuPluginHeader'])) && (!\Yii::$app->params['layoutConfigurations']['customPlatformPluginMenu'])) :
            $cmsDefaultMenu .= $cmsPluginMenu;
        endif;
        ?>
        <?php
        $currentAsset = isset($currentAsset) ? $currentAsset : open20\amos\layout\assets\BiLessAsset::register($this);
        ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-less-header", [
            'currentAsset' => $currentAsset,
            'cmsDefaultMenu' => $cmsDefaultMenu,
            'cmsSecondaryMenu' => $cmsSecondaryMenu,
            'privacyPolicyLink' => \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],
            'cookiePolicyLink' => \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon'],
            'hideHamburgerMenu' => \Yii::$app->params['layoutConfigurations']['hideHamburgerMenuHeader'],
            'alwaysHamburgerMenu' => \Yii::$app->params['layoutConfigurations']['showAlwaysHamburgerMenuHeader'],
            'hideLangSwitchMenu' => \Yii::$app->params['layoutConfigurations']['hideLangSwitchMenuHeader'],
            'hideGlobalSearch' => \Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeader'],
            'hideUserMenu' => ((\Yii::$app->params['layoutConfigurations']['hideUserMenuHeader']) || (\Yii::$app->view->params['hideUserMenuHeader'])),
            'hideAssistance' => \Yii::$app->params['assistance']['hideAssistanceHeader'],
            'fluidContainerHeader' => ((\Yii::$app->params['layoutConfigurations']['fluidContainerHeader']) || (\Yii::$app->view->params['fluidContainerHeader'])),
            'customUserMenu' => \Yii::$app->params['layoutConfigurations']['customUserMenuHeader'],
            'customUserNotLogged' => \Yii::$app->params['layoutConfigurations']['customUserNotLoggedHeader'],
            'customUserMenuLoginLink' => \Yii::$app->params['linkConfigurations']['loginLinkCommon'],
            'userProfileLinkCommon' => \Yii::$app->params['linkConfigurations']['userProfileLinkCommon'],
            'customUserMenuLogoutLink' => \Yii::$app->params['linkConfigurations']['logoutLinkCommon'],
            'showSocial' => \Yii::$app->params['layoutConfigurations']['showSocialHeader'],
            'showSecondaryMenu' => \Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader'],
            'disableThemeLight' => \Yii::$app->params['layoutConfigurations']['disableThemeLightHeader'],
            'disableSmallHeader' => \Yii::$app->params['layoutConfigurations']['disableSmallHeader'],
            'enableHeaderSticky' => \Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader'],
            'pageSearchLink' => \Yii::$app->params['linkConfigurations']['pageSearchLinkCommon'],
        ]); ?>
        <!--< ?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>-->
    <?php } else { ?>
        <div id="headerFixed">
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "header"); ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>
        </div>
    <?php } ?>

    <?php if (isset(Yii::$app->params['logo-bordo'])) : ?>
        <div class="container-bordo-logo"><img src="<?= Yii::$app->params['logo-bordo'] ?>" alt=""></div>
    <?php endif; ?>

    <section id="bk-page" class="fullsizeMainLayout">

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div class="container <?= (!empty($this->params['containerFullWidth']) && $this->params['containerFullWidth'] == true) ? 'container-full-width' : '' ?>">

            <div class="page-content">

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                <?php if ((!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS)
                    && (!isset(\Yii::$app->params['disable_network_scope']) || \Yii::$app->params['disable_network_scope'] == false)
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
                    <?php if (!is_null($this->title)) : ?>
                        <h1 class="title"><?= \Yii::$app->formatter->asHtml($this->title) ?></h1>
                        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "textHelp"); ?>
                    <?php endif; ?>
                </div>

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
        <?php
        if ((isset(\Yii::$app->params['layoutConfigurations']['customPlatformFooter']))) {
            $customPlatformFooter = \Yii::$app->params['layoutConfigurations']['customPlatformFooter'];
            echo $this->render(
                $customPlatformFooter,
                [
                    'currentAsset' => $currentAsset,
                    'cmsFooterMenu' => $cmsFooterMenu
                ]
            );
        } else {
            echo $this->render("parts" . DIRECTORY_SEPARATOR . "bi-less-footer", [
                'currentAsset' => $currentAsset,
                'cmsFooterMenu' => $cmsFooterMenu,
                'showSocial' => \Yii::$app->params['layoutConfigurations']['showSocialFooter'],

            ]);
        }
        ?>
<?php if ((!isset(\Yii::$app->params['layoutConfigurations']['hideCookieBar'])) || (isset(\Yii::$app->params['layoutConfigurations']['hideCookieBar']) && !(\Yii::$app->params['layoutConfigurations']['hideCookieBar']))) : ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-less-cookiebar", [
                'currentAsset' => $currentAsset,
                'cookiePolicyLink' => \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon']
            ]); ?>
        <?php endif ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-backtotop-button"); ?>    <?php } else { ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "footer_text"); ?>
    <?php } ?>

    <?php /* echo $this->render("parts" . DIRECTORY_SEPARATOR . "assistance"); */ ?>




    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>