<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\community\models\CommunityType;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\layout\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\Module as ModuleOrganizzazione;
use open20\amos\core\user\User;
use yii\helpers\Url;
use open20\amos\core\helpers\Html;
use yii\helpers\StringHelper;
use open20\amos\core\utilities\StringUtils;
use open20\amos\community\widgets\JoinCommunityWidget;

/**
 * @var \open20\amos\organizzazioni\Module $organizzazioniModule
 * @var \open20\amos\organizzazioni\models\Profilo $organization
 */

$operativeHeadquarter = $organization->operativeHeadquarter;
$legalHeadquarter = $organization->legalHeadquarter;
$sameHeadquarter = $organization->la_sede_legale_e_la_stessa_del;

$legalRepresentative = $organization->rappresentanteLegale;
$legalRepresentativeExists = (!is_null($legalRepresentative));
$operativeReferee = $organization->referenteOperativo;
$operativeRefereeExists = (!is_null($operativeReferee));

$asset = BaseAsset::register($this);

/** @var AmosCommunity $moduleCommunity */
$moduleCommunity = Yii::$app->getModule('community');
$organizzazioniModule = Yii::$app->getModule('organizzazioni');
$loggedUserId = Yii::$app->user->id;

$showButton = false;
$waitingOkUser = false;
$button = [
    'title' => '',
    'url' => '#',
    'options' => [
        'class' => 'link-all flexbox align-items-center',
    ]
];
$communityPresent = (!is_null($organization->community) && is_null($organization->community->deleted_at));


if ($organizzazioniModule->enableCommunityCreation) {
    if (!$communityPresent) {
        if (in_array($loggedUserId, [$organization->rappresentante_legale, $organization->referente_operativo])) {
            $button['title'] = Module::t('amosorganizzazioni', '#create_community_for_organization');
            $button['url'] = ['/' . Module::getModuleName() . '/profilo/create-community/', 'id' => $organization->id];
            $button['options'] = [
                'class' => 'btn btn-secondary',
                'title' => Module::t('amosorganizzazioni', '#create_community_for_organization_title'),
                'data-confirm' => Module::t('amosorganizzazioni', '#create_community_for_organization_question')
            ];
            $showButton = true;
        }
    } else {
        $userInList = false;
        foreach ($organization->communityUserMm as $userCommunity) { // User not yet subscribed to the event
            if ($userCommunity->user_id == $loggedUserId) {
                $userInList = true;
                $userStatus = $userCommunity->status;
                break;
            }
        }

        if ($userInList === true) {
            $showButton = true;
            switch ($userStatus) {
                case CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER:
                    $button['title'] = Module::t('amosorganizzazioni', '#request_sent');
                    $button['options']['class'] .= ' disabled';
                    break;
                case CommunityUserMm::STATUS_WAITING_OK_USER:
                    $waitingOkUser = true;
                    $button['title'] = Module::t('amosorganizzazioni', '#accept_invitation');
                    $button['url'] = [
                        '/community/community/accept-user',
                        'communityId' => $organization->community_id,
                        'userId' => $loggedUserId
                    ];
                    $button['options']['data']['confirm'] = Module::t('amosorganizzazioni', '#accept_invitation_question');
                    break;
                case CommunityUserMm::STATUS_ACTIVE:
                    $createUrlParams = [
                        '/community/join/open-join',
                        'id' => $organization->community_id
                    ];
                    $button['title'] = '<span>' . Module::t('amosorganizzazioni', '#visit_community_btn_title') . '</span>' . '<span class="am am-arrow-right"></span>';
                    $button['url'] = \Yii::$app->urlManager->createUrl($createUrlParams);
                    break;
            }
        } else if ($community->community_type_id == CommunityType::COMMUNITY_TYPE_PRIVATE) {
            $showButton = true;
            $isCommunityParticipant = CommunityUserMm::findOne([
                'user_id' => Yii::$app->user->id,
                'community_id' => $organization->community_id
            ]);
            if (!$isCommunityParticipant) {
                $createUrlParams = [
                    '/community/community/join-community',
                    'communityId' => $organization->community_id,
                    'redirectAction' => Yii::$app->request->url
                ];
                $button['title'] = Module::t('amoscommunity', 'Iscriviti alla community');
                $button['url'] = \Yii::$app->urlManager->createUrl($createUrlParams);
                $button['options'] = [
                    'class' => 'btn btn-primary btn-xs my-3 align-self-start'    ,
                    'title' => Module::t('amoscommunity', 'Richiedi l\'iscrizione alla community dedicata a {organizationName}', ['organizationName' => $organization->name])
                ];
            } else {
                $createUrlParams = [
                    '/community/join/open-join',
                    'id' => $organization->community_id,
                ];
                $button['title'] = '<span>' . Module::t('amosorganizzazioni', '#visit_community_btn_title') . '</span>' . '<span class="am am-arrow-right"></span>';
                $button['url'] = \Yii::$app->urlManager->createUrl($createUrlParams);
                $button['options'] = [
                    'title' => Module::t('amoscommunity', 'Accedi alla community dedicata a {organizationName}', ['organizationName' => $organization->name])
                ];
            }

        }
    }
}

if (isset($community)) {
    open20\amos\community\assets\AmosCommunityAsset::register($this);
    $fixedCommunityType = (!is_null($moduleCommunity->communityType));
    $org = Profilo::findOne(['community_id' => $community->id]);
?>

    <?php if (!$isLayoutInScope) : ?>

        <div class="network-scope-wrapper scope-organizzazioni-wrapper">
            <?php if(Yii::$app->getModule('layout')->open2CommunityOrganizationView){
                echo '<div class="container">';
                }else{ 
                    echo '<div class="container border-dashed">';
                }
?> 
            
                <div class="scope-title-container">
                    <div class="scope-title">
                        <?php if (isset($this->params['CommunityParams']['outsideCommunity']) && $this->params['CommunityParams']['outsideCommunity']) : ?>
                            <h1><?= $organization->name ?></h1>
                        <?php else : ?>
                            <h1><?= $organization->name ?></h1>
                            <a href="/site/to-menu-url?url=/it/organizzazioni/profilo/view?id=<?= $organization->id ?>" class="link-all flexbox align-items-center" title="Visualizza la scheda dell'organizzazione">
                                <span><?= Module::t('amosorganizzazioni', 'Scheda organizzazione') ?></span>
                                <span class="mdi mdi-domain m-l-5"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="actions-scope">
                        <div class="wrap-icons">
                            <?php
                            echo ContextMenuWidget::widget([
                                'model' => $organization,
                                'actionModify' => "/organizzazioni/profilo/update?id=" . $organization->id,
                                'actionDelete' => "/organizzazioni/profilo/delete?id=" . $organization->id,
                                'layout' => '@vendor/open20/amos-layout/src/views/widgets/context_menu_widget_network_scope.php'
                            ])
                            ?>
                        </div>
                    </div>
                </div>
                <?php if (!empty($community->parent_id) && !empty($community->parent->name)) : ?>
                    <p class="link-all m-b-0 align-items-center p-b-0">
                        <span class="ic ic-community m-r-5"></span>
                        <?= Module::t('amoscommunity', 'sottocommunity di:') ?>
                        <a href="/community/join/open-join?id=<?= $community->parent_id ?>" class="m-l-5" title="<?= Module::t('amoscommunity', 'Vai alla community principale') . ':' . ' ' . $community->parent->name ?>">
                            <?= $community->parent->name ?>
                        </a>
                    </p>
                <?php endif; ?>
                <div class="cta-network-scope flexbox">

                    <a href="/site/to-menu-url?url=/it/organizzazioni/profilo/index" class="link-all flexbox align-items-center" title="Visualizza la lista delle organizzazioni">
                        <span class="am am-arrow-left"></span>
                        <span><?= Module::t('amosorganizzazioni', 'Tutte le organizzazioni') ?></span>
                    </a>

                    <div class="cta-community">

                        <?php if (isset($this->params['CommunityParams']['outsideCommunity']) && $this->params['CommunityParams']['outsideCommunity']) : ?>

                            <?php if (!$organizzazioniModule->enableWorkflow || ($organizzazioniModule->enableWorkflow && ($organization->status == $organization->getValidatedStatus()))) : ?>
                                <?php if ($showButton) : ?>
                                    <?php if ($waitingOkUser) : ?>
                                        <?= JoinCommunityWidget::widget(['model' => $organization->community]); ?>
                                    <?php else : ?>
                                        <?= Html::a($button['title'], $button['url'], $button['options']); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>

                        <?php else : ?>
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
                            if (!empty($userProfile) && !is_null($userCommunity)) {
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
                                        <?= Module::t('amoscommunity', 'Sei iscritto alla community come') . ' ' . Module::t('amoslayout', "{$community->getRoleByUser()}") . ' | ' ?>
                                        <a class="text-danger ml-4" href="<?= Url::to(['/community/community/elimina-m2m', 'id' => $community->id, 'targetId' => \Yii::$app->user->id, 'redirectAction' => \Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Disiscriviti dalla community') . $community->title ?>">
                                            <?= Module::t('amoscommunity', 'disiscriviti') ?>
                                        </a>
                                    </small>
                                <?php } ?>
                                <?php // ---------- IS NOT PARTICIPANT  --------
                                ?>
                            <?php else : ?>
                                <?php if ($userProfile && $userProfile->validato_almeno_una_volta) { ?>
                                    <?php if ($isOpenCommunity) : ?>
                                        <a class="btn btn-primary btn-xs my-3 align-self-start" href="<?= Url::to(['/community/community/join-community', 'communityId' => $community->id, 'redirectAction' => Yii::$app->request->url]) ?>" title="<?= Module::t('amoscommunity', 'Iscriviti alla community') ?> <?= $community->title ?>">
                                            <?= Module::t('amoscommunity', 'Iscriviti alla community') ?>
                                        </a>
                                    <?php elseif ($isPrivateCommunity) : ?>
                                        <?php if ($isWaitingToSigned) : ?>
                                            <div class="button-container w-100 d-flex justify-content-center border-top">
                                                <p class="d-flex align-items-end text-muted mt-4">
                                                    <?= Module::t('amoscommunity', 'Richiesta iscrizione inviata') ?>
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
                                                <a class="btn btn-xs btn-success" href="<?= Url::to(['/community/community/join-community', 'communityId' => $community->id, 'accept' => '1', 'redirectAction' => '/community/join/open-join?id=' . $community->id]) ?>" title="<?= Module::t('amoscommunity', 'Accetta invito di iscrizione alla community') . ' ' . $community->title ?>">
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
                                <?php } else { ?>
                                    <a class="btn btn-primary btn-xs my-3 align-self-start" disabled="true" href="<?= 'javascript:void(0)' ?>" title="<?= Module::t('amoscommunity', "Devi essere validato per poter effettuare l'iscrizione alla community") ?> <?= $community->title ?>">
                                        <?= Module::t('amoscommunity', 'Iscriviti alla community') ?>
                                    </a>
                                <?php } ?>

                            <?php endif; ?>
                        <?php endif; ?>

                    </div>

                </div>
                <?php if(Yii::$app->getModule('layout')->open2CommunityOrganizationView == false){ ?>
                <div class="row p-t-15">
                    <div class="col-sm-4">
                        <?php
                        $url = '/img/img_default.jpg';
                        if (!is_null($organization->logoOrganization)) {
                            $url = $organization->logoOrganization->getUrl('original', [
                                'class' => 'img-responsive'
                            ]);
                        }
                        ?>
                        <img class="img-responsive" src="<?= $url ?>" alt="<?= $organization->name ?>">
                    </div>
                    <div class="col-sm-8">
                        <div class="other-info flexbox flexbox-column small flex-grow-1">
                            <div class="row flex-grow-1">
                                <div class="col-sm-6 m-b-10 m-t-10">

                                    <p class="info-element p-b-5">
                                        <span class="bold mb-0 pb-0 m-r-5 text-white"> <?= Module::t('amosorganizzazioni', 'Sito web') . ':' ?> </span>
                                        <span>
                                            <?php if (!empty($organization->sito_web)) :
                                                $httpString = '';
                                                if ((StringHelper::startsWith($organization->sito_web, 'http://', false)) || (StringHelper::startsWith($organization->sito_web, 'https://', false))) {
                                                    $httpString = '';
                                                } else {
                                                    $httpString = 'https://';
                                                }
                                            ?>
                                                <a href="<?= $httpString . $organization->sito_web ?>" title="<?= $organization->sito_web ?>" class="font-weight-normal text-truncate text-white p-0 mb-0 ml-1" target="_blank"><?= $organization->sito_web ?><?= AmosIcons::show('open-in-new', ['class' => 'icon icon-white p-2 m-l-5'], 'am') ?></a>
                                            <?php else : ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </span>
                                    </p>

                                    <p class="info-element p-b-5">
                                        <span class="bold text-white m-r-5 pb-0 mb-0"><?= Module::t('amosorganizzazioni', 'Partita IVA') . ':' ?></span>
                                        <span class="text-white pb-0 mb-0"><?= (!empty($organization->partita_iva) ? $organization->partita_iva : '-') ?></span>
                                    </p>

                                    <p class="info-element p-b-5">
                                        <span class="bold text-white m-r-5 pb-0 mb-0"><?= Module::t('amosorganizzazioni', 'Ref. operativo') . ':' ?></span>
                                        <span class="text-white pb-0 mb-0"><?= ($operativeRefereeExists ? $operativeReferee->nomeCognome : '-') ?></span>
                                    </p>
                                </div>
                                <div class="col-sm-6 m-b-10 m-t-10 flexbox-column">
                                    <p class="info-element p-b-5">

                                        <?= AmosIcons::show('pin', ['class' => 'icon icon-white p-2 m-r-5'], 'am') ?>
                                        <span> <?= $organization->getAddressField() ?> </span>

                                    </p>
                                    <p class="info-element p-b-5">
                                        <?php if (!empty($operativeHeadquarter->email)) : ?>
                                            <?= AmosIcons::show('email', ['class' => 'icon icon-white p-2 m-r-5'], 'am') ?>
                                            <a class="text-white text-decoration-underline pb-0 mb-0" href="mailto:<?= $operativeHeadquarter->email ?>">

                                                <?= $operativeHeadquarter->email ?>
                                            </a>
                                        <?php else : ?>
                                            <?= AmosIcons::show('email', ['class' => 'icon icon-white p-2 m-r-5'], 'am') ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="info-element p-b-5">
                                        <span class="text-white pl-0 pb-0 mb-0">
                                            <?= AmosIcons::show('phone', ['class' => 'icon icon-white p-2 m-r-5'], 'dash') ?>
                                            <?= (!empty($operativeHeadquarter->phone) ? $operativeHeadquarter->phone : '-') ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                        $desclen = 350;
                        ?>
                        <?php if (strlen($organization->presentazione_della_organizzaz) <= $desclen) : ?>
                            <?= $organization->presentazione_della_organizzaz ?>
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
                                    StringUtils::truncateHTML($organization->presentazione_della_organizzaz, $desclen)
                                    ?>
                                    <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>"><?= $moreContentTextLink ?></a>
                                </div>
                                <div class="changeContentJs totalContent" style="display:none">
                                    <?= $organization->presentazione_della_organizzaz ?>
                                    <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>"><?= $lessContentTextLink ?></a>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div> 
            <?php } ?>
            </div>
        </div>
        <!-- END: community data -->
    <?php else : ?>
        <div class="network-scope-wrapper scope-community-wrapper scope-small">
            <p class="link-all m-b-0 flexbox align-items-center p-b-0">
                <span class="ic ic-community m-r-5"></span>
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

<?php } ?>