<?php
/** @var $model \open20\amos\een\models\EenPartnershipProposal  */
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;
use open20\design\utility\DateUtility;
use open20\design\utility\DesignIcon;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$labelReadMore = (isset($labelReadMore)) ? $labelReadMore : Module::t('amosdesign', 'Dettaglio proposta');
$idProposal =(isset($idProposal)) ? $idProposal :  '';
$title =(isset($title)) ? $title :  '';
$dateEnd =(isset($dateEnd)) ? DateUtility::getDate($dateEnd, 'php:d F Y') :  '';
$dateSubmit =(isset($dateSubmit)) ? DateUtility::getDate($dateSubmit, 'php:d F Y') :  '';
$dateUpdate =(isset($dateUpdate)) ? DateUtility::getDate($dateUpdate, 'php:d F Y') :  '';


if(!empty($model)) {
    //$idProposal = $model->reference_external;
    //$title = $model->content_title;
    //$dateEnd = $model->datum_deadline;
    //$dateSubmit = $model->datum_submit;
    //$dateUpdate = $model->datum_update;
    //$country = $model->company_country_label;
    //$description = $model->content_summary;
    //$type = $model->getReferenceTypeLabel();
    //$url = $model->getFullViewUrl();
}




?>
<div class="proposte-collaborazione-wrapper proposte-collaborazione-een col-12">
  <div class="row variable-gutters">
    <div class="col-md-3 info-proposte-collaborazione">
      <div class="row mt-md-0 mt-2 text-md-right">

        <div class="col-auto col-md-12">
        <div class="complementary-2-color-a8 small font-weight-bold">
            <span>
                <?= DesignIcon::show('earth', DesignIcon::ICON_MD, 'icon icon-sm icon-warning mr-1', $currentAsset)?>
            </span>
            <span class="text-uppercase">
            <?= Module::t('amosdesign', 'Dal mondo') ?>
            </span>
          </div>
          <?php if($dateEnd): ?>
            <div class="date-end bg-tertiary text-white px-2 my-md-1">
              <small><?= Module::t('amosdesign', 'Scadenza') . ': ' ?></small> <strong><?= $dateEnd ?></strong>
            </div>
          <?php endif; ?>
        </div>

        <div class="col-auto col-md-12">
          <div class="row variable-gutters">
            <?php if($dateSubmit): ?>
              <div class="other-info my-1 col-auto col-md-12">
                <small><?= Module::t('amosdesign', 'Presentazione') . ': ' ?></small>
                <strong><?= $dateSubmit ?></strong>
              </div>
            <?php endif; ?>
            <?php if($dateUpdate): ?>
              <div class="other-info my-1 col-auto col-md-12">
                <small><?= Module::t('amosdesign', 'Ultimo aggiornamento') . ': ' ?></small>
                <strong><?= $dateUpdate ?></strong>
              </div>
            <?php endif; ?>
            <?php if($country): ?>
              <div class="other-info my-1 col-auto col-md-12">
                <small><?= Module::t('amosdesign', 'Paese') . ': '  ?></small>
                <strong><?= $country ?></strong>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="content-proposte-collaborazione h-100 pl-md-3">
        <div class="d-flex align-items-start mt-2 mt-md-0">
          <a href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vai alla proposta collaborazione') . ': ' ?><?= $title ?>" class="link-list-title">
            <h3 class="title-three-line"><?= $title ?></h3>
          </a>
          <div class="d-flex align-items-center ml-auto pl-3 mt-1">
              <?php if($model): ?>
                <?= \open20\design\components\BulletNewWidget::widget(['model' => $model]) ?>
              <?php endif; ?>
            <div class="ml-2">
              <?php
              echo $this->render(
                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget'
              );
              ?>
            </div>
          </div>
        </div>
        <div>

        <?php if($description): ?>
          <p class="title-four-line"><?= $description ?></p>
        <?php endif; ?>
        <?php if($type): ?>
          <p><span class="badge badge-secondary"><?= $type ?></span></p>
        <?php endif; ?>

        </div>
        <div class="mt-auto d-flex flex-wrap">
          <?php if($idProposal): ?>
            <div class="blockquote-footer">

              <span><?= Module::t('amosdesign', 'Identificativo proposta') . ': ' ?></span>
              <span class="font-italic"><?= $idProposal ?></span>
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