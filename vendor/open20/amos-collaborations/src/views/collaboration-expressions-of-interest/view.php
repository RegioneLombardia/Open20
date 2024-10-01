<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor\open20\amos\collaborations\views/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\attachments\components\AttachmentsTableWithPreview;

/**
 * @var yii\web\View $this
 * @var open20\amos\collaborations\models\CollaborationExpressionsOfInterest $model
 */

$this->title = $model->collaborationProposals->title;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/collaborations']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Collaboration Expressions Of Interest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'), 'url' => ['/collaborations/collaboration-proposals/own-interest']];
$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Manifestazioni di interesse'), 'url' => ['/collaborations/collaboration-expressions-of-interest/created-by-me']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];

$isArchived = $model->collaborationProposals->end_date < date('Y-m-d');
?>

<div class="collaboration-expressions-of-interest-view">

    <div class="post-info col-xs-12 m-t-15 m-b-30">
        <div class="container-sidebar">
            <h4 class="title m-l-10">
                <?= \Yii::t('amoscollaborations', 'Proposta di collaborazione') ?>
            </h4>
            <hr>
            <div class="col-sm-6">
                <div>
                    <strong><?= \Yii::t('amoscollaborations', 'Titolo') ?>:</strong>
                    <?= $model->collaborationProposals->title ?>
                </div>
                <div>
                    <strong><?= \Yii::t('amoscollaborations', 'Creatore') ?>:</strong>
                    <?= $model->collaborationProposals->createdUserProfile->nomeCognome ?>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- <div>
                    <strong>< ?= \Yii::t('amoscollaborations', 'Stato') ?>:</strong>
                    < ?= $isArchived ? \Yii::t('amoscollaborations', 'Archiviata') : $model->collaborationProposals->getWorkflowStatusLabel()[$model->collaborationProposals->status] ?>
                </div> -->
                <div>
                    <strong><?= \Yii::t('amoscollaborations', 'Data della proposta') ?>:</strong>
                    <?= \Yii::$app->getFormatter()->asDate($model->collaborationProposals->start_date) ?>
                </div>
                <div>
                    <strong><?= \Yii::t('amoscollaborations', 'Data di scadenza') ?>:</strong>
                    <?= \Yii::$app->getFormatter()->asDate($model->collaborationProposals->end_date) ?>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h4 class="title m-l-10">
            <?= \Yii::t('amoscollaborations', 'Manifestazione di interesse') ?>
            <?= ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => ['update', 'id' => $model->id],
            'actionDelete' => ['delete', 'id' => $model->id],
            'mainDivClasses' => 'm-t-0 m-b-15'
        ]) ?>
        </h4>
       
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'proposer_first_name',
                'proposer_last_name',
                'proposer_email',
                'proposer_phone',
                'company',
                'manifestazioneinteresse',
                [
                    'label' => $model->getAttributeLabel('country'),
                    'value' => function ($model) {
                        return $model->getCountryName($model->country);
                    }
                ],
                [
                    'label' => $model->getAttributeLabel('city'),
                    'value' => function ($model) {
                        return $model->getCityName($model->city);
                    }
                ],
                [
                    'label' => $model->getAttributeLabel('province'),
                    'value' => function ($model) {
                        return $model->getProvinceName($model->province);
                    }
                ],
                'municipality',
                'address',
                'postal_code',
            ],
        ]) ?>
    </div>

    <div><h2><?= \Yii::t('amoscollaborations', 'Allegati') ?></h2>
<div>
    <h3><?= \Yii::t('amoscollaborations', 'Documento') ?></h3>
    <?php if (!empty($model->file)) {
        if (!empty($model->short_abstract)) { ?>
            <p><?= $model->short_abstract ?></p>
    <?php }
    } ?>
    <?= AttachmentsTableWithPreview::widget([
        'model' => $model,
        'attribute' => 'file',
        'viewDeleteBtn' => false
    ]) ?>
</div></div>





</div>

<div>
    <?= Html::a(
            \Yii::t('amoscore', 'Indietro'),
            Url::previous(),
            ['class' => 'btn btn-secondary m-t-10']
    ); ?>
</div>
