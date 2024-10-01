<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\backendobjects\frontend\views\default\fullsize
 */

use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\models\CollaborationType;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\core\user\User;
use open20\amos\core\utilities\ButtonUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;
use open20\design\utility\DateUtility;
use open20\design\components\BulletNewWidget;

BootstrapItaliaDesignAsset::register($this);

$title = $model->title;
$shortSummary = $model->short_summary;
$expireDate = DateUtility::getDate($model->end_date, 'php:d F Y');
$publishedAt = Yii::$app->formatter->asDate($model->start_date);
$updatedAt = Yii::$app->formatter->asDate($model->updated_at);
$labelReadMore = \Yii::t('amoscollaboration', 'Leggi tutto');
$statesCounter = $model->getExpressionsOfInterestStatesCounter();
$url = $model->getFullViewUrl();
$status = $model->getWorkflowStatusLabel()[$model->status];
$categoryLabel = $model->getCategoryLabel()[$model->category_id];
$collaborationType = CollaborationType::findOne($model->collaboration_type_id)
    ->name;

$exprCount = CollaborationExpressionsOfInterest::find()
    ->andWhere(['collaboration_proposal_id' => $model->id])
    ->count();
$loggedUser = User::findOne(\Yii::$app->user->id);

$actionModify = isset($actionModify) ? $actionModify : null;
$actionDelete = isset($actionDelete) ? $actionDelete : null;

$templateContextMenu = '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget';

?>

<div class="col-md-4 mb-4">
    <div class="item-challenge align-items-start">
        <?php if ($expireDate): ?>
            <div class="date-end mt-md-1 mb-md-3">
                <small><?= Module::t('amosdesign', 'Scadenza') . ': ' ?></small>
                <strong><?= $expireDate ?></strong>
            </div>
        <?php endif; ?>
        <div class="title-challenge h5 font-weight-bold mb-4">
            <a class="link-list-title" href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vai alla proposta collaborazione') . ': ' ?><?= $title ?>"> 
                <?= $title ?>
            </a>
        </div>
        <div class="description-challenge">
            <?= $shortSummary ?>
        </div>
        <div class="footer-challenge d-flex w-100">
            <div class="cta-challenge flex-grow-1">
                <a  href="<?= $url ?>" title="Leggi" class="read-more"><?= Module::t('amosdesign', 'Leggi') ?> <span class="mdi mdi-arrow-right ml-2"></span></a>
            </div>
            
        </div>
    </div>
</div>
