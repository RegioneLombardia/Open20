<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */
use open20\amos\partnershipprofiles\Module;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\partnershipprofiles\assets\PartnershipProfilesAsset;

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\partnershipprofiles\widgets\graphics\WidgetGraphicsLatestPartnershipProfiles $widget
 * @var string $toRefreshSectionId
 */
PartnershipProfilesAsset::register($this);
$modulePartenershipProfiles = \Yii::$app->getModule(Module::getModuleName());
?>
<div class="grid-item grid-item--height2">
  <div class="box-widget latest-partnership-profiles">
    <div class="box-widget-toolbar">
      <h2 class="box-widget-title col-xs-10 nop"><?= Module::tHtml('Module', 'Ultime Proposte di collaborazione') ?></h2>
      <?php
      if (isset($modulePartenershipProfiles) && !$modulePartenershipProfiles->hideWidgetGraphicsActions) {
        echo WidgetGraphicsActions::widget([
          'widget' => $widget,
          'tClassName' => Module::className(),
          'actionRoute' => '/partnershipprofiles/partnership-profiles/create',
          'toRefreshSectionId' => $toRefreshSectionId
        ]);
      }
      ?>
    </div>
    <section>
    <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
      <div role="listbox">
      <?php
      $listaPartnership = $listaPartnership->getModels();
      if (count($listaPartnership) == 0):
        $textReadAll = Module::t('Module', 'Aggiungi Proposta di collaborazione');
        $linkReadAll = ['/partnershipprofiles/partnership-profiles/create'];
      ?>

        <div class="list-items list-empty clearfixplus">
          <h2 class="box-widget-subtitle"><?= Module::tHtml('Module', 'Nessuna Proposta di collaborazione'); ?></h2>
        </div>
        
      <?php else:
        $textReadAll = Module::t('Module', 'Visualizza Proposte di collaborazione');
        $linkReadAll = ['/partnershipprofiles'];
      ?>
          
        <div class="list-items clearfixplus">
        <?php
        foreach ($listaPartnership as $partnership):
          /** @var \open20\amos\partnershipprofiles\models\PartnershipProfiles $partnership */
        ?>
          <div class="col-xs-12 widget-listbox-option" role="option">
            <article class="col-xs-12 nop">
              <div class="container-text col-xs-12 nop">
              <?= \open20\amos\notificationmanager\forms\NewsWidget::widget(['model' => $partnership]); ?>
              <?=
                PublishedByWidget::widget([
                  'model' => $partnership,
                  'layout' => '{publisher}'
                ]);
              ?>

                <h2 class="col-xs-12 nop box-widget-subtitle">
                <?php
                  if (strlen($partnership->title) > 100) {
                    $stringCut = substr($partnership->title, 0, 100);
                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                } else {
                  echo $partnership->title;
                }
                ?>
                </h2>
              </div>

              <div class="col-xs-12 footer-listbox nop">
              <?php
              $module = \Yii::$app->getModule('partnershipprofiles');
              $moduleCwh = \Yii::$app->getModule('cwh');
              $communityConfigurationsId = null;
              if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                $scope = $moduleCwh->getCwhScope();
                if (isset($scope['community'])) {
                  $communityConfigurationsId = 'communityId-' . $scope['community'];
                }
              }

              $enabledFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']) 
                ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'] 
                : (!empty($module->fieldsConfigurations['fields']) 
                  ? $module->fieldsConfigurations['fields'] 
                  : []);
              
              if (!empty($enabledFields['expiration_in_months']) && $enabledFields['expiration_in_months'] == true) { ?>
                <span class="pull-left"><strong><?= Module::t('amospartnershipprofiles', 'data scadenza: ') ?><?= $partnership->expiredDate ?></strong></span>
              <?php } ?>
              
                <span class="pull-right">
                  <?= Html::a(Module::t('amospartnershipprofiles', 'LEGGI'), ['../partnershipprofiles/partnership-profiles/view', 'id' => $partnership->id], ['class' => 'btn btn-navigation-primary']); ?>
                </span>
              </div>

              <div class="clearfix"></div>
            </article>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php Pjax::end(); ?>
    </section>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => '']); ?></div>
  </div>
</div>