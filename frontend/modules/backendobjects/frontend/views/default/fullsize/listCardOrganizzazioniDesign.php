<?php

use yii\widgets\ListView;
use open20\amos\organizzazioni\Module;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use open20\design\utility\DesignUtility;
use open20\amos\core\module\BaseAmosModule;
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
        $modelLabel = strtolower($model->getGrammar()->getModelLabel());

        //COPIA CONTENUTO DI BEFOREACTION IN CONTROLLER NEWS - TODO DA RENDERE STATIC
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosorganizzazioni', 'Organizzazioni');
            $labelLinkAll = Module::t('amosorganizzazioni', 'Tutte le organizzazioni');
            $urlLinkAll   = '/news/news/all-news';
            $titleLinkAll = Module::t('amosorganizzazioni', 'Visualizza la lista delle organizzazioni');

            $labelSigninOrSignup = Module::t('amosorganizzazioni', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = Module::t(
                'amosorganizzazioni',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = Module::t('amosorganizzazioni', '#beforeActionCtaLogin');
            $titleSignin = Module::t(
                'amosorganizzazioni',
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
                Module::t(
                    'amosorganizzazioni',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );

        } else {
            $titleSection = Module::t('amosorganizzazioni', 'Le mie organizzazioni');
            $labelLinkAll = Module::t('amosorganizzazioni', 'Tutte le mie organizzazioni');
            $urlLinkAll   = '/organizzazioni/profilo/index';
            $titleLinkAll = Module::t('amosorganizzazioni', 'Visualizza la lista delle mie organizzazioni');

            $subTitleSection = Html::tag('p', Module::t('amosorganizzazioni', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = Module::t('amosorganizzazioni', 'Nuova');
        $titleCreate = Module::t('amosorganizzazioni', 'Crea una nuova organizzazione');
        $labelManage = Module::t('amosorganizzazioni', 'Gestisci');
        $titleManage = Module::t('amosorganizzazioni', 'Gestisci organizzazioni');
        $urlCreate   = '/organizzazioni/profilo/create';

        $manageLinks = [];
        $controller = \open20\amos\organizzazioni\controllers\base\ProfiloController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }
        $canCreate = \Yii::$app->user->can('PROFILO_CREATE', ['model' => $model]);

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
                'itemView' => '_itemCardOrganizzazioniDesign',
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
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= Module::t('amosorganizzazioni', 'Non sei ancora iscritto a nessuna organizzazione!') ?></strong></p>
                        <?=
                        Html::a(
                            Module::t('amosorganizzazioni', 'Clicca qui'),
                            '/news/news/all-news',
                            [
                                'title' => Module::t('amosorganizzazioni', 'Clicca e scopri tutte le organizzazioni della piattaforma {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            Module::t('amosorganizzazioni', 'e scopri ora tutte le organizzazioni di {platformName}', ['platformName' => \Yii::$app->name])
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= Module::t('amosorganizzazioni', 'Non sono presenti organizzazioni') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>