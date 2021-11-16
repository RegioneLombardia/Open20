<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Open2.0 Design - empty layout</title>
    <?php $this->head() ?>
    <?php $this->registerJs("window.__PUBLIC_PATH__ = '{$bootstrapItaliaAsset->baseUrl}/node_modules/bootstrap-italia/dist/fonts'", \yii\web\View::POS_HEAD); ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
    <!-- Begin page content -->
    <main role="main" class="flex-shrink-0">

        <?php echo $content ?>


    </main>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>