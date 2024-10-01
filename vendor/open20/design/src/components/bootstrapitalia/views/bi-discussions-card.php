<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'sm';
$date              = DateUtility::getDate($date);
$numberExpose      = (isset($numberExpose) ? $numberExpose : 4);

$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);

?>



<div class="discussion-container-card card-wrapper card-space">
    <div class="card card-bg">
        <div class="card-body">


            <div class="row variable-gutters">
                <div class="col-lg-7 order-2 order-lg-1">
                    <div class="small">
                        <span><?= $date ?></span>
                    </div>
                    <h3 class="discussion-title h5 mb-1 font-weight-bold">
                        <a class="title-three-line link-list-title" href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Partecipa alla discussione {title} ',['title' => $title]) ?>"><?= $title ?></a>

                    </h3>
                </div>
                <div class="col-lg-5 order-1 order-mb-2 mb-2 mb-lg-0">
                    <div class="image-discussion-wrapper position-relative">
                        <a href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vai alla discussione {title} ',['title' => $title]) ?>">
                            <img src="<?= $image ?>" alt="<?= Module::t('amosdesign', 'Immagine della discussione {title} ',['title' => $title]) ?>" class="img-fluid w-100 mh-100" />
                        </a>
                        <div class="other-info-item d-flex align-items-center pl-3">
                            <?php if (($newPubblication)) : ?>
                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                                );
                                ?>
                            <?php endif ?>
                            <div class="ml-2">
                                <?php
                                    echo $this->render(
                                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                                        [
                                        'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model,
                                            $actionModify, $actionDelete),
                                            'model' => $model
                                        ]
                                    );
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($communityTitle)) : ?>
            <small><strong class="community-title text-secondary"><?= $communityTitle ?></strong></small>
            <?php endif ?>
            <div class="description-discussion text-secondary small mt-2">
                <p class="title-four-line <?php if (isset($lastCommentDiscussion)) : ?> title-three-line <?php endif ?> ">
                    <?= $description ?>
                </p>
            </div>
            <div class="footer-discussion border-bottom border-light pb-3 mb-2">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="author-discussion mr-auto pr-2">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                            [
                            'nameSurname' => $nameSurname,
                            'imageAvatar' => $imageAvatar,
                            'url' => $urlAvatar,
                            'removeLink' => $removeLink,
                            'avatarWrapperSize' => $avatarWrapperSize,
                            'extraTextSize' => $extraTextSize
                            ]
                        );
                        ?>
                    </div>
                    <div class="partecipant-list pl-2">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-group-stacked',
                            [
                            'numberExpose' => $numberExpose,
                            'usersNumber' => $partecipantNumber,
                            'userProfiles' => $avatarsGroup,
                            'tooltipAdditionalInfoAvatarsGroup' => isset($tooltipAdditionalInfoAvatarsGroup) ?: Module::t('amosdesign',
                                    'Partecipante'),
                            'removeLink' => $removeLink,
                            ]
                        );
                        ?>
                    </div>

                </div>
            </div>


            <?php
            echo $this->render(
                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-last-comment-discussion',
                [
                'lastCommentDiscussion' => $lastCommentDiscussion,
                'nameSurnameLastComment' => $nameSurnameLastComment
                ]
            );
            ?>

            <a class="read-more" href="<?= $url ?>" title="<?= $titleReadMore ?>">
                <span class="text-uppercase"><?= Module::t('amosdesign', 'Partecipa') ?></span>
            </a>

        </div>
    </div>
</div>