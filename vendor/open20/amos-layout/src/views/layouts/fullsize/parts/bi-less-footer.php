<?php
/* versione piattaforma */
$versione = Yii::t('amoscore', 'Versione') . ' ' . ((isset(\Yii::$app->params['versione'])) ? \Yii::$app->params['versione'] : '1.0');

use luya\cms\models\NavContainer;
use luya\cms\models\NavItemPage;
use yii\helpers\Html;

$container = NavContainer::findOne(['alias' => "footercms"]);
$cms_content = "";
if(!is_null($container)){
    $navs = $container->navs;
    $nav = reset($navs);
    if(!is_null($nav)){
        $page = NavItemPage::findOne(['nav_item_id' => $nav->id]);
        $cms_content = $page->getContent();
    }
}
?>
<footer id="footerContent" class="it-footer mt-auto w-100">
  <div class="it-footer-main py-1 py-md-4 bg-tertiary">
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
                <div class="col-xs-12">
                  <div class="link-list-wrapper">
                    <?= $cmsFooterMenu ?>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="col-md-1">
          <?php if ($showSocial) : ?>
            <div class="py-1 py-md-4">
              <?php
              echo $this->render(
                "bi-less-footer-social",
                [
                  'currentAsset' => $currentAsset,
                ]
              );
              ?>
            </div>
          <?php endif ?>
        </div>
      </div>
      <?= $cms_content ?>
      <div role="contentinfo" aria-label="Copyleft" class="py-1 py-md-4">
      <hr class="border-white">
        <div class="row">
          <div class="col-lg-12">
            <p class="white-color" dir="ltr"><small> <?= $customCopyleftFooter ?> </small></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php
if ($socialModule = \Yii::$app->getModule('social') && class_exists('\kartik\social\GoogleAnalytics')) :
  if (YII_ENV_PROD && !empty($socialModule->googleAnalytics)) :
    echo \kartik\social\GoogleAnalytics::widget([]);
  endif;
endif;
?>