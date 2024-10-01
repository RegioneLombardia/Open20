<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use yii\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use open20\design\utility\DesignUtility;
use open20\amos\community\AmosCommunity;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;
?>


<?php
$modelLabel = strtolower($model->getGrammar()->getModelLabel());
$hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
$hideModuleBackendIfEmptyList = (strpos($cssClass, 'hide-module-if-empty-list') !== false && $dataProvider->getTotalCount() <= 0) ? true : false;

$classModuloBackend = 'modulo-backend-' . $modelLabel . ' ' . $cssClass;
if ($hideModuleBackendIfEmptyList) {
    $classModuloBackend .= ' ' . 'd-none';
}

?>
<div class="<?= $classModuloBackend ?>">
    <?php if (!$hideBiPluginHeader) : ?>
        <?php
        if ($isGuest) {
            $titleSection = AmosCommunity::t('amoscommunity', 'Community');
            $urlLinkAll = '/community/community/index';
            $labelLinkAll = AmosCommunity::t('amoscommunity', 'Tutte le community');
            $titleLinkAll = AmosCommunity::t('amoscommunity', 'Visualizza la lista delle community');

            $labelSigninOrSignup = AmosCommunity::t('amoscommunity', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosCommunity::t(
                'amoscommunity',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosCommunity::t('amoscommunity', '#beforeActionCtaLogin');
            $titleSignin = AmosCommunity::t(
                'amoscommunity',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                AmosCommunity::t(
                    'amoscommunity',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosCommunity::t('amoscommunity', 'Le mie community');
            $urlLinkAll = '/community/community/my-communities';
            $labelLinkAll = AmosCommunity::t('amoscommunity', 'Tutte le community a cui partecipi');
            $titleLinkAll = AmosCommunity::t('amamoscommunityosapp', 'Visualizza la lista delle community a cui partecipi');

            $subTitleSection = Html::tag('p', AmosCommunity::t('amoscommunity', 'Cocreazione per la generazione di nuove idee: le community sono uno degli strumenti caratteristici di questa piattaforma. Attraverso gruppi di lavoro dedicati, le iniziative di {platformName} creano spazi di collaborazione, innovazione, condivisione e networking.', ['platformName' => \Yii::$app->name]));
        }

        $labelCreate = AmosCommunity::t('amoscommunity', 'Nuova');
        $titleCreate = AmosCommunity::t('amoscommunity', 'Crea una nuova community');
        $labelManage = AmosCommunity::t('amoscommunity', 'Gestisci');
        $titleManage = AmosCommunity::t('amoscommunity', 'Gestisci le community');
        $urlCreate = '/community/community/create';

        $manageLinks = [];
        $controller = \open20\amos\community\controllers\CommunityController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }

        $canCreate = \Yii::$app->user->can('COMMUNITY_CREATE', ['model' => $model]);

        ?>
        <?=
        $this->render(
            '@vendor/open20/design/src/views/layouts/parts/bi-plugin-header',
            [
                'isGuest' => $isGuest,
                'modelLabel' => $modelLabel,
                'titleSection' => $titleSection,
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'canCreate' => $canCreate,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
            ]
        );
        ?>
    <?php endif ?>

    <div class="card-<?= $modelLabel ?>-container">
        <?php
        if ($dataProvider->getTotalCount() > 0) {

            $listViewLayout = $this->render('@vendor/open20/design/src/components/yii2/views/listViewLayout');

            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemCardCommunityDesign',
                'viewParams' => [
                    'detailPage' => $detailPage,
                    'viewFields' => $viewFields,
                    'blockItemId' => $blockItemId,
                    'hideAllCtaGuest' => (strpos($cssClass, 'hide-all-cta-guest') !== false)
                ],
                'layout' => $listViewLayout,
                'summary' => DesignUtility::getLayoutSummary($withPagination),
                'pager' => DesignUtility::listViewPagerConfig(),
                'itemOptions' => [
                    'tag' => false
                ],
                'options' => [
                    'class' => 'list-view w-100'
                ]
            ]);
        } else { ?>
            <?php if (!$isGuest) : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosCommunity::t('amoscommunity', 'Non sei ancora iscritto a nessuna community!') ?></strong></p>
                        <?=
                        Html::a(
                            AmosCommunity::t('amoscommunity', 'Clicca qui'),
                            '/community/community/index',
                            [
                                'title' => AmosCommunity::t('amoscommunity', 'Clicca e scopri tutte le community di {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            AmosCommunity::t('amoscommunity', 'e scopri ora tutte le community di {platformName}', ['platformName' => \Yii::$app->name])
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosCommunity::t('amoscommunity', 'Non sono presenti community') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>