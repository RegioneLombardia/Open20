<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\design\utility\DateUtility;
use open20\amos\sondaggi\models\Sondaggi;

ModuleSondaggiAsset::register($this);
/**
 * @var \open20\amos\sondaggi\models\Sondaggi $model
 */
$hideStatusPoll = (isset($hideStatusPoll)) ? $hideStatusPoll : false;
$dateStart = $model->publish_date;
$dayStart   = DateUtility::getDate($dateStart, 'php:d');
$monthStart = DateUtility::getDate($dateStart, 'php:M');
$yearStart  = DateUtility::getDate($dateStart, 'php:Y');
$dateStart  = DateUtility::getDate($dateStart);
$dateEnd = $model->close_date;


$hideDateEnd = empty($model->close_date);
if (isset($dateEnd)) {
    $dayEnd   = DateUtility::getDate($dateEnd, 'php:d');
    $monthEnd = DateUtility::getDate($dateEnd, 'php:M');
    $yearEnd  = DateUtility::getDate($dateEnd, 'php:Y');
    $dateEnd  = DateUtility::getDate($dateEnd);
}

?>

<div class="sondaggi-item-list-wrapper">
    <div class="card-wrapper">
        <div class="card">
            <div class="card-body border-bottom border-light px-0">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="content-date-img">
                            <div class="date-start">
                                <span class="small"><?= AmosSondaggi::t('amossondaggi', 'Apre il') ?></span>
                                <span class="card-day bold lead"><?= $dayStart ?></span>
                                <span class="card-mounth bold"><?= $monthStart ?></span>
                                <span class="card-year"><?= $yearStart ?></span>
                            </div>
                            <?php
                           $url = '/img/img_default.jpg';
                           if ($model->file) {
                               $url = $model->file->getUrl('dashboard_news');
                           }

                            ?>
                            <div class="img-sondaggio">
                                <?= Html::img($url, [
                                    'alt' => AmosSondaggi::t('amossondaggi', 'Immagine del sondaggio'),
                                    'class' => 'img-responsive'
                                ]); ?>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="content-text-sondaggi">


                            <?php if (!$hideDateEnd) : ?>
                                <div class="date-end-poll">
                                    <div class="category-top small text-muted">
                                        <span class="font-weight-light"> <?= AmosSondaggi::t('amossondaggi', 'Fino al') . ' ' ?></span>
                                        <span class="card-day"><?= $dayEnd ?></span>
                                        <span class="card-month"><?= $monthEnd ?></span>
                                        <span class="card-year"><?= $yearEnd ?></span>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="list-title">
                                <a href="<?php
                                    $path = 'pubblicazione/compila';
                                    if (Yii::$app->controller->action->id == 'manage')
                                        $path = 'dashboard/dashboard';
                                    echo Yii::$app->urlManager->createUrl([
                                        '/'.$this->context->module->id.'/'.$path,
                                        'id' => $model->id,
                                        'url' => $url,
                                    ])
                                ?>" title="<?= $model->titolo ?>" class="link-list-title title-two-line">
                                    <h5 class="card-title font-weight-bold big-heading mb-2 "><strong><?= $model->titolo ?></strong></h5>
                                </a>
                                <?php
                                if ($model->isCommunitySurvey()) {
                                    $community = \open20\amos\community\models\Community::findOne($model->community_id);
                                    if ($community && $community->name) { ?>
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="dalla community <?= $community->name ?>">

                                            <span class="mdi mdi-account-supervisor-circle text-muted m-l-5"></span>

                                            <span class="sr-only"><?= $community->name ?></span>
                                        </a>
                                    <?php }
                                } ?>

                                <div class="ml-auto">

                                    <?php if(\Yii::$app->getUser()->can('DASHBOARD_VIEW')): ?>
                                        <?php
                                        $labelDeleteConfirm = null;
                                        $actionDelete = '/sondaggi/sondaggi/delete?id=' . $model->id;
                                        $checkDeletePermission = true;
                                        if ($model->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                                            $labelDeleteConfirm = AmosSondaggi::t('amossondaggi', 'E\' necessario disattivare il sondaggio per procedere con l\'eliminazione. Procedere con la disattivazione del sondaggio?');
                                            $actionDelete = ['/sondaggi/pubblicazione/depubblica', 'idSondaggio' => $model->id, 'url' => '/sondaggi/dashboard/dashboard?id=' . $model->id];
                                            $checkDeletePermission = false;
                                        }
                                        ?>
                                        <?= ContextMenuWidget::widget([
                                            'model' => $model,
                                            'actionModify' => "/sondaggi/dashboard/dashboard?id=" . $model->id,
                                            'actionDelete' => $actionDelete,
                                            'labelDeleteConfirm' => $labelDeleteConfirm,
                                            'mainDivClasses' => '',
                                            'checkModifyPermission' => false,
                                            'checkDeletePermission' => $checkDeletePermission,
                                            'labelModify' => \Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') ? AmosSondaggi::t('amossondaggi', 'Modifica') : AmosSondaggi::t('amossondaggi', 'Gestisci')
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGI_READ', ['model' => $model])) {
                            ?>
                                <div class="desc-poll text-muted">
                                    <p>
                                        <?php
                                        if (strlen($model->descrizione) > 300) {
                                            $stringCut = substr($model->descrizione, 0, 300);
                                            echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                        } else {
                                            echo $model->descrizione;
                                        }
                                        ?>
                                    </p>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="footer-list">
                                <?php if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGI_MANAGE')) { ?>
                                    <span class="partecipanti-poll"><?= AmosSondaggi::t('amossondaggi', 'Partecipanti') . ':' ?><strong> <?= $model->getNumeroPartecipazioni() ?></strong></span>
                                    <?php
                                    if (!$hideStatusPoll) {
                                        $statusLabel = '--';
                                        if ($model->hasWorkflowStatus()) {
                                            if ($model->getWorkflowStatus()->id == Sondaggi::WORKFLOW_STATUS_VALIDATO && SondaggiUtility::isTerminated($model)) {
                                                $statusLabel = AmosSondaggi::t('amossondaggi', Sondaggi::STATUS_CONCLUSO);
                                            } else {
                                                $statusLabel = AmosSondaggi::t('amossondaggi', $model->getWorkflowStatus()->getLabel());
                                            }
                                        }
                                        ?>
                                        <span class="status-poll"><?=  AmosSondaggi::t('amossondaggi', 'Stato') ?>:
                                            <strong>
                                                <?= $statusLabel ?>
                                            </strong>
                                        </span>
                                    <?php }; ?>
                                <?php }
                                /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                                $url = \yii\helpers\Url::current();
                                //if (\Yii::$app->getUser()->can('PARTECIPANTE') || TRUE) {
                                if (\Yii::$app->getUser()->can('RESPONSABILE_ENTE')) {
                                    echo Html::a(
                                        AmosSondaggi::tHtml('amossondaggi', '#assign_compiler'), '#',
                                        [
                                            'class' => 'assign-compiler',
                                            'title' => AmosSondaggi::t('amossondaggi', '#assign_compiler'),
                                            'data' => ['id' => $model->id]
                                        ]
                                    ).'&nbsp;';
                                }
                                if ($model->isCompilable() && \Yii::$app->user->can('COMPILA_SONDAGGIO', ['model' => $model])) {
                                    echo Html::a(
                                        AmosSondaggi::tHtml('amossondaggi', 'Partecipa'),
                                        Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/pubblicazione/compila',
                                            'id' => $model->id,
                                            'url' => $url,
                                        ]),
                                        [
                                            'class' => 'read-more',
                                            'title' => AmosSondaggi::t('amossondaggi', 'Partecipa'),
                                        ]
                                    );
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
