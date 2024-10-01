<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignUtility;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\widgets\FileExplorerWidget;
use yii\helpers\Html;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;

$folder = null;
$query = $dataProvider->query;
$where = $query->where;
foreach($where as $i => $cond){
    if(is_array($cond)){
        if (array_key_exists('parent_id', $cond)) {
            $folder = $cond['parent_id'];
        }
    }
}

$pagination = $dataProvider->pagination;
$pageSize = $pagination ? $pagination->getPageSize() : 10;

?>


<?php
$modelLabel = strtolower($model->getGrammar()->getModelLabel());
$hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
$hideBreadcrumb = (strpos($cssClass, 'file-explorer-disable-breadcrumb-navigation') !== false) ? true : false;
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
            $subTitleSection = Html::tag(
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
            ]
        );
        ?>
     
    <?php endif ?>

    <div class="widget-file-explorer">
    <?= FileExplorerWidget::widget([
        'folder'=>$folder,
        'withSearch' => $withSearch,
        'showUpdateDate' => $lastUpdateDate,
        'pagination' => $pagination ? true : false,
        'pageSize' => $pageSize,
        'hideFolder' => $hideFolder,
        'hideCategory' => $hideCategory,
        'hideBreadcrumb' => $hideBreadcrumb,
        
    ]) ?>
    </div>
    
</div>