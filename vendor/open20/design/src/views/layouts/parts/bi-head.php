<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\design
 * @category   CategoryName
 */
use yii\helpers\Html;

/** @var \app\modules\cms\base\CmsView $this */

$seoTitle='';

$ni = \app\modules\cms\models\NavItem::findOne(['id'=> Yii::$app->menu->current->id]);
//\yii\helpers\VarDumper::dump($ni->title_tag,3,1); die;

if (!empty($ni) && !empty($ni->title_tag)) {
	$seoTitle=$ni->title_tag;
} else if (\Yii::$app->getModule('seo') && method_exists($this,'getSeoTitle') && !empty($this->getSeoTitle()  && empty($title))){
    $seoTitle=$this->getSeoTitle();
} else {
	$seoTitle=Html::encode($title);
}
$seoTitle=$seoTitle.' - '. Yii::$app->name;
?>

<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= Html::csrfMetaTags() ?>
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?= $seoTitle?></title>
<link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl . Yii::$app->params['favicon'] ?>" type="image/x-icon" />

<?php $this->head() ?>


<?php $this->registerJs("window.__PUBLIC_PATH__ = '{$currentAsset->baseUrl}/node_modules/bootstrap-italia/dist/fonts';", \yii\web\View::POS_HEAD); ?>

