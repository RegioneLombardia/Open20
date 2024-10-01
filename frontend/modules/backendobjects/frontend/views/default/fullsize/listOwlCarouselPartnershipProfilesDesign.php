<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use open20\design\utility\DesignIcon;
use open20\design\utility\DesignUtility;
use open20\amos\partnershipprofiles\Module;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;

?>
<?php if (!\Yii::$app->user->isGuest) { ?>

    <div class="modulo-backend-<?= $modelLabel ?> <?= $cssClass ?> py-5">
        <?php if (!($cssClass == 'hide-bi-plugin-header')) : ?>
            <?php
            $modelLabel = strtolower($model->getGrammar()->getModelLabel());

            if ($isGuest) {
                $titleSection = Module::t('amospartnershipprofiles', 'Proposte di collaborazione');
                $urlLinkAll = '/partnershipprofiles/partnership-profiles/index';
                $labelLinkAll = Module::t('amoamospartnershipprofilessapp', 'Tutte le proposte');
                $titleLinkAll = Module::t('amospartnershipprofiles', 'Visualizza la lista delle proposte');

                $subTitleSection = Html::tag('p', Module::t('amospartnershipprofiles', 'Innovazione, competitività e networking: la piattaforma Open Innovation offre ai suoi utenti l’opportunità di entrare in contatto con centinaia di imprese e collaborare nello sviluppo di progetti e attività.', ['platformName' => \Yii::$app->name]));
                $ctaLoginRegister = Html::a(
                    Module::t('amospartnershipprofiles', 'accedi o registrati alla piattaforma'),
                    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon'] : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                    [
                        'title' => Module::t('amospartnershipprofiles', 'Clicca per accedere o registrarti alla piattaforma {platformName}', ['platformName' => \Yii::$app->name])
                    ]
                );
                $subTitleSection .= Html::tag('p', Module::t('amospartnershipprofiles', 'Se vuoi rispondere alle proposte di collaborazione di {platformName} {ctaLoginRegister}', ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]));
            } else {
                $titleSection = Module::t('amospartnershipprofiles', 'Proposte di mio interesse');
                $urlLinkAll = '/partnershipprofiles/partnership-profiles/own-interest';
                $labelLinkAll = Module::t('amospartnershipprofiles', 'Tutte le proposte di mio interesse');
                $titleLinkAll = Module::t('amospartnershipprofiles', 'Visualizza tutte le proposte');

                $subTitleSection = Html::tag('p', Module::t('amospartnershipprofiles', 'Innovazione, competitività e networking: la piattaforma {platformName} offre ai suoi utenti l’opportunità di entrare in contatto con centinaia di imprese e collaborare nello sviluppo di progetti e attività.', ['platformName' => \Yii::$app->name]));
                $subTitleSection .= Html::tag('p', Module::t('amospartnershipprofiles', 'Lancia una nuova proposta di collaborazione sulla piattaforma, clicca su “NUOVA”!', ['platformName' => \Yii::$app->name]));
            }

            $labelCreate = Module::t('amospartnershipprofiles', 'Nuova');
            $titleCreate = Module::t('amospartnershipprofiles', 'Crea una nuova proposta');
            $labelManage = Module::t('amospartnershipprofiles', 'Gestisci');
            $titleManage = Module::t('amospartnershipprofiles', 'Gestisci le proposte');
            $urlCreate = '/partnershipprofiles/partnership-profiles/create';

            $manageLinks = [];

            $controller = \open20\amos\partnershipprofiles\controllers\PartnershipProfilesController::class;
            if (method_exists($controller, 'getManageLinks')) {
                $manageLinks = $controller::getManageLinks();
            }

            $canCreate = \Yii::$app->user->can('PARTNERSHIPPROFILES_CREATE', ['model' => $model]);

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

                $listViewLayout = $this->render(
                    '@vendor/open20/design/src/components/yii2/views/listViewOwlCarouselLayout',
                    [
                        'landscapeColsNumber' => 'one'
                    ]
                );

                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_itemOwlCarouselPartnershipProfilesDesign',
                    'viewParams' => [
                        'detailPage' => $detailPage,
                        'viewFields' => $viewFields,
                        'blockItemId' => $blockItemId,
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
                        <p><?= BaseAmosModule::t('amosapp', 'Non ci sono contenuti che corrispondono ai tuoi interessi') ?></p>
                    </div>
                <?php endif; ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>