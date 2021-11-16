<?php

use \open20\amos\layout\assets\BootstrapItaliaCustomAsset;
use open20\amos\core\components\AmosView;

$currentAsset = BootstrapItaliaCustomAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-head", [
        'currentAsset' => $currentAsset
    ]); ?>
</head>

<body class="bg-body">
    <?php $this->beginBody() ?>

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-header", [
        'currentAsset' => $currentAsset,
        'disableToggleSidebar' => true
    ]); ?>


    <div id="mainContent" class="container-fluid bootstrap-italia-layout-no-sidebar <?= \Yii::$app->view->params['customClassMainContent'] ?>">
        <div class="row">

            <main role="main" class="py-4 px-lg-5 px-4 w-100 bg-white">
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-messages", [
                    'currentAsset' => $currentAsset
                ]); ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-breadcrumbs", [
                    'currentAsset' => $currentAsset
                ]); ?>

                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->beginViewContent() ?>
                <?php endif; ?>
                <?= $content ?>
                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->endViewContent() ?>
                <?php endif; ?>
            </main>
        </div>



        <?= $this->render("parts" . DIRECTORY_SEPARATOR . "footer"); ?>

        <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>