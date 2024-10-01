<?php

/** do not delete asset */

/**
 * @var $this \yii\web\View
 */

use open20\design\assets\BootstrapItaliaDesignAsset;

// use open20\design\assets\CmsDashboardRedattoreDesignAsset;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
// $cmsDashboardRedattoreDesignAsset = CmsDashboardRedattoreDesignAsset::register($this);
$isLuyaApplication = \Yii::$app instanceof  luya\web\Application;
$title = (($isLuyaApplication && Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection'] : $this->title;

?>

<!-- usata per CMS -->
<?php $customBehavior ?>
<?php $isAngularCmsBackend = $angularCmsBackend; ?>
<?php $hideBtnModifyCmsPage = $hideBtnModifyCmsPage; ?>
<?php $isGuest = \Yii::$app->user->isGuest; ?>


<?php $this->beginPage() ?>
<!DOCTYPE html>

<?php if ($isAngularCmsBackend) : ?>
    <html ng-app="zaa" ng-controller="LayoutMenuController" ng-strict-di>
<?php else : ?>
    <html lang="<?= Yii::$app->language ?>">
<?php endif; ?>


<head>
    <?php if ((isset(\Yii::$app->params['layoutConfigurations']['customPlatformHead']))) : ?>
        <?php
        $customPlatformHead = \Yii::$app->params['layoutConfigurations']['customPlatformHead'];
        echo $this->render($customPlatformHead, [
            'currentAsset' => $currentAsset,
        ]);
        ?>
    <?php else : ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-head", [
            'currentAsset' => $currentAsset,
            'title' => (($isLuyaApplication && Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection'] : $this->title

        ]); ?>
    <?php endif ?>
</head>

<?php if ($isAngularCmsBackend) : ?>

    <body ng-cloak flow-prevent-drop class="cms-admin-layout-dxp {{browser}}" ng-class="{'debugToolbarOpen': showDebugBar, 'modal-open' : !AdminClassService.modalStackIsEmpty()}">

        <?php if (class_exists('frontend\modules\cookies\widgets\BannerCookieWidget')) :
            echo \frontend\modules\cookies\widgets\BannerCookieWidget::widget();
        endif; ?>

    <?php else : ?>

        <body class="bg-body position-relative d-flex flex-column">

            <?php if (class_exists('frontend\modules\cookies\widgets\BannerCookieWidget')) :
                echo \frontend\modules\cookies\widgets\BannerCookieWidget::widget();
            endif; ?>

        <?php endif; ?>

        <?php $this->beginBody() ?>
        <?php if (!$hideCookieBarCheck) : ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-cookiebar", [
                'currentAsset' => $currentAsset,
                'cookiePolicyLink' => \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon']
            ]); ?>
        <?php endif ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-skiplink", [
            'currentAsset' => $currentAsset,
        ]); ?>

        <?php
        if (!(\Yii::$app->params['layoutConfigurations']['hideCmsMenuPluginHeader'])) :
            $cmsDefaultMenu .= $cmsPluginMenu;
        endif;
        ?>

        <?php
        if (isset(\Yii::$app->view->params['hideHamburgerMenuHeader'])) {
            $hideHamburgerMenuHeaderCheck = (\Yii::$app->view->params['hideHamburgerMenuHeader']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['hideHamburgerMenuHeader'])) {
                $hideHamburgerMenuHeaderCheck = (\Yii::$app->params['layoutConfigurations']['hideHamburgerMenuHeader']);
            } else {
                $hideHamburgerMenuHeaderCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['hideGlobalSearchHeader'])) {
            $hideGlobalSearchHeaderCheck = (\Yii::$app->view->params['hideGlobalSearchHeader']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeader'])) {
                $hideGlobalSearchHeaderCheck = (\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeader']);
            } else {
                $hideGlobalSearchHeaderCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['hideGlobalSearchHeaderForGuest'])) {
            $hideGlobalSearchHeaderForGuestCheck = (\Yii::$app->view->params['hideGlobalSearchHeaderForGuest']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeaderForGuest'])) {
                $hideGlobalSearchHeaderForGuestCheck = (\Yii::$app->params['layoutConfigurations']['hideGlobalSearchHeaderForGuest']) && $isGuest;
            } else {
                $hideGlobalSearchHeaderForGuestCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['enableGlobalSearchForm'])) {
            $enableGlobalSearchFormCheck = (\Yii::$app->view->params['enableGlobalSearchForm']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['enableGlobalSearchForm'])) {
                $enableGlobalSearchFormCheck = (\Yii::$app->params['layoutConfigurations']['enableGlobalSearchForm']);
            } else {
                $enableGlobalSearchFormCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['hideUserMenuHeader'])) {
            $hideUserMenuHeaderCheck = (\Yii::$app->view->params['hideUserMenuHeader']);
        } else {
            $hideUserMenuHeaderCheck = (\Yii::$app->params['layoutConfigurations']['hideUserMenuHeader']);
            if (isset(\Yii::$app->params['layoutConfigurations']['hideUserMenuHeader'])) {
                $check = 0;
                if (\Yii::$app->user->can('ADMIN')) {
                    $check = 1;
                }
                if (!$check && isset(\Yii::$app->params['layoutConfigurations']['hideUserMenuHeaderPermissions'])) {
                    $permissions = \Yii::$app->params['layoutConfigurations']['hideUserMenuHeaderPermissions'];
                    foreach ($permissions as $pr) {
                        if (\Yii::$app->user->can($pr)) {
                            $check = 1;
                        }
                    }
                }
                if ($check == 1) {
                    $hideUserMenuHeaderCheck = false;
                }
            }
        }

        if (isset(\Yii::$app->view->params['fluidContainerHeader'])) {
            $fluidContainerHeaderCheck = (\Yii::$app->view->params['fluidContainerHeader']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['fluidContainerHeader'])) {
                $fluidContainerHeaderCheck = (\Yii::$app->params['layoutConfigurations']['fluidContainerHeader']);
            } else {
                $fluidContainerHeaderCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['hideCookieBar'])) {
            $hideCookieBarCheck = (\Yii::$app->view->params['hideCookieBar']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['hideCookieBar'])) {
                $hideCookieBarCheck = (\Yii::$app->params['layoutConfigurations']['hideCookieBar']);
            } else {
                $hideCookieBarCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['hideAssistance'])) {
            $hideAssistanceCheck = (\Yii::$app->view->params['hideAssistance']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['hideAssistance'])) {
                $hideAssistanceCheck = (\Yii::$app->params['layoutConfigurations']['hideAssistance']);
            } else {
                $hideAssistanceCheck = false;
            }
        }
        if (isset(\Yii::$app->view->params['customPlatformGuide'])) {
            $customPlatformGuideCheck = (\Yii::$app->view->params['customPlatformGuide']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['customPlatformGuide'])) {
                $customPlatformGuideCheck = (\Yii::$app->params['layoutConfigurations']['customPlatformGuide']);
            } else {
                $customPlatformGuideCheck = false;
            }
        }

        if (isset(\Yii::$app->view->params['hideTopHeaderForGuestUser'])) {
            $hideTopHeaderForGuestUserCheck = (\Yii::$app->view->params['hideTopHeaderForGuestUser']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser'])) {
                $hideTopHeaderForGuestUserCheck = (\Yii::$app->params['layoutConfigurations']['hideTopHeaderForGuestUser']);
            } else {
                $hideTopHeaderForGuestUserCheck = false;
            }
        }
        if ($hideTopHeaderForGuestUserCheck == true && $isGuest) {
            $hideTopHeaderForGuestUserCheck = true;
        } else {
            $hideTopHeaderForGuestUserCheck = false;
        }

        if (isset(\Yii::$app->view->params['alwaysHamburgerMenuRight'])) {
            $alwaysHamburgerMenuRightCheck = (\Yii::$app->view->params['alwaysHamburgerMenuRight']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['alwaysHamburgerMenuRight'])) {
                $alwaysHamburgerMenuRightCheck = (\Yii::$app->params['layoutConfigurations']['alwaysHamburgerMenuRight']);
            } else {
                $alwaysHamburgerMenuRightCheck = false;
            }
        }


        if (isset(\Yii::$app->view->params['showSocialHeader'])) {
            $showSocialHeaderCheck = (\Yii::$app->view->params['showSocialHeader']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['showSocialHeader'])) {
                $showSocialHeaderCheck = (\Yii::$app->params['layoutConfigurations']['showSocialHeader']);
            } else {
                $showSocialHeaderCheck = false;
            }
        }

        list($showSidebarRedattore, $openSidebarRedattoreOnLoadPage) = \open20\design\utility\DesignUtility::showSidebarRedattore($this);

        if (isset(\Yii::$app->view->params['showSocialFooter'])) {
            $showSocialFooterCheck = (\Yii::$app->view->params['showSocialFooter']);
        } else {
            if (isset(\Yii::$app->params['layoutConfigurations']['showSocialFooter'])) {
                $showSocialFooterCheck = (\Yii::$app->params['layoutConfigurations']['showSocialFooter']);
            } else {
                $showSocialFooterCheck = false;
            }
        }

        if (isset(\Yii::$app->params['layoutConfigurations']['customCopyleftFooter'])) {
            $customCopyleftFooter = (\Yii::$app->params['layoutConfigurations']['customCopyleftFooter']);
        } else {
            $customCopyleftFooter = 'Powered by Open 2.0';
        }

        ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-header", [
            'currentAsset' => $currentAsset,
            'cmsDefaultMenu' => $cmsDefaultMenu,
            'cmsSecondaryMenu' => $cmsSecondaryMenu,
            'privacyPolicyLink' => \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],
            'cookiePolicyLink' => \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon'],
            'hideHamburgerMenu' => $hideHamburgerMenuHeaderCheck,
            'alwaysHamburgerMenu' => \Yii::$app->params['layoutConfigurations']['showAlwaysHamburgerMenuHeader'],
            'hideLangSwitchMenu' => \Yii::$app->params['layoutConfigurations']['hideLangSwitchMenuHeader'],
            'hideGlobalSearch' => $hideGlobalSearchHeaderCheck,
            'hideGlobalSearchForGuest' => $hideGlobalSearchHeaderForGuestCheck,
            'enableGlobalSearchForm' => $enableGlobalSearchFormCheck,
            'hideUserMenu' => $hideUserMenuHeaderCheck,
            'fluidContainerHeader' => $fluidContainerHeaderCheck,
            'customUserMenu' => \Yii::$app->params['layoutConfigurations']['customUserMenuHeader'],
            'customUserNotLogged' => \Yii::$app->params['layoutConfigurations']['customUserNotLoggedHeader'],
            'customUserMenuLoginLink' => \Yii::$app->params['linkConfigurations']['loginLinkCommon'],
            'customUserMenuLogoutLink' => \Yii::$app->params['linkConfigurations']['logoutLinkCommon'],
            'customUserProfileLink' => \Yii::$app->params['linkConfigurations']['userProfileLinkCommon'],
            'customPlatformMenu' => \Yii::$app->params['menuCmsConfigurations']['customPlatformMenu'],
            'showSocial' => $showSocialHeaderCheck,
            'showSecondaryMenu' => \Yii::$app->params['layoutConfigurations']['showSecondaryMenuHeader'],
            'disableThemeLight' => \Yii::$app->params['layoutConfigurations']['disableThemeLightHeader'],
            'disableSmallHeader' => \Yii::$app->params['layoutConfigurations']['disableSmallHeader'],
            'enableHeaderSticky' => \Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader'],
            'pageSearchLink' => \Yii::$app->params['linkConfigurations']['pageSearchLinkCommon'],
            'hideTopHeaderForGuestUser' => $hideTopHeaderForGuestUserCheck,
            'alwaysHamburgerMenuRight' => $alwaysHamburgerMenuRightCheck,
        ]); ?>

        <div id="mainContent" class="d-flex <?= \Yii::$app->view->params['customClassMainContent'] ?>">

            <?php if ($showSidebarRedattore) : ?>
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-sidebar-redattore", [
                    'openOnLoadPage' => $openSidebarRedattoreOnLoadPage,
                    'hideHamburgerMenu' => $hideHamburgerMenuHeaderCheck,
                    'currentAsset' => $currentAsset
                ]); ?>
            <?php endif ?>
            <main role="main" class="w-100 bg-white <?= (\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']) ? 'mt-0' : '' ?> <?= ($showSidebarRedattore) ? 'main-with-sidebar-redattore' : '' ?>">

                <?php if (\Yii::$app->params['layoutConfigurations']['enableBtnModifyCmsPage'] && !$isAngularCmsBackend && !$hideBtnModifyCmsPage) : ?>
                    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-modify-cms-page", [
                        'currentAsset' => $currentAsset,
                        'currentPageUrl' => $currentPageUrl
                    ]); ?>
                <?php endif ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-favorites"); ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-messages", [
                    'currentAsset' => $currentAsset
                ]); ?>

                <?php if ((!(\Yii::$app->params['layoutConfigurations']['hideBreadcrumbs']) || (!\Yii::$app->view->params['hideBreadcrumbs']))) : ?>
                    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs", [
                        'currentAsset' => $currentAsset
                    ]); ?>
                <?php endif ?>

                <?php if (\Yii::$app->view->params['enablePluginHeader']) : ?>
                    <?= $this->render(
                        "parts" . DIRECTORY_SEPARATOR . "bi-plugin-header",
                        [
                            'title' => $this->titlePlugin,
                            'helpText' => $this->textPluginHeader,
                            'hideViewAll' => true
                        ]
                    ); ?>
                <?php endif ?>

                <?php if ((\Yii::$app->view->params['enablePluginToolbar'])) : ?>
                    <?= $this->render(
                        "parts" . DIRECTORY_SEPARATOR . "bi-plugin-toolbar"
                    ); ?>
                <?php endif ?>


                <?php if ($isAngularCmsBackend) : ?>
                    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "angular-cms" . DIRECTORY_SEPARATOR . "angular-cms-wrapper", [
                        'currentAsset' => $currentAsset
                    ]); ?>
                <?php endif ?>


                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->beginViewContent() ?>
                <?php endif; ?>
                <?= $content ?>
                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->endViewContent() ?>
                <?php endif; ?>
            </main>

        </div>

        <?php
        $this->trigger('beforeFooter');
        ?>


        <?php if ((isset(\Yii::$app->params['layoutConfigurations']['customPlatformFooter']))) : ?>
            <?php
            $customPlatformFooter = \Yii::$app->params['layoutConfigurations']['customPlatformFooter'];
            echo $this->render($customPlatformFooter, [
                'currentAsset' => $currentAsset,
                'cmsFooterMenu' => $cmsFooterMenu,
                'showSocial' => $showSocialFooterCheck,
                'customCopyleftFooter' => $customCopyleftFooter,
                'existSidebarRedattore' => $showSidebarRedattore
            ]);
            ?>
        <?php else : ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-footer", [
                'customCopyleftFooter' => $customCopyleftFooter,
                'currentAsset' => $currentAsset,
                'cmsFooterMenu' => $cmsFooterMenu,
                'showSocial' => $showSocialFooterCheck,
                'existSidebarRedattore' => $showSidebarRedattore
            ]); ?>
        <?php endif; ?>


        <?php
        // guida visibile solo per utenti loggati! logica ereditata da intranet ARIA
        // per altre siruazioni da personalizzare con un parametro
        if ($customPlatformGuideCheck && !\open20\amos\core\utilities\CurrentUser::isPlatformGuest()) : ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-guide", [
                'currentAsset' => $currentAsset,
                'pathView' => $customPlatformGuideCheck
            ]); ?>
        <?php endif; ?>

        <!-- ASSISTANCE -->
        <?php if (!$hideAssistanceCheck) : ?>
            <?php
            $isMail = ((isset(Yii::$app->params['assistance']['type']) && Yii::$app->params['assistance']['type'] == 'email') || (!isset(Yii::$app->params['assistance']['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
            $mailAddress = isset(Yii::$app->params['assistance']['email']) ? Yii::$app->params['assistance']['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');
            $urlAssistance = !empty(Yii::$app->params['assistance']['url']) ? \Yii::$app->params['platform']['frontendUrl'] . '/' . Yii::$app->params['assistance']['url'] : '#';
            ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-assistance", [
                'currentAsset' => $currentAsset,
                'isMail' => $isMail,
                'mailAddress' => $mailAddress,
                'urlAssistance' => $urlAssistance
            ]); ?>
        <?php endif ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-backtotop", [
            'currentAsset' => $currentAsset,
        ]); ?>


        <?php $this->endBody() ?>

        </body>

    </html>
    <?php $this->endPage() ?>