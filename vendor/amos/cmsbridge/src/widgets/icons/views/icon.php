<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\widget\views
 * @category   CategoryName
 */

/**
 * @var $this \yii\web\View
 * @var $widget \open20\amos\core\widget\WidgetIcon
 * @var $asset \yii\web\AssetBundle
 */

$moduleCms = Yii::$app->getModule('cmsbridge');
$moduleCms->frontendUrl = !empty($moduleCms->frontendUrl)? $moduleCms->frontendUrl : Yii::$app->params['platform']['frontendUrl'];


$classSpanStr = @join(' ', $widget->classSpan);
$classSpanLi = @join(' ', $widget->classLi);
$classSpanA = @join(' ', $widget->classA);
$className = $widget::className();

$userAgent = (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') > -1 || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? 'ie' : '';

$url = is_array($widget->url) ? \yii\helpers\Url::to($widget->url) : $widget->url;
$target = ((strlen($widget->targetUrl) > 0) ? 'target="' . $widget->targetUrl . '" ' : '');
$dataModule = $widget->moduleName;
use open20\amos\core\icons\AmosIcons;
use open20\amos\mobile\bridge\modules\v1\models\User as TokenUser;

$user = TokenUser::findOne(Yii::$app->user->id);
$token = $user->refreshAccessToken('webcms', 'cms')->access_token;
$url_front = $moduleCms->frontendUrl . '/admin/login/login-amos';
$base_url_front = $moduleCms->frontendUrl;
$admin_url_front = $moduleCms->frontendUrl . '/admin';
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$script = <<< JS
window.jCallback = function (data) {
     //let newTab = window.open();
	 window.location.href = '{$admin_url_front}';
    };

    $('#cms_bridge_url').click(function() {

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
$this->registerJs($script);
?>

<div class="item-widget col-custom" data-code="<?= $className ?>">
    <?php if (strlen($url)): ?>
    <a id="cms_bridge_url" data-module = "<?=$dataModule?>" class="<?=$classSpanA?>" href="#" <?= $target ?>title="<?= $widget->description ?>" role="menuitem" class="sortableOpt1" <?= $widget->dataPjaxZero ?> <?= $widget->attributes ?>>
        <?php endif; ?>
        <?php if (strlen($url) && ($widget->targetUrl == '_blank')): ?>
            <span class="sr-only"><?= Yii::t('amoscore', 'Questo link verrà aperto in una nuova pagina') ?></span>
        <?php endif; ?>
        <span class="badge"><?= $widget->bulletCount ? $widget->bulletCount : '' ?></span>
        <span class="<?= $classSpanStr ?>">
            <?= \open20\amos\core\icons\AmosIcons::show($widget->icon, [], $widget->iconFramework) ?>
            <!--span class="svg-container">
                <svg title="< ?= $widget->description ?>" role="img" class="svg-content">
                  <use xlink:href="< ?= $asset->baseUrl ?>/svg/icone< ?= vv ?>.svg#< ?= $widget->icon ?>"></use>
                </svg>
            </span-->
        <span class="icon-dashboard-name pluginName"><?= $widget->label ?></span>
    </span>
        <?php if (strlen($url)): ?>
    </a>
<?php endif; ?>
</div>
