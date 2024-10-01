<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\sondaggi
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use open20\design\utility\DateUtility;


/**
 * @var \open20\amos\sondaggi\models\Sondaggi $model
 */

$isCommunityManager = false;
if(!empty(\Yii::$app->getModule('community'))) {
    $isCommunityManager = \open20\amos\community\utilities\CommunityUtil::isLoggedCommunityManager();
}

ModuleSondaggiAsset::register($this);
/**
 * @var \open20\amos\sondaggi\models\Sondaggi $model
 */
$hideStatusPoll = (isset($hideStatusPoll)) ? $hideStatusPoll : false;
$dateStart = $model->created_at;
$dayStart   = DateUtility::getDate($dateStart, 'php:d');
$monthStart = DateUtility::getDate($dateStart, 'php:M');
$yearStart  = DateUtility::getDate($dateStart, 'php:Y');
$dateStart  = DateUtility::getDate($dateStart);


$hideDateEnd = (isset($hideDateEnd) ? $hideDateEnd : false);
if (isset($dateEnd)) {
    $dayEnd   = DateUtility::getDate($dateEnd, 'php:d');
    $monthEnd = DateUtility::getDate($dateEnd, 'php:M');
    $yearEnd  = DateUtility::getDate($dateEnd, 'php:Y');
    $dateEnd  = DateUtility::getDate($dateEnd);
}
?>


<div class="sondaggi-item-list-wrapper container-card-sondaggi">
    <div class="card-wrapper">
        <div class="card">
            <div class="card-body border-bottom border-light px-0">
                <div class="content-date-img">
                    <div class="ml-auto">
                        <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/sondaggi/dashboard/dashboard?id=" . $model->id,
                            'actionDelete' => "/sondaggi/sondaggi/delete?id=" . $model->id,
                            'labelModify' => \Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') ? AmosSondaggi::t('amossondaggi', 'Modifica') : AmosSondaggi::t('amossondaggi', 'Gestisci'),
                            'mainDivClasses' => ''
                        ]) ?>
                    </div>

                    <div class="date">
                        <div class="date-start">
                            <span class="small"><?= AmosSondaggi::t('amossondaggi', 'APERTO IL') ?></span>
                            <span class="card-day bold lead"><?= $dayStart ?></span>
                            <span class="card-mounth bold"><?= $monthStart ?></span>
                            <span class="card-year"><?= $yearStart ?></span>
                        </div>
                        <?php if (!$hideDateEnd) : ?>
                            <div class="date-end-poll">
                                <div class="category-top small">
                                    <span class="font-weight-light"> <?= AmosSondaggi::t('amossondaggi', 'Fino al') . ' ' ?></span>
                                    <span class="card-day"><?= $dayEnd ?></span>
                                    <span class="card-month"><?= $monthEnd ?></span>
                                    <span class="card-year"><?= $yearEnd ?></span>
                                </div>
                            </div>
                        <?php endif ?>
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

            <div class="content-text-sondaggi">
                <div class="list-title m-t-10">
                    <a href="<?= $model->getFullViewUrl() ?>" title="<?= $model->titolo ?>" class="link-list-title title-two-line">
                        <h5 class="card-title font-weight-bold big-heading mb-2 "><strong><?= $model->titolo ?></strong></h5>
                    </a>
                </div>
            </div>
            <?php
                if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGI_READ', ['model' => $model])) {
                ?>
            <div class="desc-poll">
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
                <span class="partecipanti-poll col-sm-12 col-md-12 col-lg-6 nopl"><?= AmosSondaggi::t('amossondaggi', 'Partecipanti') . ':' ?><strong> <?= $model->getNumeroPartecipazioni() ?></strong></span>
                <?php
                if (!$hideStatusPoll) { ?>
                    <span class="status-poll col-sm-12 col-md-12 col-lg-6 nopl"><?= \Yii::t('amossondaggi', 'Stato') ?>:<strong> <?= $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--'; ?></strong></span>
                <?php }; ?>
            </div>
            <?php
                /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                $url = \yii\helpers\Url::current();
                //if (\Yii::$app->getUser()->can('PARTECIPANTE') || TRUE) {

                if (!$model->isCompilable() && \Yii::$app->user->can('COMPILA_SONDAGGIO', ['model' => $model])) {
                    echo Html::a(
                        AmosSondaggi::tHtml('amossondaggi', 'Partecipa'),
                        Yii::$app->urlManager->createUrl([
                            '/' . $this->context->module->id . '/pubblicazione/compila',
                            'id' => $model->id,
                            'url' => $url,
                        ]),
                        [
                            'class' => 'read-more m-t-10',
                            'title' => AmosSondaggi::t('amossondaggi', 'Partecipa'),
                        ]
                    );
                }
                ?>
            </div>

        </div>
    </div>
</div>
