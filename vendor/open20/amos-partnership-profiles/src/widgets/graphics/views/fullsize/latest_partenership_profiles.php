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
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\forms\PublishedByWidget;

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\partnershipprofiles\widgets\graphics\WidgetGraphicsLatestPartnershipProfiles $widget
 * @var string $toRefreshSectionId
 */
\open20\amos\partnershipprofiles\assets\PartnershipProfilesAsset::register($this);
$modulePartenershipProfiles = \Yii::$app->getModule(Module::getModuleName());
?>
<div class="box-widget-header">
    <?php if (isset($modulePartenershipProfiles) && !$modulePartenershipProfiles->hideWidgetGraphicsActions) { ?>
        <?=
        WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => Module::className(),
            'actionRoute' => '/partnershipprofiles/partnership-profiles/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
        ?>
    <?php } ?>
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('propostecollaborazione', ['class' => 'am-2'], AmosIcons::IC) ?>
            <?= Module::tHtml('amospartnershipprofiles', 'Ultime Proposte di collaborazione') ?>
        </h2>
    </div>

    <?php
    if (count($listaPartnership->getModels()) == 0) {
        $textReadAll = Module::t('amospartnershipprofiles', 'Aggiungi Proposta di collaborazione');
        $linkReadAll = ['/partnershipprofiles/partnership-profiles/create'];
    } else {
        $textReadAll = Module::t('amospartnershipprofiles', '#showAll').AmosIcons::show('chevron-right');
        $linkReadAll = ['/partnershipprofiles'];
    }
    ?>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => '']); ?></div>
</div>
<div class="box-widget box-widget-column latest-partnership-profiles">
    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <?php if (count($listaPartnership->getModels()) == 0): ?>
            <div class="list-items list-empty">
                <h3><?= Module::tHtml('Module', 'Nessuna Proposta di collaborazione'); ?></h3></div>
        <?php endif; ?>
        <div class="list-items">
            <?php
            foreach ($listaPartnership->getModels() as $partnership):
                /** @var \open20\amos\partnershipprofiles\models\PartnershipProfiles $partnership */
                ?>
                <div class="widget-listbox-option" role="option">
                    <article class="wrap-item-box wrap-item-box-row">
                        <div class="box-widget-info-top">
                            <div class="container-img avatar">
                                <?= Html::img($partnership->createdUserProfile->getAvatarUrl('dashboard_partnership')) ?>
                            </div>
                            <?=
                            PublishedByWidget::widget([
                                'model' => $partnership,
                                'layout' => '{publisher}'
                            ])
                            ?>
                            <?= \open20\amos\notificationmanager\forms\NewsWidget::widget([
                                'model' => $partnership]); ?>
                        </div>
                        <div class="container-text">
                            <h2 class="box-widget-subtitle">
                                <?php
                                if (strlen($partnership->title) > 200) {
                                    $stringCut = substr($partnership->title, 0, 200);
                                    $stringCut = substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                } else {
                                    $stringCut = $partnership->title;
                                }
                                ?>

                                <?=
                                Html::a($stringCut,
                                    ['../partnershipprofiles/partnership-profiles/view', 'id' => $partnership->id]);
                                ?>
                            </h2>

                            <div class="box-widget-text">

                                <?php
                                $stringNoTags = $partnership->short_description;
                                //remove table from editor
                                //$stringNoTags = preg_replace('/<table(.*?)>(.*?)<\/table>/s', '', $stringNoTags);
                                $stringNoTags = preg_replace('/<table(.*$)/s', '', $stringNoTags);
                                // remove iframe from editor
                                //$stringNoTags = preg_replace('/<iframe(.*?)>(.*?)<\/iframe>/s', '', $stringNoTags);
                                $stringNoTags = preg_replace('/<iframe(.*$)/s', '', $stringNoTags);
                                // remove images from editor
                                //$stringNoTags = preg_replace('/<img(.*?)\/>/s', '', $stringNoTags);
                                $stringNoTags = preg_replace('/<p><img(.*$)/s', '', $stringNoTags);
                                // remove empty paragraph
                                $stringNoTags = preg_replace('/<p><\/p>/s', '', $stringNoTags);
                                // remove &nbsp; space
                                $stringNoTags = str_replace('&nbsp;', '', $stringNoTags);
                                if (strlen($stringNoTags) > 120) {
                                    $stringCut = substr(strip_tags($stringNoTags), 0, 120);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                } else {
                                    echo $stringNoTags;
                                }
                                ?>
                            </div>

                            <div class="box-widget-info-bottom">
                                <?php
                                $module                    = \Yii::$app->getModule('partnershipprofiles');
                                $moduleCwh                 = \Yii::$app->getModule('cwh');
                                $communityConfigurationsId = null;
                                if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                                    $scope = $moduleCwh->getCwhScope();
                                    if (isset($scope['community'])) {
                                        $communityConfigurationsId = 'communityId-'.$scope['community'];
                                    }
                                }
                                $enabledFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'])
                                        ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'] : (!empty($module->fieldsConfigurations['fields'])
                                        ? $module->fieldsConfigurations['fields'] : []);
                                if (!empty($enabledFields['expiration_in_months']) && $enabledFields['expiration_in_months']
                                    == true) {
                                    ?>
                                    <span class="pull-left"><strong><?= Module::t('amospartnershipprofiles', 'data scadenza: ') ?><?= $partnership->expiredDate ?></strong></span>
    <?php } ?>
                            </div>
                        </div>
                    </article>
                </div>
                <?php
            endforeach;
            ?>
        </div>
<?php Pjax::end(); ?>
    </section>
</div>