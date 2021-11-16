<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\core\widget\WidgetAbstract;
use app\components\CmsHelper;

////\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
/* @var $this \yii\web\View */
/* @var $content string */

$urlCorrente   = Url::current();
$arrayUrl      = explode('/', $urlCorrente);
$countArrayUrl = count($arrayUrl);
$percorso      = '';
$i             = 0;
$moduloId      = Yii::$app->controller->module->id;
$basePath      = Yii::$app->getBasePath();
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
    if (file_exists($basePath . '/' . $percorso . '/intro/' . $vista . '.php')) {
        $this->params['intro'] = [
            'filename' => $vista
        ];
    }
}
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render("@vendor" . DIRECTORY_SEPARATOR . "open20" . DIRECTORY_SEPARATOR . "amos-layout" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "fullsize" . DIRECTORY_SEPARATOR ."parts" . DIRECTORY_SEPARATOR . "head", [
        'title' => ((Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection'] : $this->title
    ]); ?>
</head>

<body>

    <!-- add for fix error message Parametri mancanti -->
    <input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>" />

    <?php $this->beginBody() ?>


    <?php
    $currentAsset = isset($currentAsset) ? $currentAsset : open20\amos\layout\assets\BiLessAsset::register($this);
    ?>
    <?=
    $this->render(
        "@vendor" . DIRECTORY_SEPARATOR . "open20" . DIRECTORY_SEPARATOR . "amos-layout" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "fullsize" . DIRECTORY_SEPARATOR ."parts" . DIRECTORY_SEPARATOR . "bi-less-header",
        [
            'currentAsset' => $currentAsset,
            'privacyPolicyLink' => \Yii::$app->params['platform']['frontendUrl'] . \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],
            'cookiePolicyLink' => \Yii::$app->params['platform']['frontendUrl'] . \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon'],
            'hideHamburgerMenu' => true,
            'alwaysHamburgerMenu' => true,
            'hideGlobalSearch' => true,
            'hideUserMenu' => true,
            'hideLangSwitchMenu' => true,
            'frontendUrl' => \Yii::$app->params['platform']['frontendUrl'],
        ]
    );
    ?>

    <section id="bk-page" class="fullsizeListLayout preferenceSondaggiFrontendCustomLayout">

        <?= $this->render("@vendor" . DIRECTORY_SEPARATOR . "open20" . DIRECTORY_SEPARATOR . "amos-layout" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "fullsize" . DIRECTORY_SEPARATOR ."parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <div class="container <?= (!empty($this->params['containerFullWidth']) && $this->params['containerFullWidth']
                                    == true) ? 'container-full-width' : ''
                                ?>">

            <div class="page-content">
                <?= $content ?>
            </div>
        </div>

    </section>

    <?php
    //    if ((isset(\Yii::$app->params['layoutConfigurations']['customPlatformFooter']))) {
    //        $customPlatformFooter = \Yii::$app->params['layoutConfigurations']['customPlatformFooter'];
    //        echo $this->render(
    //            $customPlatformFooter,
    //            [
    //                'currentAsset' => $currentAsset,
    //                'cmsFooterMenu' => $cmsFooterMenu
    //            ]
    //        );
    //    } else {
    echo $this->render(".".DIRECTORY_SEPARATOR."parts" . DIRECTORY_SEPARATOR . "footer-custom", [
        'currentAsset' => $currentAsset,
        'cmsFooterMenu' => $cmsFooterMenu,
        'showSocial' => \Yii::$app->params['layoutConfigurations']['showSocialFooter'],

    ]);
    //    }
    ?>
    <?= $this->render("@vendor" . DIRECTORY_SEPARATOR . "open20" . DIRECTORY_SEPARATOR . "amos-layout" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "fullsize" . DIRECTORY_SEPARATOR ."parts" . DIRECTORY_SEPARATOR . "bi-backtotop-button"); ?>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>