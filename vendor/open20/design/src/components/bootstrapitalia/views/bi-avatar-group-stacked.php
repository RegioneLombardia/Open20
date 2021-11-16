<?php

use open20\amos\admin\AmosAdmin;
use open20\design\Module;

$numberExpose = (isset($numberExpose)) ? $numberExpose :  4;
$baseUrlProfile = '/' . AmosAdmin::getModuleName() . '/user-profile/view?id=';
$removeLink  = (isset($removeLink)) ? $removeLink : false;
$showTooltip  = (isset($showTooltip)) ? $showTooltip : true;
$showAvatarPresence = (isset($showAvatarPresence)) ? $showAvatarPresence : false;

$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'sm';
if ($avatarWrapperSize == 'xl') {
  $avatarClassSize = 'size-xl';
} else if ($avatarWrapperSize == 'lg') {
  $avatarClassSize = 'size-lg';
} else if ($avatarWrapperSize == 'sm') {
  $avatarClassSize = 'size-sm';
} else if ($avatarWrapperSize == 'xs') {
  $avatarClassSize = 'size-xs';
} else {
  $avatarClassSize = 'size-md';
}

?>

<ul class="avatar-group-stacked <?= $additionalClass ?>">
  <?php
  $i = 0;
  for ($i; $i < min([$numberExpose, $usersNumber]); $i++) {
  ?>
    <?php 
    if( $userProfiles[$i]){
      $nameSurname = $userProfiles[$i]->nomeCognome;
      $tooltipText = '<strong>' . $nameSurname . '</strong>' . (isset($tooltipAdditionalInfoAvatarsGroup) ? '<br/><em>' . $tooltipAdditionalInfoAvatarsGroup . '</em>' : '');
      $imageAvatar = $userProfiles[$i]->getAvatarWebUrl('card_users');
      $url = $baseUrlProfile . $userProfiles[$i]->id;
      $arr         = explode(' ', trim($nameSurname));
      $name        = $arr[0];
      $surname     = $arr[1];
      $initials    = (in_array('sm,xs', $avatarWrapperSize)) ? substr($name, 0, 1) : substr($name, 0, 1) . substr($surname, 0, 1);
      $avatarPresence = ($userProfiles[$i]->attivo) ? 'active' : 'hidden';
      if ($avatarPresence == 'hidden') {
          $avatarPresenceTitle = 'Assente';
      } else {
          $avatarPresenceTitle = 'Attivo';
      }
    }
    ?>
    <li class="avatar-wrapper">
      <?php if (!$removeLink || $showTooltip) : ?>
        <a class="avatar <?= $avatarClassSize ?>" href="<?= ($removeLink == true ? '' : $url) ?>" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tooltipText ?>">
          <?php if (!(isset($imageAvatar))) : ?>
            <p aria-hidden="true"><?= $initials ?></p>
            <span class="sr-only"><?= $nameSurname ?></span>
          <?php else : ?>
            <?php if($userProfiles[$i]): ?>
              <img src="<?= $userProfiles[$i]->getAvatarUrl('card_users') ?>" alt="<?= $nameSurname ?>">
            <?php endif ?>

          <?php endif ?>
        </a>
        <?php if ($showAvatarPresence) : ?>
          <div class="avatar-presence <?= $avatarPresence ?>" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Presenza') . ': ' .  $avatarPresenceTitle ?>">
          
              <span class="sr-only"><?= Module::t('amosdesign', 'Presenza: ') . $avatarPresenceTitle ?> </span>
          </div>
        <?php endif ?>
      <?php else : ?>
        <div class="avatar <?= $avatarClassSize ?>">
          <?php if (!(isset($imageAvatar))) : ?>
            <p aria-hidden="true"><?= $initials ?></p>
            <span class="sr-only"><?= $nameSurname ?></span>
          <?php else : ?>
            <img src="<?= $imageAvatar ?>" alt="<?= $nameSurname ?>">
          <?php endif ?>
        </div>
      <?php endif; ?>
    </li>
  <?php }
  if ($i < $usersNumber) {
  ?>
    <!--dropdown-->
    <li>
      <div class="avatar avatar-dropdown <?= $avatarClassSize ?>">
        <div class="dropdown">
          <a class="btn btn-dropdown dropdown-toggle" href="javascript::void(0)" role="button" id="dropdownMenuAvatarGroupStacked" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php $userDropdown = $usersNumber - $numberExpose ?>
            <span class="sr-only"><?= Module::t('amosdesign', 'Visualizza altri {numeroUtenti} utenti',['numeroUtenti' => $userDropdown]) ?></span>
            <span aria-hidden="true"><?= '+' . $userDropdown ?></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuAvatarGroupStacked">
            <div class="link-list-wrapper">
              <ul class="link-list">
                <?php for ($i; $i < $usersNumber; $i++) {  ?>
                  <?php
                  if($userProfiles[$i]): 
                    $nameSurname = $userProfiles[$i]->nomeCognome;
                    $imageAvatar = $userProfiles[$i]->getAvatarWebUrl('card_users');
                    $imageAvatar = str_replace('/it/' ,'/', $imageAvatar);

                    $tooltipText = (isset($tooltipAdditionalInfoAvatarsGroup) ? '<em>' . $tooltipAdditionalInfoAvatarsGroup . '</em>' : '');
                    $url = $baseUrlProfile . $userProfiles[$i]->id;
                    $arr         = explode(' ', trim($nameSurname));
                    $name        = $arr[0];
                    $surname     = $arr[1];
                    $initials    = (in_array('sm,xs', $avatarWrapperSize)) ? substr($name, 0, 1) : substr($name, 0, 1) . substr($surname, 0, 1);
                  endif;
                  ?>
                  <li class="avatar-wrapper">
                    <?php if (!$removeLink || $showTooltip) : ?>
                      <a class="list-item" href="<?= ($removeLink == true ? '' : $url) ?>" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tooltipText ?>">
                        <div class="avatar <?= $avatarClassSize ?>">
                          <?php if (!(isset($imageAvatar))) : ?>
                            <p aria-hidden="true"><?= $initials ?></p>
                            <span class="sr-only"><?= $nameSurname ?></span>
                          <?php else : ?>
                            <?php if($userProfiles[$i]): ?>
                              <img src="<?= $userProfiles[$i]->getAvatarWebUrl('card_users') ?>" alt="<?= $nameSurname ?>">
                            <?php endif ?>

                          <?php endif ?>
                        </div>
                        <span><?= $nameSurname ?></span>
                      </a>
                      <?php if ($showAvatarPresence) : ?>
                        <div class="avatar-presence <?= $avatarPresence ?>" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Presenza') . ': ' .  $avatarPresenceTitle ?>">
                            <span class="sr-only"><?= Module::t('amosdesign', 'Presenza: ') . $avatarPresenceTitle ?> </span>
                        </div>
                      <?php endif ?>
                    <?php else : ?>
                      <div class="list-item">
                        <div class="avatar <?= $avatarClassSize ?>">
                          <?php if (!(isset($imageAvatar))) : ?>
                            <p aria-hidden="true"><?= $initials ?></p>
                            <span class="sr-only"><?= $nameSurname ?></span>
                          <?php else : ?>
                            <img src="<?= $userProfiles[$i]->getAvatarWebUrl('card_users') ?>" alt="<?= $nameSurname ?>">
                          <?php endif ?>
                        </div>
                        <span><?= $nameSurname ?></span>
                      </div>
                    <?php endif; ?>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </li>
  <?php } ?>
</ul>