<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\views\collaboration-proposals
 * @category   CategoryName
 */

use amos\sitemanagement\models\SiteManagementSliderElem;
use open20\amos\attachments\components\AttachmentsTableWithPreview;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\WorkflowTransitionWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\collaborations\controllers\CollaborationProposalsController;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\Module;
use open20\amos\collaborations\utility\CollaborationProposalsUtility;
use yii\web\View;
use yii\widgets\DetailView;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use open20\amos\admin\models\UserProfile;
use yii\helpers\Url;
use open20\amos\collaborations\models\CollaborationType;
use open20\amos\collaborations\models\CollaborationStageOfDevelopment;
use open20\amos\collaborations\models\CollaborationIprStatusMm;
use open20\amos\collaborations\models\CollaborationPartnershipTypeMm;
use open20\amos\collaborations\models\CollaborationPartnerTypeAndSizeMm;
use open20\amos\collaborations\models\CollaborationMarketKeywordsMm;
use open20\amos\collaborations\models\CollaborationTechnologyKeywordsMm;
use open20\amos\collaborations\models\CollaborationTargetCountriesMm;

/**
 * @var yii\web\View $this
 * @var open20\amos\collaborations\models\CollaborationProposals $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('amoscollaborations', 'Proposte di collaborazione'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'),  'url' => ['/collaborations/collaboration-proposals/own-interest']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];

$exprCount = CollaborationExpressionsOfInterest::find()->andWhere(['collaboration_proposal_id' => $model->id])->count();

$appController = Yii::$app->controller;

// Tab ids
$idTabCard = 'tab-card';
$idClassifications = 'tab-classifications';
$idTabMoreInformation = 'tab-more-information';
$idTabAttachments = 'tab-attachments';

$moreInformationLinkId = "more-information-link-id";
$moreInformationBlockId = "more-information-block-id";
$lessInformationLinkId = "less-information-block-id";


$module = \Yii::$app->getModule('collaborations');
$moduleCwh = \Yii::$app->getModule('cwh');
$communityConfigurationsId = null;
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    if (isset($scope['community'])) {
        $communityConfigurationsId = 'communityId-' . $scope['community'];
    }
}

$enabledFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'] : (!empty($module->fieldsConfigurations['fields']) ? $module->fieldsConfigurations['fields'] : []);
$enabledTabs = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['tabs']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['tabs'] : (!empty($module->fieldsConfigurations['tabs']) ? $module->fieldsConfigurations['tabs'] : []);

$isArchived = CollaborationProposalsUtility::isArchived($model->id);
$proposalAuthor = UserProfile::findOne(['user_id' => $model->created_by])->nome . ' ' . UserProfile::findOne(['user_id' => $model->created_by])->cognome;

$exprDoneByMe = CollaborationExpressionsOfInterest::find()
    ->andWhere(['collaboration_proposal_id' => $model->id])
    ->andWhere(['created_by' => Yii::$app->user->id])
    ->count();

// MM TABLES SELECTED VALUES
// IPR STATUS
$ipr_statuses_query = CollaborationIprStatusMm::find()
    ->innerJoin('collaboration_ipr_status', 'collaboration_ipr_status.id = collaboration_ipr_status_mm.collaboration_ipr_status_id')
    ->innerJoin('collaboration_proposals', 'collaboration_proposals.id = collaboration_ipr_status_mm.collaboration_ipr_status_id')
    ->andWhere(['collaboration_ipr_status_mm.collaboration_proposals_id' => $model->id])
    ->select('collaboration_ipr_status.name')->asArray()->all();
foreach ($ipr_statuses_query as $array) {
    foreach ($array as $key => $name) {
        $ipr_statues[] = $name;
    }
}
$ipr_statues = implode(' - ', $ipr_statues);
// PARTNERSHIP TYPE
$partnership_types_query = CollaborationPartnershipTypeMm::find()
    ->innerJoin('collaboration_partnership_type', 'collaboration_partnership_type.id = collaboration_partnership_type_mm.collaboration_partnership_type_id')
    ->innerJoin('collaboration_proposals', 'collaboration_proposals.id = collaboration_partnership_type_mm.collaboration_partnership_type_id')
    ->andWhere(['collaboration_partnership_type_mm.collaboration_proposals_id' => $model->id])
    ->select('collaboration_partnership_type.name')->asArray()->all();
foreach ($partnership_types_query as $array) {
    foreach ($array as $key => $name) {
        $partnership_types[] = $name;
    }
}
$partnership_types = implode(' - ', $partnership_types);
// PARTNER TYPE AND SIZE
$partner_type_and_size_query = CollaborationPartnerTypeAndSizeMm::find()
    ->innerJoin('collaboration_partner_type_and_size', 'collaboration_partner_type_and_size.id = collaboration_partner_type_and_size_mm.collaboration_partner_type_and_size_id')
    ->innerJoin('collaboration_proposals', 'collaboration_proposals.id = collaboration_partner_type_and_size_mm.collaboration_partner_type_and_size_id')
    ->andWhere(['collaboration_partner_type_and_size_mm.collaboration_proposals_id' => $model->id])
    ->select('collaboration_partner_type_and_size.name')->asArray()->all();
foreach ($partner_type_and_size_query as $array) {
    foreach ($array as $key => $name) {
        $partner_type_and_size[] = $name;
    }
}
$partner_type_and_size = implode(' - ', $partner_type_and_size);
// TARGET COUNTRIES
$target_countries_query = CollaborationTargetCountriesMm::find()
    ->innerJoin('collaboration_target_countries', 'collaboration_target_countries.id = collaboration_target_countries_mm.collaboration_target_countries_id')
    ->innerJoin('collaboration_proposals', 'collaboration_proposals.id = collaboration_target_countries_mm.collaboration_target_countries_id')
    ->andWhere(['collaboration_target_countries_mm.collaboration_proposals_id' => $model->id])
    ->select('collaboration_target_countries.name')->asArray()->all();
foreach ($target_countries_query as $array) {
    foreach ($array as $key => $name) {
        $target_countries[] = $name;
    }
}
$target_countries = implode(' - ', $target_countries);
// MARKET KEYWORDS
/*$market_keywords_query = CollaborationMarketKeywordsMm::find()
    ->innerJoin('collaboration_market_keywords', 'collaboration_market_keywords.id = collaboration_market_keywords_mm.collaboration_market_keywords_id')
    ->innerJoin('collaboration_proposals', 'collaboration_proposals.id = collaboration_market_keywords_mm.collaboration_market_keywords_id')
    ->andWhere(['collaboration_market_keywords_mm.collaboration_proposals_id' => $model->id])
    ->select('collaboration_market_keywords.description')->asArray()->all();
foreach ($market_keywords_query as $array) {
    foreach ($array as $key => $name) {
        $market_keywords[] = $name;
    }
}
$market_keywords = implode(' - ', $market_keywords);*/
// TECHNOLOGY KEYWORDS
/*$tech_keywords_query = CollaborationTechnologyKeywordsMm::find()
    ->innerJoin('collaboration_technology_keywords', 'collaboration_technology_keywords.id = collaboration_technology_keywords_mm.collaboration_technology_keywords_id')
    ->innerJoin('collaboration_proposals', 'collaboration_proposals.id = collaboration_technology_keywords_mm.collaboration_technology_keywords_id')
    ->andWhere(['collaboration_technology_keywords_mm.collaboration_proposals_id' => $model->id])
    ->select('collaboration_technology_keywords.description')->asArray()->all();
foreach ($tech_keywords_query as $array) {
    foreach ($array as $key => $name) {
        $tech_keywords[] = $name;
    }
}
$tech_keywords = implode(' - ', $tech_keywords);*/

?>

<div class="<?= Yii::$app->controller->id ?>-view post-details">

    <?php $this->beginBlock($idTabCard); ?>
    <div class="view-header pull-right m-b-10">
        <!--< ?= ItemAndCardHeaderWidget::widget([
            'model' => $model,
            'publicationDateField' => 'created_at'
        ]); ?>-->
        <?= ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => ['update', 'id' => $model->id],
            'disableModify' => !($model->userCanUpdate()),
            'actionDelete' => ['delete', 'id' => $model->id],
            'disableDelete' => !($model->userCanDelete()),
        ]) ?>
    </div>

    <div class="">
        <div class="post-content">


            <div class="clearfix"></div>
            <div class="post-info">
                <div class="row">
                    <div class="col-md-6">
                        <!--<div class="item">
                            <label><//?= \Yii::t('amoscollaborations', 'Categoria') ?></label>: <//?= $model->getCategoryLabel()[$model->category_id] ?>
                        </div>-->
                        <!--< ?= PublishedByWidget::widget([
                            'model' => $model,
                            'layout' => '{targetAdv}{statusTranslated}',
                            'renderSections' => [
                                '{statusTranslated}' => function ($model) use ($isArchived) {
                                    return Html::tag(
                                        'strong',
                                        \Yii::t('amoscore', 'Status')
                                    ) . ': ' . ($isArchived ? \Yii::t('amoscollaborations', 'Archiviata') : $model->getWorkflowStatusLabel()[$model->status]);
                                }
                            ]
                        ]) ?>-->
                        <div class="published-by">
                            <!-- <strong>< ?= \Yii::t('amoscollaborations', 'ID EEN') . ': ' ?></strong>< ?= $model->id_een ?> -->

                            <!--<div class="item">
                                <label>< ?= \Yii::t('amoscollaborations', 'Data della proposta') ?></label>: < ?= date_format(date_create($model->start_date), 'd/m/Y') ?>
                            </div>-->

                            <div class="item">
                                <label><?= \Yii::t('amoscollaborations', 'Data di scadenza') ?></label>: <?= date_format(date_create($model->end_date), 'd/m/Y') ?>
                            </div>

                            <h4><span class="label label-default"><?= \Yii::t('amoscollaborations', CollaborationType::findOne($model->collaboration_type_id)->name) ?></span></h4>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">

                        <!--<div>-->
                        <?php //$exprCount = CollaborationExpressionsOfInterest::find()->andWhere(['collaboration_proposal_id' => $model->id])->count() 
                        ?>
                        <!--<strong>< ?= $exprCount == 1 ? \Yii::t('amoscollaborations', 'Manifestazione di interesse') : \Yii::t('amoscollaborations', 'Manifestazioni di interesse') ?></strong>:-->
                        <? //= $exprCount 
                        ?>
                        <!--</div>-->
                        <?php if (!Yii::$app->user->isGuest) { ?>

                            <?php

                            if ($exprDoneByMe == 0 && Yii::$app->user->id != $model->created_by) {
                                if (!$isArchived && $model->status != CollaborationProposals::STATUS_CLOSED) {
                                    echo Html::a(
                                        \Yii::t('amoscollaborations', 'Manifesta interesse'),
                                        ['collaboration-expressions-of-interest/create', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-primary'
                                        ]
                                    );
                                }
                            } else if ($exprDoneByMe > 0 && Yii::$app->user->id != $model->created_by) { ?>
                                <p class="text-muted">
                                    <?= \Yii::t('amoscollaborations', 'Hai già manifestato interesse per questa proposta'); ?>
                                </p>
                            <?php
                                echo Html::a(
                                    \Yii::t('amoscollaborations', 'Vedi la tua manifestazione di interesse'),
                                    [
                                        'collaboration-expressions-of-interest/view',
                                        'id' => CollaborationExpressionsOfInterest::findOne(['collaboration_proposal_id' => $model->id, 'created_by' => Yii::$app->user->id])->id
                                    ],
                                    [
                                        'class' => 'btn btn-outline-primary m-t-5',
                                        'title' => \Yii::t('amoscollaborations', 'Vedi la tua manifestazione di interesse')
                                    ]
                                );
                            } else if (Yii::$app->user->id == $model->created_by) {
                                if ($model->status == CollaborationProposals::STATUS_PUBLISHED || $model->status == CollaborationProposals::STATUS_CLOSED) {
                                    echo Html::a(
                                        \Yii::t('amoscollaborations', 'Vedi le manifestazioni di interesse ricevute'),
                                        [
                                            'collaboration-expressions-of-interest/received-for-own-proposal',
                                            'id' => $model->id
                                        ],
                                        [
                                            'class' => 'btn btn-outline-primary m-t-5',
                                            'title' => \Yii::t('amoscollaborations', 'Vedi le manifestazioni di interesse ricevute per questa proposta')
                                        ]
                                    );
                                }
                            } ?>
                            <?php } else {
                            if (!$isArchived && $model->status != CollaborationProposals::STATUS_CLOSED) {
                                $module = \Yii::$app->getModule(Module::getModuleName());
                                if ($module && !empty($module->modalExpressionOfInterest)) {

                                    echo Html::a(
                                        \Yii::t('amoscollaborations', 'Manifesta interesse'),
                                        ['#expression-of-interest-modal', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-primary',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#expression-of-interest-modal-' . $model->id,
                                            'title' => \Yii::t('amoscollaborations', 'Manifesta interesse'),
                                        ]
                                    ); ?>

                                    <!-- Modal -->
                                    <div class="modal fade" id="expression-of-interest-modal-<?= $model->id ?>" tabindex="-1" role="dialog" aria-labelledby="expressionOfInterestModalCenteredTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header row">
                                                    <h5 class="modal-title col-md-11" id="expressionOfInterestModalTitle">
                                                        <?= \Yii::t('amoscollaborations', 'Manifesta interesse'); ?>
                                                    </h5>
                                                    <button type="button" class="close col-md-1" data-dismiss="modal" aria-label="Chiudi">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                    $modalBody = $module->modalExpressionOfInterest['modalBody'];
                                                    if (!empty($modalBody)) {
                                                        echo \Yii::t('amoscollaborations', $modalBody);
                                                    } ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" title="<?= \Yii::t('amoscollaborations', 'Chiudi') ?>">
                                                        <?= \Yii::t('amoscollaborations', 'Chiudi') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php } else {
                                    echo Html::a(
                                        \Yii::t('amoscollaborations', 'Manifesta interesse'),
                                        ['collaboration-expressions-of-interest/create', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-primary',
                                            'data-toggle' => 'tooltip',
                                            'title' => \Yii::t('amoscollaborations', 'Accedi o registrati alla piattaforma per manifestare interesse'),
                                            'disabled' => true
                                        ]
                                    );
                                }
                            }
                        } ?>

                    </div>
                </div>

            </div>
            <div class="clearfix"></div>
            <div class="row nom post-wrap">
                <div class="post-text lead">
                    <?= $model->short_summary ?>
                </div>
            </div>
        </div>
        <div>

        </div>
    </div>

    <hr class="dotted">

    <div class="container-general-info">
        <div id="<?= $moreInformationBlockId ?>">
            <!--            <p class="title h4 m-t-30">--><? //= AmosIcons::show('info-outline'); 
                                                            ?><!-- --><? //= Module::tHtml('amoscollaborations', 'Dettaglio Proposta') 
                                                                                                            ?><!--</p>-->
            <?php
            $attributes = [];

            /*$attributes['title'] = [
                'attribute' => 'title',
                //'label' => \Yii::t('amoscollaborations', 'Titolo')
            ];*/
            //            $attributes['id_een'] = [
            //                'attribute' => 'id_een',
            //                'label' => \Yii::t('amoscollaborations', 'ID EEN')
            //            ];
            /*$attributes['category_id'] = [
                'attribute' => 'category_id',
                //'label' => \Yii::t('amoscollaborations', 'Categoria'),
                'format' => 'html',
                'value' => $model->getCategoryLabel()[$model->category_id]
            ];*/
            /*$attributes['status'] = [
                'attribute' => 'status',
                'label' => \Yii::t('amoscollaborations', 'Stato'),
                'value' => $isArchived ? \Yii::t('amoscollaborations', 'Archiviata') : $model->getWorkflowStatusLabel()[$model->status]
            ];*/
            $attributes['full_description'] = [
                'attribute' => 'full_description',
                //'label' => \Yii::t('amoscollaborations', 'Descrizione completa'),
                'format' => 'html'
            ];
            if (!empty($model->advantages)) {
                $attributes['advantages'] = [
                    'attribute' => 'advantages',
                    //'label' => \Yii::t('amoscollaborations', 'Vantaggi / Aspetti innovativi'),
                    'format' => 'html'
                ];
            }
            if (!empty($model->technical_specifications)) {
                $attributes['technical_specifications'] = [
                    'attribute' => 'technical_specifications',
                    //'label' => \Yii::t('amoscollaborations', 'Specifiche tecniche / Competenze cercate'),
                    'format' => 'html'
                ];
            }
            $attributes['ipr_status'] = [
                'attribute' => 'ipr_status_mm',
                'label' => \Yii::t('amoscollaborations', 'Proprietà industriale'),
                'format' => 'html',
                'value' => !empty($ipr_statues) ? $ipr_statues : \Yii::t('amoscollaborations', 'Non specificato')
            ];
            $attributes['stage_of_development_id'] = [
                'attribute' => 'stage_of_development_id',
                //'label' => \Yii::t('amoscollaborations', 'Stadio di sviluppo'),
                'format' => 'html',
                'value' => CollaborationStageOfDevelopment::findOne($model->stage_of_development_id)->name
            ];
            $attributes['partnerhip_type'] = [
                'attribute' => 'partnership_type_mm',
                'label' => \Yii::t('amoscollaborations', 'Tipo di partnership'),
                'format' => 'html',
                'value' => !empty($partnership_types) ? $partnership_types : \Yii::t('amoscollaborations', 'Non specificato')
            ];
            $attributes['partner_type_and_size'] = [
                'attribute' => 'partner_type_and_size_mm',
                'label' => \Yii::t('amoscollaborations', 'Tipo e dimensione del partner cercato'),
                'format' => 'html',
                'value' => !empty($partner_type_and_size) ? $partner_type_and_size : \Yii::t('amoscollaborations', 'Non specificato')
            ];
            $attributes['partner_role'] = [
                'attribute' => 'partner_role',
                //'label' => \Yii::t('amoscollaborations', 'Ruolo / Contributo del partner cercato'),
                'format' => 'html'
            ];
            /*$attributes['collaboration_type_id'] = [
                'attribute' => 'collaboration_type_id',
                //'label' => \Yii::t('amoscollaborations', 'Tipo di collaborazione ricercata'),
                'format' => 'html',
                'value' => CollaborationType::findOne($model->collaboration_type_id)->name
            ];*/
            /*$attributes['target_countries'] = [
                'attribute' => 'target_countries_mm',
                'label' => \Yii::t('amoscollaborations', 'Paesi interessati'),
                'format' => 'html',
                'value' => !empty($target_countries) ? $target_countries : \Yii::t('amoscollaborations', 'Non specificato')
            ];*/
            /*$attributes['market_keywords'] = [
                'attribute' => 'market_keywords_mm',
                'label' => \Yii::t('amoscollaborations', 'Mercati di sbocco'),
                'format' => 'html',
                'value' => !empty($market_keywords) ? $market_keywords : \Yii::t('amoscollaborations', 'Non specificato')
            ];*/
            /*$attributes['technology_keywords'] = [
                'attribute' => 'technology_keywords_mm',
                'label' => \Yii::t('amoscollaborations', 'Tecnologie impiegate'),
                'format' => 'html',
                'value' => !empty($tech_keywords) ? $tech_keywords : \Yii::t('amoscollaborations', 'Non specificato')
            ];*/
            if (!empty($model->video_pitch)) {
                if (strpos($model->video_pitch, 'youtube')) {
                    $videoUrl = SiteManagementSliderElem::getUrlEmbedVideoStatic($model->video_pitch);
                } else if (strpos($model->video_pitch, 'vimeo')) {
                    $videoUrl = CollaborationProposalsUtility::getVimeoEmbedVideoUrl($model->video_pitch);
                }
                $videoTitle = !empty($model->video_title) ? $model->video_title : 'Video';
                $attributes['video'] = [
                    'attribute' => 'video_pitch',
                    'label' => \Yii::t('amoscollaborations', 'Presentazione video'),
                    'format' => 'raw',
                    /*'value' => Html::a(
                        !empty($model->video_title) ? $model->video_title : $model->video_pitch,
                        $model->video_pitch,
                        ['target' => '_blank']
                    )*/
                    'value' => '<div>' .
                        $model->video_title .
                        '<div class="m-t-20">' . CollaborationProposalsUtility::getEmbeddedVideo($videoUrl, $videoTitle) . '</div>'
                        . '</div>'
                ];
            }
            ?>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'options' => ['class' => 'table-info table-responsive']
            ]) ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php
    $itemsTab[] = [
        'label' => \Yii::t('amoscollaborations', 'Scheda'),
        'content' => $this->blocks[$idTabCard],
        'options' => ['id' => $idTabCard],
    ];
    ?>

    <?php $this->beginBlock($idTabAttachments); ?>

    <h2><?= \Yii::t('amoscollaborations', 'Allegati') ?></h2>
    <div>
        <h3><?= \Yii::t('amoscollaborations', 'Documento') ?></h3>
        <?php if (!empty($model->pdf)) {
            if (!empty($model->short_abstract)) { ?>
                <p><?= $model->short_abstract ?></p>
        <?php }
        } ?>
        <?= AttachmentsTableWithPreview::widget([
            'model' => $model,
            'attribute' => 'pdf',
            'viewDeleteBtn' => false
        ]) ?>
    </div>
    <!--<div>
        <h3><?/*= \Yii::t('amoscollaborations', 'Immagine') */ ?></h3>
        <?php /*if (!empty($model->image)) {
                if (!empty($model->caption)) {*/ ?>
                <p><?/*= $model->caption */ ?></p>
            <?php /*}
            } */ ?>
        <?/*= AttachmentsTableWithPreview::widget([
            'model' => $model,
            'attribute' => 'image',
            'viewDeleteBtn' => false
        ]) */ ?>
    </div>-->
    <?php $this->endBlock(); ?>

    <div class="clearfix"></div>
    <?php
    $itemsTab[] = [
        'label' => \Yii::t('amoscollaborations', 'Allegati'),
        'content' => $this->blocks[$idTabAttachments],
        'options' => ['id' => $idTabAttachments],
    ];
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>
</div>