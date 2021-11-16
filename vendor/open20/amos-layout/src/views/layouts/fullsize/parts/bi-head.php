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

// la registrazione in questo modo non può funzionare per ora senza includere il js di bootstrap-italia
//$this->registerJs("window.__PUBLIC_PATH__ = '{$currentAsset->baseUrl}/node_modules/bootstrap-italia/dist/fonts'", \yii\web\View::POS_HEAD);

/* JSLoggingAsset::register($this); */
?>
 
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= Html::csrfMetaTags() ?>
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?= Html::encode(Yii::$app->name) ?> - <?= Html::encode($this->title) ?></title>
<link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?><?= Yii::$app->params['favicon'] ?>" type="image/x-icon" />
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"> -->
<?php $this->head() ?>
