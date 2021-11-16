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
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "head", [
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

    <section id="bk-page" class="fullsizeListLayout preferenceFullsizeListLayout">

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div class="container <?= (!empty($this->params['containerFullWidth']) && $this->params['containerFullWidth']
                                    == true) ? 'container-full-width' : ''
                                ?>">

            <div class="page-content">

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                <?php if (
                    !empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine']
                    == WidgetAbstract::ENGINE_ROWS
                ) : ?>

                    <?php
                    $isLayoutInScope = false;
                    $moduleCwh = \Yii::$app->getModule('cwh');
                    if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                        $scope = $moduleCwh->getCwhScope();
                        $isLayoutInScope = (!empty($scope)) ? true : false;
                    }
                    ?>

                    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope", ['isLayoutInScope' => $isLayoutInScope]); ?>
                <?php endif; ?>

                <div class="page-header">
                    <?=
                    $this->render(
                        "parts" . DIRECTORY_SEPARATOR . "bi-less-plugin-header",
                        [
                            'isGuest' => \Yii::$app->user->isGuest,
                            'modelLabel' =>  $this->params['modelLabel'],
                            'titleSection' => $this->params['titleSection'],
                            'subTitleSection' => $this->params['subTitleSection'],
                            'subTitleAdditionalClass' => $this->params['subTitleAdditionalClass'],
                            'urlLinkAll' => $this->params['urlLinkAll'],
                            'labelLinkAll' => $this->params['labelLinkAll'],
                            'titleLinkAll' =>  $this->params['titleLinkAll'],
                            'hideCreate' => $this->params['hideCreate'],
                            'urlCreate' => $this->params['urlCreate'],
                            'labelCreate' =>  $this->params['labelCreate'],
                            'titleCreate' => $this->params['titleCreate'],
                            'dataConfirmCreate' => $this->params['dataConfirmCreate'],

                            'hideSecondAction' =>  $this->params['hideSecondAction'],
                            'urlSecondAction' =>  $this->params['urlSecondAction'],
                            'labelSecondAction' =>  $this->params['labelSecondAction'],
                            'titleSecondAction' => $this->params['titleSecondAction'],
                            'iconSecondAction' => $this->params['iconSecondAction'],

                            'labelManage' => $this->params['labelManage'],
                            'titleManage' => $this->params['titleManage'],
                            'hideManage' => $this->params['hideManage'],
                            'bulletCount' =>  $this->params['bulletCount'],
                        ]
                    );
                    ?>
                </div>

                <?php if (array_key_exists('currentDashboard', $this->params) && !(!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true)) : ?>
                    <div class="col-xs-12 nop tabs-container">
                        <?php
                        $items                = [];
                        $widgetsIcons         = $thisDashboardWidgets = $this->params['currentDashboard']->getAmosWidgetsSelectedIcon(true);
                        if (\Yii::$app->controller->hasProperty('child_of')) {
                            $widgetsIcons->andFilterWhere([AmosWidgets::tableName() . '.child_of' => \Yii::$app->controller->child_of]);
                        }

                        foreach ($widgetsIcons->all() as $widgetIcon) {
                            if (Yii::$app->user->can($widgetIcon['classname'])) {
                                $widgetObj                       = Yii::createObject($widgetIcon['classname']);
                                $label                           = $widgetObj->bulletCount ? $widgetObj->label . '<span class="badge badge-default">' . $widgetObj->bulletCount . '</span>'
                                    : $widgetObj->label;
                                $items[$widgetIcon['classname']] = ['label' => $label, 'url' => $widgetObj->url];
                            }
                        }

                        echo \open20\amos\core\toolbar\Nav::widget([
                            'items' => $items,
                            'encodeLabels' => false,
                            'options' => ['class' => 'nav nav-tabs'],
                        ]);
                        ?>
                    </div>
                <?php endif; ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "change_view"); ?>

                <?= $content ?>
            </div>
        </div>

    </section>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>