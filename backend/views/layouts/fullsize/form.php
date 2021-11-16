<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\layout\assets\FormAsset;
use open20\amos\layout\assets\IEAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;
use app\components\CmsHelper;

//\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
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
}
$showSidebarForm = (isset($this->params['showSidebarForm'])) ? $this->params['showSidebarForm'] : false;

FormAsset::register($this);
IEAssets::register($this);
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?=
    $this->render(
        "parts" . DIRECTORY_SEPARATOR . "head",
        [
            'title' => ((Yii::$app->isCmsApplication()) && !empty($this->params['titleSection'])) ? $this->params['titleSection']
                : $this->title
        ]
    );
    ?>
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
        "parts" . DIRECTORY_SEPARATOR . "bi-less-header",
        [
            'currentAsset' => $currentAsset,
            'privacyPolicyLink' => \Yii::$app->params['platform']['frontendUrl'] . \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],
            'cookiePolicyLink' => \Yii::$app->params['platform']['frontendUrl'] . \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon'],
            'hideHamburgerMenu' => true,
            'alwaysHamburgerMenu' => true,
            'hideGlobalSearch' => true,
            'hideLangSwitchMenu' => true,
            'frontendUrl' => \Yii::$app->params['platform']['frontendUrl'],
        ]
    );
    ?>

    <section id="bk-page" class="fullsizeFormLayout preferenceFullsizeFormLayout">

        <?php
        if (
            !empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS
            && (!isset(\Yii::$app->params['disable_network_scope']) || \Yii::$app->params['disable_network_scope'] == false)
        ) :
        ?>
            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope"); ?>
        <?php endif; ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div id="record_form" class="container">

            <?php if (empty(\Yii::$app->params['dashboardEngine'])) : ?>
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope"); ?>
            <?php endif; ?>

            <div class="page-content">
                <div class="<?= ($showSidebarForm) ? 'layout-sidebarForm' : 'layout-standardForm' ?>">
                    <?php
                    if ($showSidebarForm) {
                        echo $this->render("parts" . DIRECTORY_SEPARATOR . "bi-less-sidebarForm", ['viewParams' => \Yii::$app->getView()->params['bi-menu-sidebar']]);
                    }
                    ?>

                    <div class="<?= ($showSidebarForm) ? 'main-with-sidebar' : 'w-100' ?>">
                        <?php if ($showSidebarForm) { ?>
                            <a class="btn-sidebar btn-lg" role="button" title="Visualizza sidebar" data-toggle="collapse" href="#sidebarForm" aria-expanded="false" aria-controls="sidebarForm">
                                <span class="mdi mdi-24px mdi-apps mdi-flip-h"></span>
                            </a>
                        <?php } ?>
                        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                        <div class="page-header">
                            <?php
                            if (is_array($this->params['titleButtons']) && !empty($this->params['titleButtons'])) {
                                echo "<div class='w-100 text-right m-b-20'>";
                                foreach ($this->params['titleButtons'] as $button) {
                                    echo $button;
                                }
                                echo "</div>";
                            }
                            ?>

                            <?php if (!is_null($this->title)) : ?>
                                <h1 class="title"><?= Html::encode($this->title) ?></h1>
                                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "textHelp"); ?>
                            <?php endif; ?>

                        </div>

                        <?= $content ?>

                    </div>
                </div>
            </div>
        </div>

    </section>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>