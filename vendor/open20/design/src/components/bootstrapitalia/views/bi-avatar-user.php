<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$arr = explode(' ', trim($nameSurname));
$name = $arr[0];
$surname = $arr[1];
$initials = substr($name, 0, 1) . substr($surname, 0, 1);
$titlelink = 'Visualizza il profilo di' . ' ' . $nameSurname;
$tooltipText = '<strong>' . $nameSurname . '</strong>' . (isset($tooltipAdditionalInfo) ? '<br/><em>' . $tooltipAdditionalInfo . '</em>' : '');
$removeLink = (isset($removeLink)) ? $removeLink : false;

$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'md';
if ($avatarWrapperSize == 'xl') {
  $avatarClassSize = 'size-xl';
  $extraTextSize = (isset($extraTextSize)) ? $extraTextSize : 'lead';
} else if ($avatarWrapperSize == 'lg') {
  $avatarClassSize = 'size-lg';
  $extraTextSize = (isset($extraTextSize)) ? $extraTextSize : '';
} else if ($avatarWrapperSize == 'sm') {
  $avatarClassSize = 'size-sm';
  $extraTextSize = (isset($extraTextSize)) ? $extraTextSize : 'small';
  $initials = substr($name, 0, 1);
} else if ($avatarWrapperSize == 'xs') {
  $avatarClassSize = 'size-xs';
  $extraTextSize = (isset($extraTextSize)) ? $extraTextSize : 'small';
  $initials = substr($name, 0, 1);
} else {
  $avatarClassSize = 'size-md';
  $extraTextSize = (isset($extraTextSize)) ? $extraTextSize : 'small';
}

$statoProfilo = (isset($statoProfilo)) ? $statoProfilo : 'Da validare';
if ($statoProfilo == 'Validato') {
  $statoProfiloIcon = 'account-check';
  $statoProfiloClass = 'success';
} else if ($statoProfilo == 'Non validato') {
  $statoProfiloIcon = 'account-cancel';
  $statoProfiloClass = 'danger';
} else if ($statoProfilo == 'Modifica in corso') {
  $statoProfiloIcon = 'account-edit';
  $statoProfiloClass = 'secondary';
} else {
  $statoProfiloIcon = 'account-question';
  $statoProfiloClass = 'warning';
}


$buttonCtaMsgClass = (isset($buttonCtaMsgClass)) ? $buttonCtaMsgClass : 'btn-primary';
$buttonCtaJoinClass = (isset($buttonCtaJoinClass)) ? $buttonCtaJoinClass : 'btn-secondary';
$isNewUser = (isset($isNewUser)) ? $isNewUser : false;


$statoProfilo = (isset($statoProfilo)) ? $statoProfilo : 'Non validato';
if ($statoProfilo == 'Validato') {
    $statoProfiloIcon = 'check';
    $statoProfiloClass = 'success';
} else if ($statoProfilo == 'Non validato') {
    $statoProfiloIcon = 'minus';
    $statoProfiloClass = 'danger';
} else if ($statoProfilo == 'Modifica in corso') {
    $statoProfiloIcon = 'autorenew';
    $statoProfiloClass = 'secondary';
} else {
    $statoProfiloIcon = 'exclamation-thick';
    $statoProfiloClass = 'warning';
}
?>


<div class="user-list-wrapper border border-light p-3 d-flex flex-row flex-column flex-sm-row">
  <div class="avatar-wrapper avatar-extra-text mb-2 mb-sm-0 justify-content-start ">
    <div class="avatar-box-img <?= $avatarWrapperSize ?> position-relative avatar <?= $avatarClassSize ?>">

      <?php if (!(isset($imageAvatar))) : ?>
        <p aria-hidden="true"><?= $initials ?></p>
        <span class="sr-only"><?= $nameSurname ?></span>
        
      <?php else : ?>
        <img src="<?= $imageAvatar ?>" alt="<?= $nameSurname ?>">
        
      <?php endif ?>
      <?php if (!($statoProfilo == 'Validato' && $hideStatusValidated)) : ?>
                <div class="avatar-status bg-<?= $statoProfiloClass ?>" data-toggle="tooltip" title="Stato profilo utente: <?= $statoProfilo ?>">
                    <svg class="icon icon-white">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#<?=$statoProfiloIcon?>"></use>

                    </svg>
                    <span class="sr-only"><?= $statoProfilo ?></span>
                </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="user-info-wrapper ml-0 ml-sm-3 d-flex flex-column flex-grow-1">
    <div class="first-row d-flex">
      <div class="info-container d-flex justify-content-between flex-grow-1 flex-column flex-sm-row">
        <div class="name-manage d-flex">
          
            <a href="<?= $url ?>" class="mr-2">
              <h3 class="font-weight-bold h5 mb-0 avatar-name"><?= $nameSurname ?></h3>
            </a>
          
          
          <?php
            if($isNewUser):
            echo $this->render(
              '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
            );
            endif;
          ?>
        </div>

        <div class="tooltip-container text-secondary small d-flex align-items-center my-2 my-sm-0">
     
          
          <div class="text-truncate d-flex align-items-center" data-toggle="tooltip" title="Collaborazione prevalente: <?= $collaborazionePrevalente ?>">
            <span>
              <svg class="icon icon icon-tertiary p-1 mr-1 rounded-circle border border-tertiary">
                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#briefcase-account"></use>
              </svg>
            </span>

            <span class="sr-only">Collaborazione prevalente:<?= $collaborazionePrevalente ?></span>
          </div>

          <div class="text-truncate d-flex align-items-center" data-toggle="tooltip" title="Facilitatore: <?= $facilitatore ?>">
            <span>
              <svg class="icon icon icon-tertiary p-1 mr-1 rounded-circle border border-tertiary">
                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#assistant"></use>
              </svg>
            </span>
            <span class="sr-only">Facilitatore:<?= $facilitatore ?></span>
          </div>
         

        </div>
       
      </div>
      <div class="manage-container">
      </div>
    </div>
    <?php if($collaborazionePrevalente):?>
      <div class="second-row d-flex">
        <p class="mb-0 small"><?= $collaborazionePrevalente?> </p>
      </div>
    <?php endif; ?>
    <?php if($userEmail): ?>
      <div class="third-row d-flex">
        <span>
          <svg class="icon icon-sm mr-1 ">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#email"></use>
          </svg>
        </span>
        <a href="mailto:<?= $userEmail ?>" title="Invia una mail a <?= $nameSurname ?>">
        <?= $userEmail ?>
        </a>
      </div>
    <?php endif; ?>
    <div class="fourth-row d-flex justify-content-between flex-column flex-sm-row">
      <?= $descrizionrBreve ?>
      <div class="pull-right">
        <a href="#" class="btn btn-primary mt-2 mt-sm-0" title="Visualizza il profilo di <?=$nameSurname ?> ">
          Visualizza
        </a>
      </div>
    </div>
  </div>
</div>

