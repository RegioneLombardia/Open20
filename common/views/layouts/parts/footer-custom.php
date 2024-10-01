<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use luya\cms\models\NavContainer;
use luya\cms\models\NavItemPage;
use luya\cms\models\NavItem;
use yii\helpers\Html;

//Pickup assistance params
$assistance = isset(\Yii::$app->params['assistance']) ? \Yii::$app->params['assistance'] : [];

//Check if is in email mode
$isMail = ((isset($assistance['type']) && $assistance['type'] == 'email') || (!isset($assistance['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
$mailAddress = isset($assistance['email']) ? $assistance['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');

/** @var \open20\amos\admin\AmosAdmin $moduleAdmin */
$moduleAdmin = Yii::$app->getModule('amosadmin');
$canViewOnlineUsers = !Yii::$app->user->isGuest && $moduleAdmin && $moduleAdmin->enableOnlineUsers && (Yii::$app->user->can('CAPOREDATTORECMS') || Yii::$app->user->can('CMS_PAGE_EDITOR'));

$canviewBookmarks = !Yii::$app->user->isGuest && (Yii::$app->user->can('CAPOREDATTORECMS') || Yii::$app->user->can('CMS_PAGE_EDITOR'));

$container = NavContainer::findOne(['alias' => 'footercms']);
$cms_content = "";
if(!is_null($container)){
    $navs = $container->navs;
    $nav = reset($navs);
        if(!is_null($nav)){
            $item = NavItem::findOne(['nav_id' => $nav->id]);
            if(!empty($item)){
                $page = NavItemPage::findOne(['nav_item_id' => $item->id]);
                $cms_content = $page->getContent();
            }         
        } 
}
?>


<footer id="footerContent" class="it-footer w-100 mt-auto <?=($existSidebarRedattore) ? 'z-index-3 position-relative' : 'z-index-1'?>">
    <div class="it-footer-main py-4 bg-tertiary">
        <div class="container noprint">
            <div class="row">
                <div class="col-md-11">
                    <div class="noprint container-title">
                        <div role="contentinfo" aria-label="Titolo del footer" class="py-1 pt-3 py-md-4">
                            <p dir="ltr" class="equal-font-size-h5 footer-title"><strong><?= Html::encode(Yii::$app->name) ?></strong></p>
                        </div>
                    </div>
                    <div class="py-1 py-md-4">
                        <div class="row">
                            <?php if (isset($cmsFooterMenu)) { ?>
                                <div class="col-12">
                                    <div class="link-list-wrapper">
                                        <?= $cmsFooterMenu ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="py-1 py-md-4">
                        <?php if ($showSocial) : ?>
                            <?php
                            echo $this->render("bi-footer-social", [
                                'currentAsset' => $currentAsset,
                            ]);
                            ?>
                        <?php endif ?>
                    </div>
                </div>

            </div>
            <?= $cms_content ?>
            <div role="contentinfo" aria-label="Copyleft" class="py-1 py-md-4">
                <hr class="border-white">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="white-color" dir="ltr"><small><?= $customCopyleftFooter ?></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    <?php if ($canViewOnlineUsers) { ?>
        function toggleOnlineUserDialog() {
            document.getElementById("onlineUserDialog").classList.toggle("active");
        }
        ;
    <?php } ?>
	
    <?php if (!$canviewBookmarks) {  ?>
            var x = document.getElementsByClassName("container-favourites");
            x[0].style.visibility = "hidden";
            var y = document.getElementsByClassName("btn-modify-cms-page");
            y[0].style.visibility = "hidden";
    <?php } ?>

    function copyUrl() {
        var url = window.location.href;
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(url).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Url della pagina attuale copiato negli appunti!');

    }
    ;
</script>



<div class="social-share">
    <ul class="social-share-buttons">
        
        
        <li class="mailto-share">
            <a href="<?= $mail_sharing_link ?>" target="_blank" title="<?= Yii::t('amosplatform', 'Condividi la pagina per mail') ?>">
                <span class="text-white mdi mdi-mail"></span>            
            </a>
        </li>
        <li class="copylink-share">

            <a href="#" role="button" onclick="copyUrl()">
                <span class="text-white mdi mdi-content-copy"></span>            
            </a>


        </li>

        <!-- Online users -->
        <?php if ($canViewOnlineUsers) { ?>
            <li class="online-user">
            
            

                <a class="online-users-btn" href="#" title="Utenti Online" role="button" onclick="toggleOnlineUserDialog()">
                    <span class="text-white mdi mdi-account-multiple-outline"></span>

                </a>

            </li>
        <?php } ?>

    </ul>
</div>

<?php if (isset(\Yii::$app->params['assistance-url'])): ?>
    <div class="assistance">
        <a href="<?= \Yii::$app->params['assistance-url'] ?>">
            <?= AmosIcons::show('email', ['class' => 'icon-assistance']) ?>
            <span><?= Yii::t('amoscore', 'Hai bisogno di assistenza?') ?></span>
            <span class="sr-only"><?= Yii::t('amoscore', 'Verrà aperta una nuova finestra') ?></span>
        </a>
    </div>
<?php elseif (isset(Yii::$app->modules['assistance-request'])): ?>
    <?= $this->renderFile('@vendor/open20/amos-assistance-request/src/views/_modal_form_request.php'); ?>
<?php else: ?>
    <?php if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))): ?>
        <div class="assistance">
            <a href="<?= $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : '') ?>">
                <?= AmosIcons::show('email', ['class' => 'icon-assistance']) ?>
                <span><?= Yii::t('amoscore', 'Hai bisogno di assistenza?'); ?></span>
                <span class="sr-only"><?= Yii::t('amoscore', 'Verrà aperta una nuova finestra') ?></span>
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?= $this->render("footer_text"); ?>

<?php if ($canViewOnlineUsers) { ?>
 
    <?= $this->render('_onlineUsersDialog', [
        'currentAsset' => $currentAsset,
        'moduleAdmin' => $moduleAdmin,
    ]); ?>
<?php } ?>

<?php
if ($socialModule = \Yii::$app->getModule('social') && class_exists('\kartik\social\GoogleAnalytics')):
    if (YII_ENV_PROD && !empty($socialModule->googleAnalytics)):
        echo \kartik\social\GoogleAnalytics::widget([]);
    endif;
endif;
?>