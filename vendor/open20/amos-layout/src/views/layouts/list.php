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

////\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
/* @var $this \yii\web\View */
/* @var $content string */

$urlCorrente = Url::current();
$arrayUrl = explode('/', $urlCorrente);
$countArrayUrl = count($arrayUrl);
$percorso = '';
$i = 0;
$moduloId = Yii::$app->controller->module->id;
$basePath = Yii::$app->getBasePath();
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
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "head"); ?>
</head>

<body>

    <!-- add for fix error message Parametri mancanti -->
    <input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>" />

    <?php $this->beginBody() ?>


        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "header"); ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>


    <?php if (isset(Yii::$app->params['logo-bordo'])) : ?>
        <div class="container-bordo-logo"><img src="<?= Yii::$app->params['logo-bordo'] ?>" alt=""></div>
    <?php endif; ?>

    <section id="bk-page">
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div class="container <?= (!empty($this->params['containerFullWidth']) && $this->params['containerFullWidth'] == true) ? 'container-full-width' : '' ?>">

            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope"); ?>

            <div class="page-content">

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                <div class="page-header">
                    <?php if (!is_null($this->title)) : ?>
                        <h1 class="title"><?= Html::encode($this->title) ?></h1>
                        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "textHelp"); ?>
                    <?php endif; ?>
                </div>

                <?php if (array_key_exists('currentDashboard', $this->params)) : ?>
                    <div class="col-xs-12 nop">
                        <?php
                        $items = [];
                        $widgetsIcons = $thisDashboardWidgets = $this->params['currentDashboard']->getAmosWidgetsSelectedIcon(true);
                        if (\Yii::$app->controller->hasProperty('child_of')) {
                            $widgetsIcons->andFilterWhere([AmosWidgets::tableName() . '.child_of' => \Yii::$app->controller->child_of]);
                        }

                        foreach ($widgetsIcons->all() as $widgetIcon) {
                            if (Yii::$app->user->can($widgetIcon['classname'])) {
                                $widgetObj = Yii::createObject($widgetIcon['classname']);
                                $label = $widgetObj->bulletCount ? $widgetObj->label . '<span class="badge badge-default">' . $widgetObj->bulletCount . '</span>'
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

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "sponsors"); ?>

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "footer_text"); ?>

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "assistance"); ?>

    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>