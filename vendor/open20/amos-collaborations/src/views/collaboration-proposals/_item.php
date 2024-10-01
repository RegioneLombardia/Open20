<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\views\collaboration-proposals
 * @category   CategoryName
 */

use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\collaborations\Module;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\models\CollaborationType;

/** @var CollaborationProposals $model */

$exprDoneByMe = CollaborationExpressionsOfInterest::find()
    ->andWhere(['collaboration_proposal_id' => $model->id])
    ->andWhere(['created_by' => Yii::$app->user->id])
    ->count();

$module = \Yii::$app->getModule('collaborations');
$moduleCwh = \Yii::$app->getModule('cwh');
$communityConfigurationsId = null;
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    if (isset($scope['community'])) {
        $communityConfigurationsId = 'communityId-' . $scope['community'];
    }
}

//$enabledFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'] : (!empty($module->fieldsConfigurations['fields']) ? $module->fieldsConfigurations['fields'] : []);

?>
<div class="listview-container">
    <div class="<?= Yii::$app->controller->id ?> col-xs-12">
        <div class="row row-d-flex">
            <div class="col-sm-3 info-proposte-collaborazione">
                <div class="flexbox">
                    <div class="col-auto">
                        <div class="tipo-collaborazione small text-warning">
                            <?php if ($model->category_id == CollaborationProposals::CATEGORY_FROM_PLATFORM) { ?>
                                <span class="mdi mdi-layers"></span>
                            <?php } else { ?>
                                <span class="mdi mdi-earth"></span>
                            <?php } ?>
                            <span><?= $model->getCategoryLabel()[$model->category_id] ?></span>
                        </div>
                        <div class="date-end bg-secondary">
                            <small><?= \Yii::t('amoscollaborations', 'Scadenza') . ': ' ?></small><strong><?= Yii::$app->formatter->asDate($model->end_date, 'long') ?></strong>
                        </div>
                    </div>
                </div>


                <!--<div class="author">
                    <?= ItemAndCardHeaderWidget::widget([
                        'model' => $model,
                        'publicationDateField' => 'updated_at'
                    ]) ?>
                </div>-->
                <?php //if (!Yii::$app->user->isGuest) { ?>
                    <!--<div class="other-info">-->
                        <!--<small>< ?= \Yii::t('amoscollaborations', 'ID EEN') . ': ' ?></small><strong>< ?= $model->id_een ?></strong><br>-->
                        <!--<div class="published-by">
                            <div class="item"><small>< ?= \Yii::t('amoscollaborations', 'Proposta il') . ': ' ?></small><strong>< ?= Yii::$app->formatter->asDate($model->start_date) ?></strong></div>
                            <div class="item"><small>< ?= \Yii::t('amoscollaborations', 'Ultimo aggiornamento') . ': ' ?></small><strong>< ?= Yii::$app->formatter->asDate($model->updated_at) ?></strong></div>
                        </div>


                        <small>< ?= \Yii::t('amoscollaborations', 'Collaborazione cercata') . ': ' ?></small><strong>< ?= CollaborationType::findOne($model->collaboration_type_id)->name ?></strong>-->

                    <!--</div>-->
                <?php //} ?>
            </div>
            <div class="col-sm-9 border-left">
                <div class="content-proposte-collaborazione">
                    <div class="title">
                        <h3><?= Html::a(
                                $model->title,
                                $model->getFullViewUrl(),
                                [
                                    'class' => 'link-list-title',
                                    'title' =>  $model->title,
                                ]
                            ); ?></h3>
                        <div class="ml-auto">
                            <!--< ?= NewsWidget::widget(['model' => $model]); ?>-->
                            <?= ContextMenuWidget::widget([
                                'model' => $model,
                                'actionModify' => ['update', 'id' => $model->id],
                                'disableModify' => !($model->userCanUpdate()),
                                'actionDelete' => ['delete', 'id' => $model->id],
                                'disableDelete' => !($model->userCanDelete()),
                            ]) ?>
                        </div>

                    </div>

                    <p class="title-three-line">
                        <?php
                        $shortDesc = strip_tags($model->short_summary);
                        if (strlen($shortDesc) > 800) {
                            $stringCut = substr($shortDesc, 0, 800);
                            $shortDesc = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                        }
                        ?>
                        <?= $shortDesc ?>

                    </p>
                    <?php if (!Yii::$app->user->isGuest) { ?>
                        <p><span class="label label-default"><?= \Yii::t('amoscollaborations', CollaborationType::findOne($model->collaboration_type_id)->name) ?></span></p>
                    <?php } ?>


                    <div class="footer-item">
                        <?php if (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || Yii::$app->user->can('COLLABORATIONS_STAFF')) { ?>
                            <div class="box-interesse">
                                <div class="num-interesse">
                                    <?php $exprCount = CollaborationExpressionsOfInterest::find()->andWhere(['collaboration_proposal_id' => $model->id])->count() ?>
                                    <?= $exprCount ?>
                                </div>
                                <div class="text-interesse">
                                    <strong><?= $exprCount == 1 ? \Yii::t('amoscollaborations', 'Manifestazione di interesse') : \Yii::t('amoscollaborations', 'Manifestazioni di interesse') ?></strong>
                                    <?php if ($exprDoneByMe > 0) { ?>
                                        <div>
                                        <small class="text-muted">
                                            <?= \Yii::t('amoscollaborations', 'Hai già manifestato interesse per questa proposta'); ?>
                                        </small>
                                        </div>

                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?= Html::a(
                                \Yii::t('amoscollaborations', 'Leggi tutto'),
                                $model->getFullViewUrl(),
                                [
                                    'class' => 'readmore',
                                    'title' => \Yii::t('amoscollaborations', 'Leggi tutto')
                                ]
                        ); ?>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>