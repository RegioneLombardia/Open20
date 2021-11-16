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

//\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
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
}

FormAsset::register($this);
IEAssets::register($this);

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "head"); ?>
</head>

<body>

    <?php $this->beginBody() ?>

    
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "header"); ?>
        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "logo"); ?>

    <?php if (isset(Yii::$app->params['logo-bordo'])) : /*&& \Yii::$app->params['logo-bordo'] == TRUE)*/ ?>
        <div class="container-bordo-logo"><img src="<?= Yii::$app->params['logo-bordo'] ?>" alt=""></div>
    <?php endif; ?>

    <section id="bk-page">

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "messages"); ?>

        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "help"); ?>

        <div id="record_form" class="container">

            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "network_scope"); ?>

            <div class="page-content">
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs"); ?>

                <div class="page-header">
                    <?php if (!is_null($this->title)) : ?>
                        <h1 class="title"><?= Html::encode($this->title) ?></h1>
                        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "textHelp"); ?>
                    <?php endif; ?>
                </div>

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