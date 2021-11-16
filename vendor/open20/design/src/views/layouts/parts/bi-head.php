<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\design
 * @category   CategoryName
 */
use yii\helpers\Html;

?>

<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= Html::csrfMetaTags() ?>
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?= Html::encode(Yii::$app->name)?><?= (isset($this->title)) ? ' ' . '-' . ' ' . Html::encode($this->title) : ''?></title>
<link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl . Yii::$app->params['favicon'] ?>" type="image/x-icon" />
<?php $this->head() ?>
<?php $this->registerJs("window.__PUBLIC_PATH__ = '{$currentAsset->baseUrl}/node_modules/bootstrap-italia/dist/fonts'", \yii\web\View::POS_HEAD); ?>

