<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use app\assets\ResourcesAsset;
use luya\cms\widgets\LangSwitcher;
use app\modules\seo\frontend\behaviors\LuyaSeoBehavior;
use luya\helpers\Url;

$assetBundle = ResourcesAsset::register($this);
\app\assets\SimplePaginationAsset::register($this);

/* @var $this luya\web\View */
/* @var $content string */
$this->attachBehavior('seo', LuyaSeoBehavior::className());
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->composition->language; ?>">
<!-- HEAD -->
<?= $this->render('parts/_head', [
    'assetBundle' => $assetBundle,
    'title' => $this->title,
    'head' => $this->head()
]) ?>

<body class="layout-landing">
    <?php $this->beginBody() ?>

    <!-- NAVBAR -->
    <?= $this->render('parts/_navbar', [
        'assetBundle' => $assetBundle
    ]) ?>
    <!-- END: NAVBAR -->

    <?php
    $landingClass = strtolower(str_replace(' ', '-', $this->title));
    ?>
    <div class="content content-landing <?= $landingClass ?>">
        <?= $content; ?>
    </div>
        <!-- -->

    <!-- FOOTER -->
    <?= $this->render('parts/_footer', [
        'assetBundle' => $assetBundle
    ]) ?>
    <!-- END: FOOTER -->

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>