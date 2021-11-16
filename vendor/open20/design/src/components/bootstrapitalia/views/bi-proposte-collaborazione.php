<?php
/** @var $model \open20\amos\partnershipprofiles\models\PartnershipProfiles */


use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;
use open20\design\utility\DateUtility;
use open20\design\utility\DesignIcon;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use \open20\amos\cwh\utility\CwhUtil;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$expireDate = (isset($expireDate)) ? DateUtility::getDate($expireDate, 'php:d F Y') :  '';
$publishedAt = (isset($publishedAt)) ? DateUtility::getDate($publishedAt, 'php:d F Y') :  '';
$labelReadMore = Module::t('amosdesign', 'Dettaglio proposta');
$statesCounter = (isset($statesCounter)) ? $statesCounter :  '';
$url =(isset($url)) ? $url :  '';
$pubblicationRule =(isset($pubblicationRule)) ? CwhUtil::getPublicationRuleLabel($pubblicationRule) :  '';
$status =(isset($status)) ? $status :  '';


?>

<div class="proposte-collaborazione-wrapper col-12">
    <div class="row variable-gutters">
        <div class="col-md-3 info-proposte-collaborazione">
            <div class="row mt-md-0 mt-2 text-md-right">

                <div class="col-auto col-md-12">
                    <div class="complementary-2-color-a8 small font-weight-bold">
                        <span>
                            <?= DesignIcon::show('layers', DesignIcon::ICON_MD, 'icon icon-sm icon-warning mr-1', $currentAsset) ?>
                        </span>
                        <span class="text-uppercase"><?= Module::t('amosdesign', "Dalla piattaforma") ?></span>
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
                    <a href="<?= $url ?>"
                       title="<?= Module::t('amosdesign', 'Vai alla proposta collaborazione') . ': ' ?><?= $title ?>"
                       class="link-list-title">
                        <h3 class="title-three-line"><?= $title ?></h3>
                    </a>
                    <div class="d-flex align-items-center ml-auto pl-3 mt-1">
                        <?php if($model): ?>
                            <?= \open20\design\components\BulletNewWidget::widget(['model' => $model]) ?>
                        <?php endif; ?>
                        <div class="ml-2">
                            <?php echo $this->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget'); ?>
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
                    <a class="read-more ml-auto" href="<?= $url ?>" title="<?= $labelReadMore ?>">
                        <span class="text"><?= $labelReadMore ?></span>
                    </a>

                </div>
            </div>

        </div>

    </div>
</div>
