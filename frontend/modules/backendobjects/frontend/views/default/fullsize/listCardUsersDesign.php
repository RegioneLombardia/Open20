<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\admin\AmosAdmin;
use open20\design\utility\DesignUtility;
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
            $titleSection = AmosAdmin::t('amosadmin', 'Utenti');
            $urlLinkAll = '/' . AmosAdmin::getModuleName() . '/user-profile/validated-users';
            $labelLinkAll = AmosAdmin::t('amosadmin', 'Tutti gli utenti');
            $titleLinkAll = AmosAdmin::t('amosadmin', 'Visualizza la lista degli utenti della piattaforma');

            $labelSigninOrSignup = AmosAdmin::t('amosadmin', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosAdmin::t(
                'amosadmin',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosAdmin::t('amosadmin', '#beforeActionCtaLogin');
            $titleSignin = AmosAdmin::t(
                'amosadmin',
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
                AmosAdmin::t(
                    'amosadmin',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosAdmin::t('amosadmin', 'La mia rete');
            $urlLinkAll = '/' . AmosAdmin::getModuleName() . '/user-profile/my-network';
            $labelLinkAll = AmosAdmin::t('amosadmin', 'Tutti gli utenti della mia rete');
            $titleLinkAll = AmosAdmin::t('amosadmin', 'Visualizza la lista degli utenti a cui sono connesso');

            // $subTitleSection = Html::tag('p', AmosAdmin::t('amosadmin', '');
        }

        $labelCreate = AmosAdmin::t('amosadmin', 'Nuovo');
        $titleCreate = AmosAdmin::t('amosadmin', 'Crea un nuovo utente');
        $labelManage = AmosAdmin::t('amosadmin', 'Gestisci');
        $titleManage = AmosAdmin::t('amosadmin', 'Gestisci gli utenti');
        $urlCreate = '/' . AmosAdmin::getModuleName() . '/user-profile/create';

        $manageLinks = [];
        $controller = \open20\amos\admin\controllers\UserProfileController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }

        $canCreate = \Yii::$app->user->can('USERPROFILE_CREATE', ['model' => $model]);

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
                'itemView' => '_itemCardUsersDesign',
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
                        <p class="mb-0"><strong><?= AmosAdmin::t('amosadmin', 'Non sei ancora collegato a nessun utente!') ?></strong></p>
                        <?=
                        Html::a(
                            AmosAdmin::t('amosadmin', 'Clicca qui'),
                            '/' . AmosAdmin::getModuleName() . '/user-profile/validated-users',
                            [
                                'title' => AmosAdmin::t('amosadmin', 'Clicca e scopri tutti gli utenti della piattaforma {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            AmosAdmin::t('amosadmin', 'e scopri ora tutti gli utenti di {platformName}', ['platformName' => \Yii::$app->name])
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosAdmin::t('amosadmin', 'Non sono presenti utenti') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>