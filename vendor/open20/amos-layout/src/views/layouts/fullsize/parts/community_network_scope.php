<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\core\user\User;
use open20\amos\layout\Module;
use open20\amos\core\utilities\StringUtils;
use yii\helpers\Url;

$asset = BaseAsset::register($this);

$jsReadMore = <<< JS

$("#moreTextJs .changeContentJs > .actionChangeContentJs").click(function(){
    $("#moreTextJs .changeContentJs").toggle();
    $('html, body').animate({scrollTop: $('#moreTextJs').offset().top - 120},1000);
});
JS;
$this->registerJs($jsReadMore);


$moduleCommunity = Yii::$app->getModule('community');

?>
<?php if (isset($community)) : ?>

    <?php
    open20\amos\community\assets\AmosCommunityAsset::register($this);
    // $viewUrl            = ['/community/join/open-join?id=' . $community->id];
    // $exitUrl            = (!empty(\Yii::$app->homeUrl) ? \Yii::$app->homeUrl : ['/dashboard']);
    // $unsubscribeUrl     = '/community/community/elimina-m2m?' . $community->id . '&targetId=' . \Yii::$app->user->id;
    $fixedCommunityType = (!is_null($moduleCommunity->communityType));
    ?>
    <?php if (!$isLayoutInScope) : ?>

        <div class="network-scope-wrapper scope-community-wrapper">
            <div class="container border-dashed">
                <div class="scope-title-container">
                    <div>
                        <?php if (!empty($community->parent_id) && !empty($community->parent->name)) : ?>
                            <p class="link-all m-b-0 align-items-center p-b-0">
                                <span class="ic ic-community"></span>
                                <?= Module::t('amoscommunity', 'sottocommunity di:') ?>
                                <a href="/community/join/open-join?id=<?= $community->parent_id ?>" class="m-l-5" title="<?= Module::t('amoscommunity', 'Vai alla community principale') . ':' . ' ' . $community->parent->name ?>">
                                    <?= $community->parent->name ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <div class="scope-title">
                            <h1><?= $community->name ?>
                                <small class="control-community">
                                    <?php if (!$fixedCommunityType) : ?>
                                        <?php
                                        switch ($community->community_type_id):
                                            case CommunityType::COMMUNITY_TYPE_CLOSED:
                                                $classType = 'closed';
                                                $textCommunityType = Module::t('amoslayout', 'Community ristretta ai partecipanti');
                                                $iconCommunityType = AmosIcons::show('eye-off');
                                                $tooltipCommunityType = Module::t(
                                                    'amoslayout',
                                                    'Community visibile ai soli partecipanti'
                                                );
                                                break;
                                            case CommunityType::COMMUNITY_TYPE_OPEN:
                                                $classType = 'open';
                                                $textCommunityType = Module::t('amoslayout', 'Community aperta');
                                                $iconCommunityType = AmosIcons::show('lock-open');
                                                $tooltipCommunityType = Module::t(
                                                    'amoslayout',
                                                    'Contenuti disponibili a tutti gli utenti della piattaforma'
                                                );
                                                break;
                                            case CommunityType::COMMUNITY_TYPE_PRIVATE:
                                                $classType = 'private';
                                                $textCommunityType = Module::t('amoslayout', 'Community riservata ai partecipanti');
                                                $iconCommunityType = AmosIcons::show('lock-outline');
                                                $tooltipCommunityType = Module::t(
                                                    'amoslayout',
                                                    'Contenuti disponibili ai soli partecipanti alla community'
                                                );
                                                break;
                                            default:
                                                $classType = '';
                                        endswitch;
                                        ?>
                                        <span class="community-status <?= $classType ?>">
                                            <?=
                                            Html::a(
                                                $iconCommunityType,
                                                'javascript::void(0)',
                                                [
                                                    'title' => $tooltipCommunityType,
                                                    'data-toggle' => 'tooltip'
                                                ]
                                            );
                                            ?>
                                            <?= $textCommunityType ?>
                                        </span>
                                    <?php endif; ?>
                                </small>
                            </h1>

                        </div>

                    </div>
                    <div class="actions-scope">
                        <div class="wrap-icons">
                            <?php
                            echo ContextMenuWidget::widget([
                                'model' => $community,
                                'actionModify' => "/community/community/update?id=" . $community->id,
                                'actionDelete' => "/community/community/delete?id=" . $community->id,
                                'layout' => '@vendor/open20/amos-layout/src/views/widgets/context_menu_widget_network_scope.php'
                            ])
                            ?>
                        </div>
                    </div>
                </div>
                <div class="cta-network-scope flexbox">
                    <a href="/site/to-menu-url?url=/it/community/community/index" class="link-all flexbox align-items-center" title="Visualizza la lista delle community">
                        <span class="am am-arrow-left"></span>
                        <span><?= Module::t('amoscommunity', 'Tutte le community') ?></span>
                    </a>
                    
                    <div class="cta-community">

                        <?php
                        $isOpenCommunity = false;
                        $isClosedCommunity = false;
                        $isPrivateCommunity = false;
                        $isWaitingToSigned = false;

                        $loggedUserId = Yii::$app->getUser()->getId();
                        if (!empty($loggedUserId)) {
                            $userProfile = User::findOne($loggedUserId)->getProfile();
                            $userCommunity = CommunityUtil::getMemberCommunityLogged($community->id);
                        }
                        if (!empty($userProfile) && $userProfile->validato_almeno_una_volta && !is_null($userCommunity)) {
                            if (in_array($userCommunity->status, [CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER, CommunityUserMm::STATUS_WAITING_OK_USER])) {
                                $isWaitingToSigned = true;
                            } else {
                                $isSigned = true;
                            }
                        } else {
                            $isSigned = false;
                        }
                        if ($community->community_type_id == CommunityType::COMMUNITY_TYPE_OPEN) {
                            $isOpenCommunity = true;
                        } else if ($community->community_type_id == CommunityType::COMMUNITY_TYPE_CLOSED) {
                            $isClosedCommunity = true;
                        } else if ($community->community_type_id == CommunityType::COMMUNITY_TYPE_PRIVATE) {
                            $isPrivateCommunity = true;
                        } else {
                        }
                        ?>

                        <?php $isCreatorCommunity = ($community->created_by == \Yii::$app->user->id) && !\Yii::$app->user->can("ADMIN"); ?>
                        <?php // ---------  IS ACTIVE PARTICIPANT  --------
                        ?>
                        <?php if ($isSigned) : ?>
                            <?php if (!$isCreatorCommunity) { ?>
                                <small>
                                    <?= Module::t('amoscommunity', 'Sei iscritto alla community come') . ' ' . Module::t('amoslayout', "{$community->getRoleByUser()}") ?>
                                    <a class="text-danger ml-4" href="<?= Url::to(['/community/community/elimina-m2m', 'id' => $community->id, 'targetId' => \Yii::$app->user->id, 'redirectAction' => \Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Disiscriviti dalla community') . $community->title ?>">
                                        <?= Module::t('amoscommunity', 'disiscriviti') ?>
                                    </a>
                                </small>
                            <?php } ?>
                            <?php // ---------- IS NOT PARTICIPANT  --------
                            ?>
                        <?php else : ?>
                            <?php if ($isOpenCommunity) : ?>
                                <a class="btn btn-primary btn-xs my-3 align-self-start" href="<?= Url::to(['/community/community/join-community', 'communityId' => $community->id, 'redirectAction' => Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Iscriviti alla community') ?> <?= $community->title ?>">
                                    <?= Module::t('amoscommunity', 'Iscriviti alla community') ?>
                                </a>
                            <?php elseif ($isPrivateCommunity) : ?>
                                <?php if ($isWaitingToSigned) : ?>
                                    <div class="button-container w-100 d-flex justify-content-center border-top">
                                        <p class="d-flex align-items-end text-muted mt-4">
                                            <?= Module::t('amoscommunity', 'Richiesta iscrizione inviata')?>
                                            <a href="javascript::void(0)" class="bi-form-field-tooltip-info m-l-5" data-toggle="tooltip" data-html="true" data-original-title="<?= Module::t('amoscommunity', 'Sei in attesa che un community manager convalidi la richiesta per poter accedere alla community') ?>">
                                                <span class="am am-info-outline"></span>
                                                <span class="sr-only"><?= Module::t('amoscommunity', 'Sei in attesa che un community manager convalidi la richiesta per poter accedere alla community') ?></span>
                                            </a>
                                        </p>
                                    </div>
                                <?php else : ?>
                                    <a class="btn btn-primary btn-xs my-3 align-self-start" href="<?= Url::to(['/community/community/join-community', 'communityId' => $community->id, 'redirectAction' => Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Iscriviti alla community') ?> <?= $community->title ?>">
                                        <?= Module::t('amoscommunity', 'Iscriviti alla community') ?>
                                    </a>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php if ($isWaitingToSigned) : ?>
                                    <small>
                                        <?= Module::t('amoscommunity', 'Sei stato invitato nella community come') . ' ' . Module::t('amoslayout', "{$community->getRoleByUser()}") . ':' ?>
                                        <a class="btn btn-xs btn-success" href="<?= Url::to(['/community/community/join-community', 'communityId' => $community->id, 'accept' => '1', 'redirectAction' => Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Accetta invito di iscrizione alla community') . ' ' . $community->title ?>">
                                            <?= Module::t('amoscommunity', 'Accetta') ?>
                                        </a>
                                        <a class="btn btn-xs btn-danger" href="<?= Url::to(['/community/community/join-community', 'communityId' => $community->id, 'accept' => '0', 'redirectAction' => Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Rifiuta invito di iscrizione alla community') . ' ' . $community->title ?>">
                                            <?= Module::t('amoscommunity', 'Rifiuta') ?>
                                        </a>
                                    </small>
                                <?php else : ?>
                                    <!-- -->
                                <?php endif ?>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>

                </div>
                <div class="row p-t-15">
                    <div class="col-md-4">
                        <?php
                        $url = '/img/img_default.jpg';
                        if (!empty($community->communityLogo)) {
                            $url = $community->communityLogo->getUrl('scope_community', false, true);
                        }

                        echo $logo = Html::img(
                            $url,
                            [
                                'alt' => $community->getAttributeLabel('communityLogo'),
                                'class' => 'img-responsive'
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-md-8">
                        <?php
                        $desclen = 350;
                        ?>
                        <?php if (strlen($community->description) <= $desclen) : ?>
                            <?= $community->description ?>
                        <?php else : ?>
                            <div id="moreTextJs">
                                <?php
                                $moreContentTextLink = Module::t('amoslayout', 'espandi descrizione') . ' ' . AmosIcons::show("chevron-down");
                                $moreContentTitleLink = Module::t('amoslayout', 'Leggi la descrizione completa');

                                $lessContentTextLink = Module::t('amoslayout', 'riduci descrizione') . ' ' . AmosIcons::show("chevron-up");
                                $lessContentTitleLink = Module::t('amoslayout', 'Riduci testo');
                                ?>
                                <div class="changeContentJs partialContent">
                                    <?=
                                    StringUtils::truncateHTML($community->description, $desclen)
                                    ?>
                                    <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>"><?= $moreContentTextLink ?></a>
                                </div>
                                <div class="changeContentJs totalContent" style="display:none">
                                    <?= $community->description ?>
                                    <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>"><?= $lessContentTextLink ?></a>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>

                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="network-scope-wrapper scope-community-wrapper scope-small">
            <p class="link-all m-b-0 flexbox align-items-center p-b-0">
                <span class="ic ic-community"></span>
                <!-- < ?php if (!empty($community->parent_id) && !empty($community->parent->name)) : ?>
                    < ?= Module::t('amoscommunity', 'sottocommunity di:') ?>
                < ?php else : ?>
                    < ?= Module::t('amoscommunity', 'community:') ?>
                < ?php endif; ?> -->
                <a href="/community/join/open-join?id=<?= $community->id ?>" class="" title="<?= Module::t('amoscommunity', 'Vai alla community principale') . ':' . ' ' . $community->name ?>">
                    <?= $community->name ?>
                </a>
            </p>
        </div>
    <?php endif ?>
<?php endif ?>