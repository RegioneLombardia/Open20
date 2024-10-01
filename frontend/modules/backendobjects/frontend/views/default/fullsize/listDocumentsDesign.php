<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignUtility;
use open20\amos\documenti\AmosDocumenti;
use yii\helpers\Html;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$moduleDocuments = AmosDocumenti::instance();
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
            $titleSection = AmosDocumenti::t('amosdocumenti', 'Documenti');
            $urlLinkAll = '/documenti/documenti/all-documents';
            $labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
            $titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

            $labelSigninOrSignup = AmosDocumenti::t('amosdocumenti', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosDocumenti::t(
                'amosdocumenti',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosDocumenti::t('amosdocumenti', '#beforeActionCtaLogin');
            $titleSignin = AmosDocumenti::t(
                'amosdocumenti',
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
                AmosDocumenti::t(
                    'amosdocumenti',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosDocumenti::t('amosdocumenti', 'Documenti di mio interesse');
            $urlLinkAll = '/documenti/documenti/own-interest-documents';
            $labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti documenti di mio interesse');
            $titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti di mio interesse');
        }

        $labelCreate = AmosDocumenti::t('amosdocumenti', 'Nuovo');
        $titleCreate = AmosDocumenti::t('amosdocumenti', 'Crea un nuovo documento');
        $labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
        $titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
        $urlCreate = '/documenti/documenti/create';

        $urlSecondAction = '/documenti/documenti/create?isFolder=1';
        $labelSecondAction = AmosDocumenti::t('amosdocumenti', 'Nuova Cartella');
        $titleSecondAction = AmosDocumenti::t('amosdocumenti', 'Crea una nuova cartella');

        $manageLinks = [];
        $controller = \open20\amos\documenti\controllers\DocumentiController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }

        $canCreate = \Yii::$app->user->can('DOCUMENTI_CREATE', ['model' => $model]);

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
                'urlSecondAction' => $urlSecondAction,
                'labelSecondAction' => $labelSecondAction,
                'titleSecondAction' => $titleSecondAction,
                'hideSecondAction' => (!empty($moduleDocuments) && !empty($moduleDocuments->enableFolders) && $moduleDocuments->enableFolders == true) ? true : false,
            ]
        );
        ?>
    <?php endif ?>

    <?php if ($withSearch) { ?>
        <?= $this->render('parts/_searchDocumentDesign', ['model' => $model]); ?>
    <?php } ?>

    <div class="card-<?= $modelLabel ?>-container">
        <?php
        if ($dataProvider->getTotalCount() > 0) {

            $listViewLayout = $this->render('@vendor/open20/design/src/components/yii2/views/listViewLayout');

            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemDocumentsDesign',
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
                        <p class="mb-0"><strong><?= AmosDocumenti::t('amosdocumenti', 'Non ci sono documenti di tuo interesse da visualizzare!') ?></strong></p>
                        <?=
                        Html::a(
                            AmosDocumenti::t('amosdocumenti', 'Clicca qui'),
                            '/documenti/documenti/all-documents',
                            [
                                'title' => AmosDocumenti::t('amosdocumenti', 'Clicca e scopri tutti i documenti della piattaforma {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            AmosDocumenti::t('amosdocumenti', 'e scopri ora tutti i documenti di {platformName}', ['platformName' => \Yii::$app->name])
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosDocumenti::t('amosdocumenti', 'Non sono presenti documenti') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>