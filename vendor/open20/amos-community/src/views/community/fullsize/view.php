<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\widgets\CommunityPublishedContentsWidget;
use open20\amos\core\forms\ListTagsWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use yii\log\Logger;

/**
 * @var yii\web\View $this
 * @var \open20\amos\community\models\Community $model
 * @var string $tabActive
 */
$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$idTabSheet = 'tab-registry';
$idTabContents = 'tab-contents';
$idTabParticipants = 'tab-participants';
$idTabTags = 'tab-tags';
$idTabProjects = 'tab-projects';
$idTabSubcommunities = 'tab-subcommunities';

$modelsEnabled = Yii::$app->getModule('cwh')->modelsEnabled;
/** @var AmosCommunity $moduleCommunity */
$moduleCommunity = Yii::$app->getModule('community');
$viewTabContents = $moduleCommunity->viewTabContents;

$customHideContentsModels = Yii::$app->getModule('hideContentsModels');
if (!empty($customHideContentsModels)) {
    $hideContentsModels = array_merge($moduleCommunity->hideContentsModels, $customHideContentsModels);
} else {
    $hideContentsModels = $moduleCommunity->hideContentsModels;
}

//check if a specific tab must be the active one
$tabSheetActive = isset($tabActive) ? ((strcmp($tabActive, $idTabSheet) == 0) ? true : false) : false;
$tabContentsActive = isset($tabActive) ? ((strcmp($tabActive, $idTabContents) == 0) ? true : false) : false;
$tabParticipantsActive = isset($tabActive) ? ((strcmp($tabActive, $idTabParticipants) == 0) ? true : false) : false;
$tabTagsActive = isset($tabActive) ? ((strcmp($tabActive, $idTabTags) == 0) ? true : false) : false;
$tabProjectsActive = isset($tabActive) ? ((strcmp($tabActive, $idTabProjects) == 0) ? true : false) : false;
$tabSubcommunitiesActive = isset($tabActive) ? ((strcmp($tabActive, $idTabSubcommunities) == 0) ? true : false) : false;

$url = $model->getAvatarUrl('original');

$isLoggedUserParticipant = $model->isNetworkUser();
?>

<?php /*if ($model->status != Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) { */ ?>
<!--
    <?/*= WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => Community::COMMUNITY_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]); */ ?>
--><?php /*} */ ?>


<div class="community-view">

    <?php if (Yii::$app->user->can('ADMIN') && Yii::$app->getModule('community') && Yii::$app->getModule('community')->enableUserJoinedReportDownload) { ?>
        <div class="community-description">
            <div class="container-custom">
                <div class="community-download-report col-xs-12 nop text-right">
                    <?= Html::a(AmosIcons::show('download') . ' ' . AmosCommunity::t('amoscommunity', '#download_user_joined_report'), ['user-joined-report-download', 'communityId' => $model->id], ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        </div>
    <?php } ?>
 

    <div id="bk-pluginGrafici" class="sub-dashboard-graphics wrap-graphic-widget">
        <div id="widgets-graphic" class="community-view-content">

            <div class="box-widget box-widget-column">
                <section class="col-xs-12">
                    <h2 class="title-text"><?= AmosCommunity::t('amoscommunity', '#view_title_plugins') ?></h2>

                    <div class="col-xs-12 community-content-count-section">
                        <?php
                        if (!empty($modelsEnabled)) :
                            foreach ($modelsEnabled as $modelEnabled) :
                                // Exclusion difined in configuration array
                                if (!in_array($modelEnabled, $hideContentsModels)) : ?>
                                    <?php
                                    try {
                                        echo CommunityPublishedContentsWidget::widget([
                                            'modelContent' => $modelEnabled,
                                            'modelCommunity' => $model
                                        ]);
                                    } catch (Exception $e) {
                                        Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
                                    }
                                    ?>
                            <?php
                                endif;
                            endforeach;
                            ?>
                            <div class="content-widget-item">
                                <?= AmosIcons::show('user', [], AmosIcons::IC) ?>
                                <span class="counter">
                                    <?= $model->getCommunityUsers()->count() ?>
                                </span>
                                <span class="model-label">
                                    <?= AmosCommunity::t('amoscommunity', 'Participants') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="box-widget box-widget-column">
                <section class="col-xs-12">
                    <?php if (!empty(\Yii::$app->getModule('tag'))) { ?>
                        <div class="new-graphic community-tags" id="section-tags">
                            <h2 class="title-text"><?= AmosCommunity::t('amoscommunity', '#view_title_tags') ?></h2>
                            <?= ListTagsWidget::widget([
                                'userProfile' => $model->id,
                                'className' => $model->className(),
                                'viewFilesCounter' => true,
                                'withTitle' => false,
                                'layout' => '@vendor/open20/amos-core/forms/views/widgets/widget_list_tags_fullsize.php',
                            ]); ?>
                        </div>
                    <?php } ?>
                </section>
                </section>
            </div>

        </div>
    </div>

</div>