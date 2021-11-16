<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    landing
 * @category   CategoryName
 *
 * @var $this \yii\web\View
 * @var $assetBundle \yii\web\AssetBundle
 *
 */

use app\components\CmsHelper;

?>

<footer class="footer">

    <div class="container-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2 text-right">
                    <a href="#" uk-totop uk-scroll></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
$socialModule = \Yii::$app->getModule('social');
if (!empty($socialModule) && class_exists('\kartik\social\GoogleAnalytics')) :
    if (YII_ENV_PROD && !empty($socialModule->googleAnalytics)) :
        echo \kartik\social\GoogleAnalytics::widget([]);
    endif;
endif;
?>