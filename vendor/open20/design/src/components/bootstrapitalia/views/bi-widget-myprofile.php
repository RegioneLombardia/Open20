<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    DesignPackage
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$urlTagManager = (isset($urlTagManager)) ? $urlTagManager : false;
$ariaExpanded = 'true';
$classCollapsed = '';
$widgetOpened = 'collapse show';
if (!$open) {
    $widgetOpened =  'collapse';
    $ariaExpanded = 'false';
    $classCollapsed = 'collapsed';
}
$userprofileGroup = [];
$maxTags = 5;
foreach ($contacts as $contact) {
    $userprofileGroup[] = $contact['userProfile'];
}
?>

<div class="bi-widget-myprofile-wrapper bg-secondary py-3">
    <div class="container">
        <div class="d-flex align-items-center flex-wrap position-relative">
            <?=
            $this->render(
                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                [

                    'imageAvatar' => $avatarUrl,
                    'url' => $userProfileUrl,
                    'additionalInfo' => $prevalentPartnershipName,
                    //          'extraTextSize' => $extraTextSize,
                    'nameSurname' => $nameSurname,
                    'showAvatar' => true,
                    'avatarWrapperSize' => 'xl',
                    'showWelcomeLabel' => true,
                    'isFacilitator' => $isFacilitator,
                    'isCommunityManager' => $isCommunityManager,

                ]
            );
            ?>
            <?php if (!empty($tags)) : ?>
                <?php
                if ($totCounter > 0) {
                    $labelNContent =  Module::t('amosdesign', 'Ci sono') . '<span class="badge badge-pill badge-white p-1 text-secondary mx-1">' . $totCounter . '</span>' . Module::t('amosdesign', 'nuovi contenuti di tuo interesse');
                } else {
                    $labelNContent =  Module::t('amosdesign', 'Espandi');
                }
                ?>

                <a class="dropdown-toggle my-3  ml-auto text-white text-decoration-none <?= $classCollapsed ?>" data-toggle="collapse" href="#widgetMyProfile" role="button" aria-expanded="<?= $ariaExpanded ?>" aria-controls="widgetMyProfile">
                    <u><small><?= $labelNContent ?></small></u>
                    <svg class="icon-expand icon icon-sm icon-light">
                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                    </svg>
                </a>
            <?php else : ?>
                <div class="alert alert-warning mb-0 mt-3 mt-lg-2 ml-auto" role="alert">
                    <small><?= Module::t('amosdesign', 'Quali sono i tuoi interessi?') ?>
                        <?php if ($urlTagManager && !empty($urlTagManager)) : ?>
                            <a href="<?= $urlTagManager ?>" class="alert-link text-warning"><?= Module::t('amosdesign', 'Selezionali ora') . '.' ?></a>
                        <?php else : ?>
                            <?= Module::t('amosdesign', 'Selezionali dal tuo profilo') ?>
                        <?php endif; ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
      
        <?php if (!empty($tags)) : ?>
            <div id="widgetMyProfile" class="<?= $widgetOpened ?>">
                <div class="collapse-body mt-2 px-0 pt-3 pb-2">
                    <div class="user-tags-wrapper pb-3">
                        <div class="row">
                            <div class="col">
                                <div>
                                
                                    
                                    <?php foreach ($tags as $tag) { ?>

                                        <?=

                                        $this->render(
                                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-tag',
                                            [
                                                'label' => $tag->nome,
                                                'depth' => '1',
                                                'path' => $tag->path,
                                                'tagRoot' => $tag->tagRoot->nome

                                            ]
                                        );
                                        ?>
                                       
                                    <?php
                                        if ($i == $maxTags) {
                                            break;
                                        }
                                        $i++;
                                    } ?>
                                   
                                </div>
                            </div>
                            <div class="col-md-2 text-right">
                                <?php if (count($tags) > $maxTags) { ?>
                                    <a class="dropdown-toggle ml-auto my-3 text-white text-decoration-none" data-toggle="collapse" href="#allTags" role="button" aria-expanded="false" aria-controls="allTags">
                                        <u><small><?= Module::t('amosdesign', 'Vedi tutti') ?></small></u>
                                        <svg class="icon-expand icon icon-sm icon-light">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                        </svg>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div id="allTags" class="collapse">
                            <div>
                                <?php $j = 1; ?>
                                <?php foreach ($tags as $tag) {
                                    if ($j > $i) {
                                ?>
                                        <?=
                                        $this->render(
                                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-tag',
                                            [
                                                'label' => $tag->nome,
                                                'depth' => $tag->lvl,
                                                'path' => $tag->path,
                                                'tagRoot' => $tag->tagRoot->nome

                                            ]
                                        );
                                        ?>
                                <?php
                                    }
                                    $j++;
                                } ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($totCounter > 0) : ?>
                        <div class="new-contents-wrapper py-3 mb-3">
                            <div class="row variable-gutters">

                                <?php foreach ($counters as $count) { ?>
                                    <a href="<?= $count['url'] ?>" class="box-item-number-profile col-md-3 col-sm-6" title="<?= Module::t('amosdesign', 'Vai alle') . ' ' . $count['name']  ?>">
                                        <div>
                                            <span class="number-profile"><?= $count['counter'] ?></span>
                                        </div>
                                        <span class="info-number-profile font-weight-semibold"><?= $count['name'] ?></span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="last-contents-wrapper">
                        <div class="row variable-gutters">
                            <div class="col-md-5 mt-3">
                                <div class="box-last-contents">
                                    <div class="row h-100">
                                        <div class="col-lg-6">
                                            <p class="small text-uppercase font-weight-bold mb-0"><?= Module::t('amosdesign', 'Ultimi utenti con cui sei entrato in contatto') ?></p>
                                        </div>
                                        <div class="col-lg-6 d-md-flex justify-content-md-end align-items-end">
                                       
                                            <?php echo
                                            $this->render(
                                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-group-stacked',
                                                [
                                                    'numberExpose' => 6,
                                                    'usersNumber' => count($contacts),
                                                    'userProfiles' => $userprofileGroup,
                                                    'tooltipAdditionalInfoAvatarsGroup' => isset($tooltipAdditionalInfoAvatarsGroup) ?: Module::t(
                                                        'amosdesign',
                                                        'Partecipante'
                                                    ),
                                                    'removeLink' => false,
                                                   

                                                ]
                                            );
                                            ?>


                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-7 mt-3">
                                <div class="box-last-contents">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <p class="small text-uppercase font-weight-bold"><?= Module::t('amosdesign', 'Ultime community cui hai aderito') ?></p>
                                        </div>
                                        <div class="col-lg-9 d-flex flex-wrap justify-content-end align-items-end">
                                            <?php foreach ($communities as $community) { ?>
                                                <div class="community-image rounded ml-1 ml-md-3">
                                                    <a href="<?= '/community/join/open-join?id=' . $community['id'] ?>" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Vai alla community: ') . $community['name'] ?>">
                                                        <img alt="immagine community" class="h-100 w-100" src="<?= $community['communityLogoUrl'] ?>">
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
