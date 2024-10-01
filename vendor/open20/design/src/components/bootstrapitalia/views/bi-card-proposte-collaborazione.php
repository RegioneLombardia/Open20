<?php
/** @var $model \open20\amos\partnershipprofiles\models\PartnershipProfiles */


use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\models\CollaborationType;
use open20\design\utility\DateUtility;
use open20\design\utility\DesignIcon;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use \open20\amos\cwh\utility\CwhUtil;
use open20\amos\core\helpers\Html;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$expireDate = (isset($expireDate)) ? DateUtility::getDate($expireDate, 'php:d F Y') :  '';
$publishedAt = (isset($publishedAt)) ? DateUtility::getDate($publishedAt, 'php:d F Y') :  '';
$labelReadMore = Module::t('amosdesign', 'Dettaglio proposta');
$statesCounter = $model->getExpressionsOfInterestStatesCounter();
$url =(isset($url)) ? $url :  '';
$pubblicationRule =(isset($pubblicationRule)) ? CwhUtil::getPublicationRuleLabel($pubblicationRule) :  '';
$status =(isset($status)) ? $status :  '';
$loggedUser = \open20\amos\core\user\User::findOne(\Yii::$app->user->id);
$validatoAlmenoUnaVolta = false;
if($loggedUser){
    $validatoAlmenoUnaVolta = $loggedUser->userProfile->validato_almeno_una_volta;
}

$isPlatform = ($model->category_id == CollaborationProposals::CATEGORY_FROM_PLATFORM);
$classCategory = $isPlatform ? 'collaborations-platform' : 'collaborations-world';

$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);


?>
<?php /*
<div class="proposte-collaborazione-wrapper col-12">
    <div class="row variable-gutters">
        <div class="col-md-3 info-proposte-collaborazione">
            <div class="row mt-md-0 mt-2 text-md-right">

                <div class="col-auto col-md-12">
                    <div class="complementary-2-color-a8 small font-weight-bold">
                        <span>
                            <?= DesignIcon::show('layers', DesignIcon::ICON_MD, 'icon icon-sm icon-warning mr-1', $currentAsset) ?>
                        </span>
                        <?php if ($model->category_id == CollaborationProposals::CATEGORY_FROM_PLATFORM) { ?>
                            <span class="mdi mdi-layers"></span>
                        <?php } else { ?>
                            <span class="mdi mdi-earth"></span>
                        <?php } ?>
                        <span><?= $categoryLabel ?></span>
                    </div>
                    <?php if($expireDate): ?>
                        <div class="date-end bg-tertiary text-white px-2 my-md-1">
                            <small><?= Module::t('amosdesign', 'Scadenza') . ': ' ?></small>
                            <strong><?= $expireDate ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($model): ?>
                    <div class="col-12">
                        <div class="ml-auto my-1">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                    'model' => $model->createdUserProfile
                                ]
                            );
                            ?>
                        </div>

                    </div>
                <?php endif; ?>
                <div class="col-12 mt-auto text-right">
                    <div class="row">
                        <?php if($publishedAt): ?>
                            <div class="other-info my-1 col-auto col-md-12">
                                <small><?= Module::t('amosdesign', 'Pubblicata il') . ': ' ?></small>
                                <strong><?= $publishedAt ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if($pubblicationRule): ?>
                            <div class="other-info my-1 col-auto col-md-12">
                                <small><?= Module::t('amosdesign', 'per') . ' ' ?></small>
                                <strong><?= $pubblicationRule ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if($status): ?>
                            <div class="other-info my-1 col-auto col-md-12">
                                <small><?= Module::t('amosdesign', 'Stato') . ': ' ?></small>
                                <strong><?= $status ?></strong>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="content-proposte-collaborazione h-100 pl-md-3">
                <div class="d-flex align-items-start mt-2 mt-md-0">
                    <?php if(!$validatoAlmenoUnaVolta) {?>
                        <a data-target="#modal-pp-alert" data-toggle = "modal" href="<?= 'javascript:void(0)' ?>"
                           title="<?= Module::t('amosdesign', 'Vai alla proposta collaborazione') . ': ' ?><?= $model->getTitle() ?>"
                           class="link-list-title">
                            <h3 class="title-three-line"><?= $model->getTitle() ?></h3>
                        </a>
                    <?php  } else { ?>
                    <a href="<?= $url ?>"
                       title="<?= Module::t('amosdesign', 'Vai alla proposta collaborazione') . ': ' ?><?= $title ?>"
                       class="link-list-title">
                        <h3 class="title-three-line"><?= $title ?></h3>
                    </a>
                    <?php } ?>
                    <div class="d-flex align-items-center ml-auto pl-3 mt-1">
                        <?php if($model): ?>
                            <?= \open20\design\components\BulletNewWidget::widget(['model' => $model]) ?>
                        <?php endif; ?>
                        <div class="ml-2">
                        <?php
                        echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                        [
                            'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
                            'model' => $model
                        ]
                        );
                    ?>
                        </div>
                    </div>
                </div>
                <p class="title-three-line"><?= $shortTitle ?></p>
                <div class="mt-auto d-flex flex-wrap">
                    <?php if($statesCounter['active']): ?>
                        <div class="box-interesse">
                            <div class="num-interesse"><?= $statesCounter['active'] ?></div>
                            <strong><?= Module::t('amosdesign', 'Manifestazioni di interesse') ?></strong>
                        </div>
                    <?php endif; ?>
                    <?php if(!$validatoAlmenoUnaVolta) {?>
                        <a data-target="#modal-pp-alert" data-toggle = "modal" class="read-more ml-auto" href="<?= 'javascript:void(0)' ?>" title="<?= $labelReadMore ?>">
                            <span class="text"><?= $labelReadMore ?></span>
                        </a>
                    <?php  } else { ?>
                    <a class="read-more ml-auto" href="<?= $url ?>" title="<?= $labelReadMore ?>">
                        <span class="text"><?= $labelReadMore ?></span>
                    </a>
                    <?php } ?>


                </div>
            </div>

        </div>

    </div>
</div>
*/ ?>
<div class="proposte-collaborazione-card-wrapper <?= $widthColumn ?>">
    <div class="data-scadenza bg-primary text-white small text-center rounded-top text-uppercase">
        <strong><?= \Yii::t('amoscollaborations', 'Scadenza') . ': ' ?><?= Yii::$app->formatter->asDate($model->end_date, 'long') ?></strong>
    </div>
    <div class="img-collaborations">
        <?php $imageUrl = '/img/img_default.jpg';
        if ($model->image) {
            $imageUrl = $model->image->getWebUrl();
        } ?>
        <a href="<?= $model->getFullViewUrl() ?>" title="<?= $model->title ?>">
            <img src="<?= $imageUrl ?>" class="img-responsive" alt="<?= $model->title ?>">
            
        </a>
    </div>
    <div class="tipo-collaborazione my-2 d-flex justify-content-between">
        <span class="label label-category-collaboration text-uppercase small font-weight-bold p-1 rounded">
            <?php if ($isPlatform) { ?>
                <span class="mdi mdi-application-outline"></span>
            <?php } else { ?>
                <span class="mdi mdi-earth"></span>
            <?php } ?>
            <span><?= $model->getCategoryLabel()[$model->category_id] ?></span>
        </span>
        <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                                [
                                    'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
                                    'model' => $model
                                ]
                            );
                            ?>
    </div>
    <div class="title-collaborations">
        <div class="h5"><strong><?= Html::a(
                                    $model->title,
                                    $model->getFullViewUrl(),
                                    [
                                        'class' => 'link-list-title',
                                        'title' =>  $model->title,
                                    ]
                                ); ?></strong></div>
    </div>
    <div class="descrizione">
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
    </div>
    <div class="box-tipo mt-3">
        <?php if (!Yii::$app->user->isGuest) { ?>
            <span class="label label-default px-2 font-weight-bold"><?= \Yii::t('amoscollaborations', CollaborationType::findOne($model->collaboration_type_id)->name) ?></span></p>
        <?php } ?>
    </div>
    <?php if (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || Yii::$app->user->can('COLLABORATIONS_STAFF')) { ?>
        <div class="box-interesse">
            <strong class="num-interesse">
                <?php $exprCount = CollaborationExpressionsOfInterest::find()->andWhere(['collaboration_proposal_id' => $model->id])->count() ?>
                <?= $exprCount ?>
            </strong>
            <span class="text-interesse">
                <span><?= $exprCount == 1 ? \Yii::t('amoscollaborations', 'Manifestazione di interesse') : \Yii::t('amoscollaborations', 'Manifestazioni di interesse') ?></span>
                <?php if ($exprDoneByMe > 0) { ?>
                    <div>
                        <small class="text-muted">
                            <?= \Yii::t('amoscollaborations', 'Hai già manifestato interesse per questa proposta'); ?>
                        </small>
                    </div>

                <?php } ?>
            </span>
        </div>
    <?php } ?>
    <div class="cta">
        <?= Html::a(
            \Yii::t('amoscollaborations', 'Leggi tutto'),
            $model->getFullViewUrl(),
            [
                'class' => 'readmore text-uppercase small font-weight-bold',
                'title' => \Yii::t('amoscollaborations', 'Leggi tutto')
            ]
        ); ?>
    </div>
</div>