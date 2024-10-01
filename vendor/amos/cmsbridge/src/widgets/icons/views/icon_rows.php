<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\widget\views
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\mobile\bridge\modules\v1\models\User as TokenUser;
use yii\web\View; 
/**
 * @var $this \yii\web\View
 * @var $widget \open20\amos\core\widget\WidgetIcon
 * @var $asset \yii\web\AssetBundle
 */
$moduleCms = Yii::$app->getModule('cmsbridge');
$moduleCms->frontendUrl = !empty($moduleCms->frontendUrl)? $moduleCms->frontendUrl : Yii::$app->params['platform']['frontendUrl'];


$classSpanStr = implode(' ', $widget->classSpan);
$classSpanLi = implode(' ', $widget->classLi);
$classSpanA = implode(' ', $widget->classA);
$className = $widget::className();
$userAgent = (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') > -1 || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? 'ie' : '';

$url = is_array($widget->url) ? \yii\helpers\Url::to($widget->url) : $widget->url;
$target = ((strlen($widget->targetUrl) > 0) ? 'target="' . $widget->targetUrl . '" ' : '');
$dataModule = $widget->moduleName;


$user = TokenUser::findOne(Yii::$app->user->id);
$token = $user->refreshAccessToken('webcms', 'cms')->access_token;
$url_front = $moduleCms->frontendUrl . '/admin/login/login-amos';
$base_url_front = $moduleCms->frontendUrl;
$admin_url_front = $moduleCms->frontendUrl . '/admin';
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

$cms_bridge_url = 'cms_bridge_url-' . substr(uniqid(), -3);
$script = <<< JS


    $('#{$cms_bridge_url}').click(function() {
window.jCallback = function (data) {
     //let newTab = window.open();
	//  window.location.href = '{$admin_url_front}';
    
        var url = '{$admin_url_front}';
        window.open(url, '_blank');

    };
        var csrf = '{$csrfToken}';

        $.ajax({
		cache: false,
                timeout:10000,
                type: "POST",
                url: "{$url_front}",
		jsonp : false,
    		jsonpCallback: 'jCallback',
                crossDomain: true,
                data: { 'secure_token' : '{$token}'},
                contentType: "application/json; charset=utf-8",
		async: false,
                dataType: "jsonp",
                success: function (data) {
                   //do something
                   console.log("working");
                },
                error: function (request, status, error) {

                    //do something else
                    console.log(error);

                }
            });

    });

JS;
$this->registerJs($script, View::POS_END);
?>

<div class="square-box" data-code="<?= $className ?>">
    <div class="square-content item-widget" data-code="<?= $className ?>">
        <?php if (strlen($url)): ?>
            <a id="<?= $cms_bridge_url ?>" data-module="<?= $dataModule ?>" class="<?= $classSpanA ?> dashboard-menu-item" href="#"
           <?= $target ?>title="<?= $widget->description ?>" role="menuitem" class="sortableOpt1" <?= $widget->dataPjaxZero ?> <?= $widget->attributes ?>>
        <?php else: ?>
            <div class="dashboard-menu-item">
        <?php endif; ?>

        <?php if (strlen($url) && ($widget->targetUrl == '_blank')): ?>
            <span class="sr-only"><?= Yii::t('amoscore', 'Questo link verrà aperto in una nuova pagina') ?></span>
        <?php endif; ?>
        <span class="badge"><?= $widget->bulletCount ? $widget->bulletCount : '' ?></span>
        <?php if (!(strpos($classSpanA, 'open-modal-dashboard') === false)) {
            echo AmosIcons::show('modale', ['class' => 'icon-open-modal'], AmosIcons::IC);
        } ?>
        <span class="<?= $classSpanStr ?>">
            <?= \open20\amos\core\icons\AmosIcons::show($widget->icon, [], $widget->iconFramework) ?>
            <!--span class="svg-container">
                <svg title="< ?= $widget->description ?>" role="img" class="svg-content">
                  <use xlink:href="< ?= $asset->baseUrl ?>/svg/icone< ?= vv ?>.svg#< ?= $widget->icon ?>"></use>
                </svg>
            </span-->
        <span class="icon-dashboard-name pluginName"><?= $widget->label ?></span>

        <?php if (strlen($url)): ?>
            </a>
        <?php else: ?>
            </div>
        <?php endif; ?>
    </div>
</div>
