<?php

use \open20\amos\layout\assets\BootstrapItaliaCustomAsset;
use open20\amos\core\components\AmosView;
use yii\helpers\Html;


$currentAsset = BootstrapItaliaCustomAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-head"); ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-header", [
        'currentAsset' => $currentAsset
    ]); ?>


    <div id="mainContent" class="container-fluid bootstrap-italia-layout-with-sidebar <?= \Yii::$app->view->params['customClassMainContent'] ?>">
        <div class="row">

            <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-sidebarLeft", [
                'currentAsset' => $currentAsset
            ]); ?>

            <main role="main" class="py-4 px-lg-5 px-4 w-100">
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-messages", [
                    'currentAsset' => $currentAsset
                ]); ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-content-header", [
                    'currentAsset' => $currentAsset,
                    'titleContent' => $this->title
                ]); ?>

                <?php if ($this instanceof AmosView) : ?>
                    <?php $this->beginViewContent() ?>
                <?php endif; ?>
                <div class="d-flex justify-content-between">
                
                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-search", [
                    'currentAsset' => $currentAsset
                ]); ?>

                <?= $this->render("parts" . DIRECTORY_SEPARATOR . "bi-change-view", [
                    'currentAsset' => $currentAsset
                ]); ?>
                </div>
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