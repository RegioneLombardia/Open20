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
use open20\amos\community\models\CommunityType;
use open20\amos\community\widgets\CommunityPublishedContentsWidget;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\community\widgets\mini\CommunityMembersMiniWidget;
use open20\amos\community\widgets\SubcommunitiesWidget;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\ListTagsWidget;
use open20\amos\core\forms\PublishedContentsWidget;
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
$hideContentsModels = $moduleCommunity->hideContentsModels;

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

<?php if ($model->status != Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) { ?>
    <?= WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => Community::COMMUNITY_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]); ?>
<?php } ?>

<?php  ?>

<div class="community-view">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="header-widget">
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/community/community/update?id=" . $model->id,
                    'optionsModify' => [
                        'class' => 'community-modify',
                    ],
                    'actionDelete' => "/community/community/delete?id=" . $model->id,
                    'mainDivClasses' => ''
                ]) ?>
                <?= CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]) ?>
                <?=
                \open20\amos\report\widgets\ReportFlagWidget::widget([
                    'model' => $model,
                ])
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="header col-xs-12 nop">
                <img class="img-responsive" src="<?= $url ?>" alt="<?= $model->name ?>">
                <div class="title col-xs-12">
                    <h2 class="title-text"><?= $model->name ?></h2>
                </div>
            </div>
            <div class="text-content col-xs-12 nop">
                <?= $model->description; ?>
            </div>
            <div class="widget-body-content col-xs-12 nop">
                <?php echo \open20\amos\report\widgets\ReportDropdownWidget::widget([
                    'model' => $model,
                ])
                ?>
            </div>

            <!-- CONTENT WIDGET -->
            <?php if ($isLoggedUserParticipant) {
                $classDiv = 'community-content-section';
            } else {
                $classDiv = 'community-content-count-section';
            } ?>

            <div class="col-xs-12 nop <?= $classDiv ?>">
                <?php
                if (!empty($modelsEnabled)):
                    foreach ($modelsEnabled as $modelEnabled):
                        // Exclusion difined in configuration array
                        if (!in_array($modelEnabled, $hideContentsModels)): ?>
                            <?php
                            try {
                                if ($isLoggedUserParticipant) {
                                    echo PublishedContentsWidget::widget([
                                        'modelClass' => $modelEnabled,
                                        'scope' => ['community' => $model->id]
                                    ]);
                                } else {
                                    echo CommunityPublishedContentsWidget::widget([
                                        'modelContent' => $modelEnabled,
                                        'modelCommunity' => $model
                                    ]);
                                }
                            } catch (Exception $e) {
                                Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
                            }
                            ?>
                        <?php
                        endif;
                    endforeach;
                    if (!$isLoggedUserParticipant) {
                        echo Html::tag('div',
                            AmosIcons::show('users', [], 'dash') .
                            Html::tag('span', '(' . $model->getCommunityUsers()->count() . ')', ['class' => 'counter']) .
                            Html::tag('span', AmosCommunity::t('amoscommunity', 'Participants'),
                                ['class' => 'model-label']),
                            ['class' => 'content-widget-item']);
                    }
                endif;
                ?>
            </div>


            <?php
            //The user can see subcommunities accordion if the community is OPEN Type or if the user is an active member otherwise
            if (($model->community_type_id == CommunityType::COMMUNITY_TYPE_OPEN) || $isLoggedUserParticipant) {
                ?>
                <div class="col-xs-12 nop">
                    <?= AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => AmosIcons::show('group', [], 'dash') . AmosCommunity::t('amoscommunity',
                                        '#subcommunity_accordion'),
                                'content' => SubcommunitiesWidget::widget([
                                    'model' => $model,
                                    'isUpdate' => false
                                ]),
                            ]
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                            'active' => 'false',
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ],
                        ],
                        'options' => [
                            'class' => 'first-accordion'
                        ]
                    ]); ?>
                </div>
            <?php } ?>


        </div>
        <div class="col-md-4 col-xs-12">
            <div class="typology-section-sidebar col-xs-12 nop">
                <?= Html::tag('h2',
                    AmosIcons::show('key', [], 'dash') . AmosCommunity::t('amoscommunity', '#typology_title')) ?>
                <div class="col-xs-12">
                    <div class="col-xs-6 nop info-label"><?= $model->getAttributeLabel('communityType'); ?></div>
                    <div class="col-xs-6 nop info-value"><?= isset($model->communityType) ? AmosCommunity::t('amoscommunity', $model->communityType->name) : '-' ?></div>
                    <!--                <div class="col-xs-6 nop info-label">--><?php //echo  $model->getAttributeLabel('status'); ?><!--</div>-->
                    <!--                <div class="col-xs-6 nop info-value">-->
                    <?php //echo  $model->hasWorkflowStatus() ? AmosCommunity::t('amoscommunity', $model->getWorkflowStatus()->getLabel()) : '-' ?><!--</div>-->
                    <div class="col-xs-12 nop btn-join-community">
                        <?= JoinCommunityWidget::widget([
                            'model' => $model,
                            'isProfileView' => true
                        ]) ?>
                    </div>
                </div>
            </div>

            <?php if ($isLoggedUserParticipant && $model->hide_participants == 0) { ?>
                <div class="member-section-sidebar col-xs-12 nop" id="section-member">
                    <?= Html::tag('h2', AmosIcons::show('globe') . AmosCommunity::t('amoscommunity', '#members_title')) ?>
                    <div class="col-xs-12">
                        <?= CommunityMembersMiniWidget::widget([
                            'model' => $model,
                            'targetUrlParams' => [
                                'viewM2MWidgetGenericSearch' => true,
                            ],
                            'isUpdate' => false,

                        ]);
                        ?>
                    </div>
                </div>
            <?php } ?>

            <?php if(Yii::$app->user->can('ADMIN') && Yii::$app->getModule('community') && Yii::$app->getModule('community')->enableUserJoinedReportDownload) { ?>
                <div class="member-section-sidebar col-xs-12 nop" id="section-download-reports">
                    <?= Html::tag('h2', AmosIcons::show('download') . ' ' . AmosCommunity::t('amoscommunity', '#download_user_reports')) ?>
                    <div class="col-xs-12 m-b-20">
                        <div style="text-align: center;">
                            <?= Html::a(AmosIcons::show('download') . ' ' . AmosCommunity::t('amoscommunity', '#download_user_joined_report'), ['user-joined-report-download', 'communityId' => $model->id], ['class' => 'btn btn-primary']); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (!empty(\Yii::$app->getModule('tag'))) { ?>
                <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">
                    <?= ListTagsWidget::widget([
                        'userProfile' => $model->id,
                        'className' => $model->className(),
                        'viewFilesCounter' => true,
                        'withTitle' => true
                    ]);
                    ?>
                </div>
            <?php } ?>

        </div>
    </div>
</div>
