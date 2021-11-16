<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\community\models\CommunityType;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\helpers\Html;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\layout\Module;
use open20\amos\core\icons\AmosIcons;
use amos\sitemanagement\models\SiteManagementCommunitySliderMm;
use amos\sitemanagement\widgets\SMSliderWidget;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\Module as ModuleOrganizzazione;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

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
if (isset($community)) {
    open20\amos\community\assets\AmosCommunityAsset::register($this);
    $viewUrl            = ['/community/join/index?id=' . $community->id];
    $exitUrl            = (!empty(\Yii::$app->homeUrl) ? \Yii::$app->homeUrl : ['/dashboard']);
    $unsubscribeUrl            = '/community/community/elimina-m2m?' . $community->id . '&targetId=' . \Yii::$app->user->id;
    $fixedCommunityType = (!is_null($moduleCommunity->communityType));

    $org = Profilo::findOne(['community_id' => $community->id]);
?>

    <div class="network-scope-wrapper scope-organizzazioni-wrapper">
        <div class="container border-dashed">
            <div class="scope-title-container">
                <div class="scope-title">
                    <h1><?= $organization->name ?></h1>
                    <a href="/site/to-menu-url?url=/it/organizzazioni/profilo/index" class="link-all align-items-center" title="Visualizza la lista delle organizzazioni">
                        <p><?= ModuleOrganizzazione::t('amosorganizzazioni', 'Tutte le organizzazioni') ?></p>
                        <span class="am am-arrow-right"></span>
                    </a>
                </div>
                <div class="actions-scope">
                    <div class="wrap-icons">
                        <!-- < ?php if (\Yii::$app->user->can(
                                'PROFILO_UPDATE',
                                ['model' => $organization]
                            )) : ?>
                                 <div class="manage ">
                                    <div class="dropdown">
                                        <a class="manage-menu" data-toggle="dropdown" href="" aria-expanded="true" title="Menu contestuale">
                                            <span class="pull-left am am-settings"> </span>                 
                                            <span class="pull-right am am-chevron-down"> </span>                 
                                            <span class="sr-only">Menu contestuale</span>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a href="< ?= $organization->getFullUpdateUrl() ?>" title="Modifica organizzazione" model="Notizia prova3">Modifica organizzazione</a></li>
                                        </ul>
                                    </div> -->




                    </div>
                </div>
            </div>
            <div class="row">
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
                                    <span class="bold mb-0 pb-0 m-r-5 text-white"> Sito web: </span>
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
                                    <span class="bold text-white m-r-5 pb-0 mb-0">Partita IVA:</span>
                                    <span class="text-white pb-0 mb-0"><?= (!empty($organization->partita_iva) ? $organization->partita_iva : '-') ?></span>
                                </p>

                                <p class="info-element p-b-5">
                                    <span class="bold text-white m-r-5 pb-0 mb-0">Ref. operativo:</span>
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
                </div>
                <div class="col-xs-12">
                    <?= $organization->presentazione_della_organizzaz ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END: community data -->

<?php } ?>