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
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\PublishedContentsWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;

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

$url = '/img/img_default.jpg';
if (!is_null($model->communityLogo)) {
    $url = $model->communityLogo->getUrl('original', false, true);
}
$moduleTag = Yii::$app->getModule('tag');
$showTags = isset($moduleTag) && in_array(Community::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors;
$viewUrl = '/community/community/view?id=' . $model->id;
?>

<div class="details_card">
    <div class="profile">
        <div class="col-sm-4 col-md-3 col-xs-12 left-column">
            <div class="img-profile">
                <img class="img-responsive" src="<?= $url ?>" alt="<?= $model->name ?>">
                <div class="under-img">
                    <?= \open20\amos\community\widgets\JoinCommunityWidget::widget(['model' => $model, 'isProfileView' => true]) ?>
                    <h2><?= $model->name ?></h2>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-9 col-xs-12 right-column">
            <?php if ($viewTabContents): ?>
                <?php
                ////////////////////////////*********************************** BEGIN TAB CONTENTS ***********************************////////////////////////////
                $this->beginBlock($idTabContents);
                ?>
                <div class="body">
                    <div class="intestazione-box">
                        <?php // echo Html::tag('h3', AmosCommunity::tHtml('amoscommunity', 'Contents')) ?>
                    </div>

                    <?php
                    if (!empty($modelsEnabled)):
                        foreach ($modelsEnabled as $modelEnabled):
                            // Exclusion difined in configuration array
                            if (!in_array($modelEnabled, $hideContentsModels)): ?>
                                <?php
                                try {
                                    echo PublishedContentsWidget::widget([
                                        'modelClass' => $modelEnabled,
                                        'scope' => ['community' => $model->id]
                                    ]);
                                } catch (Exception $e) {
                                    // DO NOTHING...
                                }
                                ?>
                            <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </div>
                <?php
                $this->endBlock();
                ////////////////////////////************************************ END TAB CONTENTS ************************************////////////////////////////
                ?>
            <?php endif; ?>
            <?php
            ////////////////////////////********************************* INIZIO TAB PARTICIPANTS *********************************////////////////////////////
            $this->beginBlock($idTabParticipants);
            ?>
            <div class="col-xs-12">
                <div class="intestazione-box">
                    <?php // echo Html::tag('h3', AmosCommunity::tHtml('amoscommunity', 'Participants')) ?>
                </div>

                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/community/community/update?id=" . $model->id . "&tabActive=" . $idTabParticipants,
                    'optionsModify' => [
                        'class' => 'community-modify',
                    ],
                    'actionDelete' => "/community/community/delete?id=" . $model->id,
                    'mainDivClasses' => ''
                ]) ?>

                <?php
                if (!$model->isNewRecord) {
                    echo \open20\amos\core\forms\editors\m2mWidget\M2MWidget::widget([
                        'model' => $model,
                        'modelId' => $model->id,
                        'modelData' => $model->getCommunityUserMms(),
                        'overrideModelDataArr' => true,
                        'forceListRender' => true,
                        'createAssociaButtonsEnabled' => false,
                        'actionColumnsTemplate' => '',  // Vuoto così non ho pulsanti di default in riga
                        'actionColumnsButtons' => [

                        ],
                        'itemsMittente' => [
                            'Photo' => [
                                'headerOptions' => [
                                    'id' => AmosCommunity::t('amoscommunity', 'Photo'),
                                ],
                                'contentOptions' => [
                                    'headers' => AmosCommunity::t('amoscommunity', 'Photo'),
                                ],
                                'label' => AmosCommunity::t('amoscommunity', 'Photo'),
                                'format' => 'raw',
                                'value' => function ($model) {
                                    /** @var \open20\amos\admin\models\UserProfile $userProfile */
                                    $userProfile = $model->user->getProfile();
                                    return \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $userProfile]);
                                }
                            ],
                            'name' => [
                                'attribute' => 'user.userProfile.surnameName',
                                'label' => AmosCommunity::t('amoscommunity', 'Name'),
                                'headerOptions' => [
                                    'id' => AmosCommunity::t('amoscommunity', 'name'),
                                ],
                                'contentOptions' => [
                                    'headers' => AmosCommunity::t('amoscommunity', 'name'),
                                ],
                                'value' => function ($model) {
                                    return Html::a($model->user->userProfile->surnameName, ['/admin/user-profile/view', 'id' => $model->user->userProfile->id], [
                                        'title' => AmosCommunity::t('amoscommunity', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->user->userProfile->surnameName])
                                    ]);
                                },
                                'format' => 'html'
                            ],
                            'status' => [
                                'attribute' => 'status',
                                'label' => AmosCommunity::t('amoscommunity', 'Status'),
                                'headerOptions' => [
                                    'id' => AmosCommunity::t('amoscommunity', 'Status'),
                                ],
                                'contentOptions' => [
                                    'headers' => AmosCommunity::t('amoscommunity', 'Status'),
                                ],
                                'value' => function ($model) {
                                    return $model->status;
                                }
                            ],
                            'role' => [
                                'attribute' => 'role',
                                'label' => AmosCommunity::t('amoscommunity', 'Role'),
                                'headerOptions' => [
                                    'id' => AmosCommunity::t('amoscommunity', 'Role'),
                                ],
                                'contentOptions' => [
                                    'headers' => AmosCommunity::t('amoscommunity', 'Role'),
                                ],
                                'value' => function ($model) {
                                    return $model->role;
                                }
                            ],
                        ],
                    ]);
                }
                ?>
            </div>
            <?php
            $this->endBlock();
            ////////////////////////////********************************** END TAB PARTICIPANTS **********************************////////////////////////////
            ?>
            <?php
            ////////////////////////////********************************** BEGIN TAB SHEET **********************************////////////////////////////
            $this->beginBlock($idTabSheet);
            ?>
            <div class="col-xs-12">
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/community/community/update?id=" . $model->id,
                    'optionsModify' => [
                        'class' => 'community-modify',
                    ],
                    'actionDelete' => "/community/community/delete?id=" . $model->id,
                    'mainDivClasses' => ''
                ]) ?>

                <?= $this->render('boxes/registry', ['model' => $model]) ?>
            </div>
            <?php
            $this->endBlock();
            ////////////////////////////*********************************** END TAB SHEET ***********************************////////////////////////////
            ?>

            <?php if ($showTags): ?>
                <?php
                ////////////////////////////*********************************** BEGIN TAB TAGS ***********************************////////////////////////////
                $this->beginBlock($idTabTags);
                ?>
                <div class="col-xs-12">
                    <div class="intestazione-box">
                        <?php // echo Html::tag('h3', AmosCommunity::tHtml('amoscommunity', 'Tag')) ?>
                    </div>
                    <?= ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/community/community/update?id=" . $model->id . "&tabActive=" . $idTabTags,
                        'optionsModify' => [
                            'class' => 'community-modify',
                        ],
                        'actionDelete' => "/community/community/delete?id=" . $model->id,
                        'mainDivClasses' => 'nom'
                    ]) ?>

                    <?php
                    echo \open20\amos\core\forms\ShowUserTagsWidget::widget([
                        'userProfile' => $model->id,
                        'className' => $model->className()
                    ]);
                    ?>
                </div>
                <?php
                $this->endBlock();
                ////////////////////////////************************************ END TAB TAGS ************************************////////////////////////////
                ?>
            <?php endif; ?>
            <?php
            ////////////////////////////*********************************** BEGIN TAB SUBCOMMUNITIES ***********************************////////////////////////////
            $this->beginBlock($idTabSubcommunities);
            ?>
            <div class="col-xs-12">
                <div class="intestazione-box">
                    <?= Html::tag('p', AmosCommunity::tHtml('amoscommunity', '#subcommunities')) ?>
                </div>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/community/community/update?id=" . $model->id . '&tabActive=' . $idTabSubcommunities,
                    'optionsModify' => [
                        'class' => 'community-modify',
                    ],
                    'actionDelete' => "/community/community/delete?id=" . $model->id,
                    'mainDivClasses' => ''
                ]) ?>
                <?=
                \open20\amos\community\widgets\SubcommunitiesWidget::widget([
                    'model' => $model,
                    'isUpdate' => false
                ]);
                ?>
            </div>
            <?php
            $this->endBlock();
            ////////////////////////////************************************ END TAB SUBCOMMUNITIES ************************************////////////////////////////
            ?>

            <?php
            if ($viewTabContents) {
                $itemsTab[] = [
                    'label' => AmosCommunity::tHtml('amoscommunity', 'Contents'),
                    'content' => $this->blocks[$idTabContents],
                    'options' => ['id' => $idTabContents],
                    'active' => $tabContentsActive
                ];
            }
            $itemsTab[] = [
                'label' => AmosCommunity::tHtml('amoscommunity', 'Participants'),
                'content' => $this->blocks[$idTabParticipants],
                'options' => ['id' => $idTabParticipants],
                'active' => $tabParticipantsActive
            ];
            $itemsTab[] = [
                'label' => AmosCommunity::tHtml('amoscommunity', 'Registry'),
                'content' => $this->blocks[$idTabSheet],
                'options' => ['id' => $idTabSheet],
                'active' => $tabSheetActive
            ];
            if ($showTags) {
                $itemsTab[] = [
                    'label' => AmosCommunity::tHtml('amoscommunity', 'Tag'),
                    'content' => $this->blocks[$idTabTags],
                    'options' => ['id' => $idTabTags],
                    'active' => $tabTagsActive
                ];
            }
            if ($model->getSubcommunities()->count()) {
                $itemsTab[] = [
                    'label' => AmosCommunity::tHtml('amoscommunity', 'Subcommunities'),
                    'content' => $this->blocks[$idTabSubcommunities],
                    'options' => ['id' => $idTabSubcommunities],
                    'active' => $tabSubcommunitiesActive
                ];
            }
            ?>

            <?= Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => $itemsTab,
                ]
            ); ?>
        </div>
    </div>
</div>
