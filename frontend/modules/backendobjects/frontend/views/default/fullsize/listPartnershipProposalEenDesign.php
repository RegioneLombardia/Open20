<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use yii\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use open20\amos\een\AmosEen;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;
?>

<?php if (!$isGuest) { ?>

    <?php
    $modelLabel = strtolower($model->getGrammar()->getModelLabel());
    ?>
    <div class="modulo-backend-<?= $modelLabel ?> <?= $cssClass ?>">
        <?php if (!($cssClass == 'hide-bi-plugin-header')) : ?>
            <?php
            $modelSearch = new \open20\amos\een\models\search\EenPartnershipProposalSearch();
            /** @var  $dataProvider \yii\data\ActiveDataProvider */
            $dataProvider = $modelSearch->searchAll([]);
            $n = $dataProvider->getTotalCount();
            if ($isGuest) {
                $titleSection = AmosEen::t('amoseen', 'Proposte di collaborazione');
                $urlLinkAll = '/een/een-partnership-proposal/index';
                $labelLinkAll = AmosEen::t('amoseen', 'Tutte le proposte');
                $titleLinkAll = AmosEen::t('amoseen', 'Visualizza la lista delle proposte');


                $ctaLoginRegister = Html::a(
                    AmosEen::t('amoseen', 'accedi o registrati alla piattaforma'),
                    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon'] : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                    [
                        'title' => AmosEen::t('amoseen', 'Clicca per accedere o registrarti alla piattaforma {platformName}', ['platformName' => \Yii::$app->name])
                    ]
                );
                $subTitleSection = Html::tag('p', AmosEen::t('amoseen', 'Scopri più di {n} proposte di collaborazione internazionale promosse da Enterprise Europe Network (EEN), la rete mondiale dei centri di supporto all\'innovazione, all\'internazionalizzazione e alla competitività delle imprese. Aziende, enti pubblici e multinazionali da tutto il mondo offrono opportunità di cooperazione per le aziende.', [
                    'platformName' => \Yii::$app->name,
                    'n' => $n
                ]));
                $subTitleSection .= Html::tag('p', AmosEen::t('amoseen', 'Se vuoi vedere o lanciare delle proposte di collaborazione {ctaLoginRegister}', ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]));
            } else {
                $titleSection = AmosEen::t('amoseen', 'Proposte di mio interesse');
                $urlLinkAll = '/een/een-partnership-proposal/own-interest';
                $labelLinkAll = AmosEen::t('amoseen', 'Tutte le proposte di mio interesse');
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

            $this->view->params['urlSecondAction'] = Yii::$app->urlManager->createUrl(['/een/een-partnership-proposal/create-proposal']);
            $this->view->params['labelSecondAction'] = \open20\amos\een\AmosEen::t('amoseen', "Nuova");
            $this->view->params['titleSecondAction'] = \open20\amos\een\AmosEen::t('amoseen', "Crea una nuova proposta");
            $this->view->params['hideSecondAction'] = false;

            $profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
            if (($profile && !$profile->validato_almeno_una_volta)) {
                $this->view->params['optionsSecondAction'] = [
                    'class' => 'btn btn-navigation-primary',
                    'data-target' => "#modal-een-alert",
                    'data-toggle' => "modal"
                ];
                $this->view->params['urlSecondAction'] = 'javascript:void(0)';
            }
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
                    'itemView' => '_itemPartnershipProposalEenDesign',
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
                        <p><?= BaseAmosModule::t('amosapp', 'Non ci sono contenuti che corrispondono ai tuoi interessi') ?></p>
                    </div>
                <?php endif; ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>