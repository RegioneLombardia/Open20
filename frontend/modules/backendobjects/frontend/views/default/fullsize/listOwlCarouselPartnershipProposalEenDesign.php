<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use open20\design\utility\DesignIcon;
use open20\design\utility\DesignUtility;
use open20\amos\een\AmosEen;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;

// count all proposal een

?>

<?php if (!\Yii::$app->user->isGuest) { ?>
    <?php
    $modelSearch = new \open20\amos\een\models\search\EenPartnershipProposalSearch();
    $dataProviderAll = $modelSearch->searchAll([]);
    $n = $dataProviderAll->getTotalCount();
    ?>

    <div class="modulo-backend-<?= $modelLabel ?> <?= $cssClass ?> py-5">
        <?php if (!($cssClass == 'hide-bi-plugin-header')) : ?>
            <?php
            $modelLabel = strtolower($model->getGrammar()->getModelLabel());

            if ($isGuest) {
                $titleSection = AmosEen::t('amoseen', 'Proposte di collaborazione dal mondo');
                $urlLinkAll = '/een/een-partnership-proposal/index';
                $labelLinkAll = AmosEen::t('amoseen', 'Tutte le proposte');
                $titleLinkAll = AmosEen::t('amoseen', 'Visualizza la lista delle proposte');

                $subTitleSection = Html::tag('p', AmosEen::t('amoseen', 'Scopri più di {n} proposte di collaborazione internazionale promosse da Enterprise Europe Network (EEN), la rete mondiale dei centri di supporto all\'innovazione, all\'internazionalizzazione e alla competitività delle imprese. Aziende, enti pubblici e multinazionali da tutto il mondo offrono opportunità di cooperazione per le aziende.', [
                    'platformName' => \Yii::$app->name,
                    'n' => $n
                ]));
                $ctaLoginRegister = Html::a(
                    AmosEen::t('amoseen', 'accedi o registrati alla piattaforma'),
                    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon'] : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                    [
                        'title' => AmosEen::t('amoseen', 'Clicca per accedere o registrarti alla piattaforma {platformName}', ['platformName' => \Yii::$app->name])
                    ]
                );
                $subTitleSection .= Html::tag('p', AmosEen::t('amoseen', 'Se vuoi vedere o lanciare delle proposte di collaborazione {ctaLoginRegister}', ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]));
            } else {
                $titleSection = AmosEen::t('amoseen', 'Proposte dal mondo di mio interesse');
                $urlLinkAll = '/een/een-partnership-proposal/own-interest';
                $labelLinkAll = AmosEen::t('amoseen', 'Tutte le proposte dal mondo di mio interesse');
                $titleLinkAll = AmosEen::t('amoseen', 'Visualizza tutte le proposte');

                $subTitleSection = Html::tag('p', AmosEen::t('amoseen', 'Scopri più di {n} proposte di collaborazione internazionale promosse da Enterprise Europe Network (EEN), la rete mondiale dei centri di supporto all\'innovazione, all\'internazionalizzazione e alla competitività delle imprese. Aziende, enti pubblici e multinazionali da tutto il mondo offrono opportunità di cooperazione per le aziende.', [
                    'platformName' => \Yii::$app->name,
                    'n' => $n
                ]));
                $subTitleSection .= Html::tag('p', AmosEen::t('amoseen', 'Lancia una nuova proposta di collaborazione sulla piattaforma, clicca su “NUOVA”!', ['platformName' => \Yii::$app->name]));
            }

            $labelCreate = AmosEen::t('amoseen', 'Nuova');
            $titleCreate = AmosEen::t('amoseen', 'Crea una nuova proposta');
            $labelManage = AmosEen::t('amoseen', 'Gestisci');
            $titleManage = AmosEen::t('amoseen', 'Gestisci le proposte');
            $urlCreate = '/een/een-partnership-proposal/create-proposal';

            $manageLinks = [];

            $controller = \open20\amos\een\controllers\EenPartnershipProposalController::class;
            if (method_exists($controller, 'getManageLinks')) {
                $manageLinks = $controller::getManageLinks();
            }

            $canCreate = \Yii::$app->user->can('PROPOSTECOLLABORAZIONEEEN_CREATE', ['model' => $model]);

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
                    'itemView' => '_itemOwlCarouselPartnershipProposalEenDesign',
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