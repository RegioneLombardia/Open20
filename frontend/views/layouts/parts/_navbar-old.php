<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    landing
 * @category   CategoryName
 *
 *
 * @var $this \yii\web\View
 * @var $assetBundle \yii\web\AssetBundle
 *
 */

use app\assets\ResourcesAsset;
use app\components\CmsHelper;


$resourceAsset = ResourcesAsset::register($this);
?>
<div style="z-index: 980;" uk-sticky="top: 100; animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up">
<div class="topbar">
    <div class="container-padding">
        <div class="logo-bianco">
            <a href="https://www.regione.lombardia.it/" title="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
                <img src="<?= $assetBundle->baseUrl ?>/img/logo_regione_lombardia-bianco.png" alt="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
            </a>
        </div>
        <!-- <div class="login-logout dropdown hidden">
            < ?php if (!\Yii::$app->user->isGuest) {
                $profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
                $nomeCognome = $profile->nomeCognome;
                ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span><?= $nomeCognome ?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="< ?= \Yii::$app->params['platform']['backendUrl'] . "/dashboard" ?>">Accedi all'area riservata</a></li>
                    <li>
                        <a href="< ?= \Yii::$app->params['platform']['backendUrl'] . "/logout" ?>">Logout</a>
                    </li>
                </ul>
            < ?php } else { ?>

                <a data-method="post" href="< ?= \Yii::$app->params['platform']['backendUrl'] . "/dashboard" ?>">
                    <svg class="bg-light icon">
                        <use xlink:href="< ?= $assetBundle->baseUrl ?>/img/icons/sprite.svg#it-key"></use>
                    </svg>
                    Accedi</a>
            < ?php } ?>
        </div> -->
    </div>
</div>
<div class="header-center-wrapper" style="width:100%">
    <div class="container-padding">
        <div>
            <div>
                <div class="header-center-content-wrapper">
                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <!--LOGO-->
                    <div class="newloghi">
                        <a href="https://www.regione.lombardia.it/" class="logo logo-regione" title="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
                            <img src="<?= $assetBundle->baseUrl ?>/img/logo_regione_lombardia.svg" alt="<?= \Yii::t('app', 'Regione Lombardia'); ?>">
                        </a>
                        <!-- < ?= \Yii::t('app', 'Eventi Regione Lombardia'); ?> -->
                        <a href="<?= "/" ?>" class="logo hidden" title="<?= \Yii::t('app', 'Eventi Regione Lombardia'); ?>">
                            <img src="#" alt="<?= \Yii::t('app', 'Eventi Regione Lombardia'); ?>">
                        </a>
                    </div>
                    <!--END: LOGO-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: navigation -->
<div class="scrollable-menu collapse navbar-collapse" id="navbar-main">
    <div>
        <div>
            <div>
                <!-- BEGIN: default  -->
                <ul class="nav navbar-nav main-nav">
                    <?= CmsHelper::MenuRender(Yii::$app->menu->findAll(['depth' => 1, 'container' => 'default'])); ?>

                </ul>
                <!-- END: default  -->
            </div>
        </div>

    </div>
</div>
<!--  END: navigation-->
</div>

<?php

?>