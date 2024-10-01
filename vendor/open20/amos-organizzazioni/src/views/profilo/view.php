<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\MapWidget;
use open20\amos\core\forms\editors\maps\PlaceWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\assets\OrganizzazioniAsset;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\widgets\JoinProfiloWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\organizzazioni\models\Profilo $model
 */

$mainLegalHeadquarter = $model->legalHeadquarter;
if (!is_null($mainLegalHeadquarter)) {
    $model->mainLegalHeadquarterAddress = $mainLegalHeadquarter->address;
}
$moduleL = \Yii::$app->getModule('layout');
if (!empty($moduleL)) {
    OrganizzazioniAsset::register($this);
}
$mainOperativeHeadquarter = $model->operativeHeadquarter;
if (!is_null($mainOperativeHeadquarter)) {
    $model->mainOperativeHeadquarterAddress = $mainOperativeHeadquarter->address;
}

$jsReadMore = <<< JS

$("#moreTextJs .changeContentJs > .actionChangeContentJs").click(function(){
    $("#moreTextJs .changeContentJs").toggle();
    $('html, body').animate({scrollTop: $('#moreTextJs').offset().top - 120},1000);
});
JS;
$this->registerJs($jsReadMore);

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = $this->title;

/** @var Module $organizzazioniModule */
$organizzazioniModule = Yii::$app->getModule(Module::getModuleName());

/** @var ProfiloSedi $emptyProfiloSedi */
$emptyProfiloSedi = Module::instance()->createModel('ProfiloSedi');

$operativeHeadquarter = $model->operativeHeadquarter;
$hasOperativeHeadquarter = !empty($operativeHeadquarter);

$loggedUserId = Yii::$app->user->id;
/** @var User $loggedUser */
$loggedUser = Yii::$app->user->identity;
$loggedUserProfile = $loggedUser->userProfile;

$legalHeadquarter = $model->legalHeadquarter;
$hasLegalHeadquarter = !is_null($legalHeadquarter);

if (!is_null($model->logoOrganization)) {
    $url = $model->logoOrganization->getUrl('original', [
        'class' => 'img-responsive'
    ]);
} else {
    $url = '/img/img_default.jpg';
} 

$classSectionSedi = ($organizzazioniModule->forceSameSede || !$hasLegalHeadquarter || $model->la_sede_legale_e_la_stessa_del) ? 'col-md-12' : 'col-md-6';

?>

<div class="organizzazioni-view">
    <div class="info-view-header">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <a class="cta-link-all" href="/organizzazioni/profilo/index">
                        <span class="mdi mdi-arrow-left-circle-outline"></span> <?=  Module::t('amosorganizzazioni', 'Tutte le organizzazioni') ?>
                    </a>
                </div>
                <div class="col-xs-12 col-md-2">
                    <?= Html::img($url, ['alt' => $imgTitle, 'class' => 'img-organizzazione img-responsive'])?>                
                </div>
                <div class="col-xs-12 col-md-10">
                    <div class="title-btn-container">
                        <h1><?= $model->name ?></h1>
                        <?php if (!$organizzazioniModule->enableWorkflow || ($organizzazioniModule->enableWorkflow && ($model->status == $model->getValidatedStatus()))) : ?>
                            <?php if (!$model->userIsEmployee($loggedUserId) && Yii::$app->user->can('ASSOCIATE_ORGANIZZAZIONI_TO_USER', ['model' => $loggedUserProfile])) : ?>

                                <?= JoinProfiloWidget::widget([
                                    'model' => $model,
                                    'userId' => $loggedUserId,
                                    'btnClass' => 'btn btn-navigation-secondary',
                                    'customBtnLabel' => Module::t('amosorganizzazioni', '#ask_to_be_employee'),
                                ]) ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php
                        $profiloEntiTypeNotNull = (!is_null($model->profiloEntiType));
                        $profiloTipoStrutturaNotNull = (!is_null($model->tipologia_struttura_id));
                    ?>
                    <!-- if without else because the entity type must be present -->
                    <?php if ($profiloEntiTypeNotNull && ($organizzazioniModule->enableProfiloEntiType === true)) : ?>
                        <?php if ($model->isMunicipality()) : ?>
                            <div>
                                <p class="lead"><span class="mdi mdi-domain"></span><strong><?= $model->getAttributeLabel('istat_code') ?>:</strong> <?= !empty($model->istat_code) ? $model->istat_code : '' ?></p>
                            </div>
                        <?php elseif ($model->isOtherEntity() && ($organizzazioniModule->enableTipologiaOrganizzazione === true)) : ?>
                            <div>
                                <p class="lead"><span class="mdi mdi-domain"></span><strong><?= $model->getAttributeLabel('tipologia_di_organizzazione') ?>:</strong> <?= !empty($model->tipologiaDiOrganizzazione) ? $model->tipologiaDiOrganizzazione->name : '-' ?></p>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($organizzazioniModule->enableTipologiaOrganizzazione === true) : ?>
                        <div>
                            <p class="lead"><span class="mdi mdi-domain"></span><strong><?= $model->getAttributeLabel('tipologia_di_organizzazione') ?>:</strong> <?= !empty($model->tipologiaDiOrganizzazione) ? $model->tipologiaDiOrganizzazione->name : '-' ?></p>
                        </div>
        
                    <?php endif; ?>
                    <?php if ($organizzazioniModule->enableProfiloTipologiaStruttura === true) : ?>
                        <div>
                            <p class="lead"><span class="mdi mdi-domain"></span><strong><?= $model->getAttributeLabel('tipologia_struttura_id') ?>:</strong> <?= $profiloTipoStrutturaNotNull ? $model->tipologiaStruttura->name : '' ?></p>
                        </div>
                            
                    <?php endif; ?>
                </div>
                <div class="col-xs-12 col-md-12">

                    
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <p class="one-line-text"><strong><?= $model->getAttributeLabel('partita_iva') ?>:</strong> <?= ($model->partita_iva ? $model->partita_iva : '-') ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <p class="one-line-text"><strong><?= $model->getAttributeLabel('codice_fiscale') ?>:</strong> <?= ($model->codice_fiscale ? $model->codice_fiscale : '-') ?></p>
                        </div>
                        <?php if ($organizzazioniModule->enableProfiloEntiType === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <p class="one-line-text"><strong><?= $model->getAttributeLabel('profilo_enti_type_id') ?>:</strong> <?= $profiloEntiTypeNotNull ? $model->profiloEntiType->name : '' ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($organizzazioniModule->enableUniqueSecretCodeForInvitation && Yii::$app->user->can('PROFILO_UPDATE', ['model' => $model])) : ?>
                            <div class="col-xs-12 col-md-6">
                                <p class="one-line-text"><strong><?= $model->getAttributeLabel('unique_secret_code') ?>:</strong> <?= $model->unique_secret_code ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($organizzazioniModule->enableFormaLegale === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <p class="one-line-text"><strong><?= $model->getAttributeLabel('forma_legale') ?>:</strong> <?= !empty($model->formaLegale) ? $model->formaLegale->name : '-' ?></p>
                            </div>
                        <?php endif; ?>
                        
                    </div>
             
                    
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="main-info m-b-30">
            <div class="row">
                <?php if (!empty($operativeHeadquarter->email)) : ?>
                    <div class="col-xs-12 col-md-4">
                        <p><span class="mdi mdi-email"></span> <strong><?=Module::t('amosorganizzazioni', 'Email')?>: </strong><?=$operativeHeadquarter->email ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($operativeHeadquarter->phone)) : ?>
                    <div class="col-xs-12 col-md-4">
                        <p><span class="mdi mdi-phone"></span> <strong><?=$emptyProfiloSedi->getAttributeLabel('phone')?>: </strong><?= $operativeHeadquarter->phone?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($model->sito_web)) : ?>
                    <div class="col-xs-12 col-md-4">
                        <p >
                            <span class="mdi mdi-web"></span>
                            <strong>
                                <a href="<?=$model->sito_web?>" title="<?= Module::t('amosorganizzazioni', '#visit_website_btn_title') ?>" target="_blank" class="text-decoration-none text-black"><?= Module::t('amosorganizzazioni', '#visit_website_btn_title') ?></a> 
                            </strong>
                            
                        </p>
                    </div>
                <?php endif; ?>
                <?php if(!empty($operativeHeadquarter->pec)): ?>
                    <div class="col-xs-12 col-md-4">
                        <p><span class="mdi mdi-shield-check"></span> <strong><?=Module::t('amosorganizzazioni', 'PEC')?>: </strong><?= $operativeHeadquarter->pec ?></p>
                    </div>
                <?php endif; ?>
                <?php if(!empty($operativeHeadquarter->fax)): ?>
                    <div class="col-xs-12 col-md-4">
                        <p><span class="mdi mdi-fax"></span> <strong><?=$emptyProfiloSedi->getAttributeLabel('fax')?>: </strong><?= $operativeHeadquarter->fax?></p>
                    </div>
                <?php endif; ?>
                <?php if ($organizzazioniModule->enableSocial) : ?>

                    <div class="col-xs-12 col-md-4">
                        <p>
                            <span class="mdi mdi-thumb-up"></span> <strong><?=$emptyProfiloSedi->getAttributeLabel('Social')?>: </strong>
                            <a href="<?= $model->facebook?>" title="Vai alla pagina Facebook"><span class="mdi mdi-facebook"></span></a>
                            <a href="<?= $model->linkedin?>" title="Vai alla pagina Linkedin"><span class="mdi mdi-linkedin"></span></a>
                            <a href="<?= $model->twitter?>" title="Vai alla pagina Twitter"><span class="mdi mdi-twitter"></span></a>
                        </p>

                       
                    </div>
                <?php endif; ?>
          
            </div>
            <hr class="m-t-30 m-b-30 border-dotted" style="border-style:dotted">
            <div class="row">
                <!--TODO STAMPA MAPPA-->
                <div class="col-xs-12 col-md-6">
                    <?php if (!$model->la_sede_legale_e_la_stessa_del) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <p><span class="mdi mdi-map-marker"></span> <strong><?=Module::t('amosorganizzazioni', 'SEDE LEGALE')?>: </strong></p>
                                <?php 
                                    if ($hasLegalHeadquarter) {
                                            $slIndirizzo = Html::tag(
                                                'div',
                                                Html::tag(
                                                    'div',
                                                    $legalHeadquarter->getAttributeLabel('address'),
                                                    ['class' => 'col-xs-12 col-sm-4 info-label']
                                                ) .
                                                    Html::tag(
                                                        'div',
                                                        $model->getAddressFieldSedeLegaleForView(),
                                                        ['class' => 'col-xs-12 col-sm-8 info-value']
                                                    ),
                                                ['class' => 'col-xs-12 nop']
                                            );

                                            $slEmail = Html::tag(
                                                'div',
                                                Html::tag(
                                                    'div',
                                                    $legalHeadquarter->getAttributeLabel('email'),
                                                    ['class' => 'col-xs-12 col-sm-4 info-label']
                                                ) .
                                                    Html::tag(
                                                        'div',
                                                        $legalHeadquarter->email,
                                                        ['class' => 'col-xs-12 col-sm-8 info-value']
                                                    ),
                                                ['class' => 'col-xs-12 nop']
                                            );

                                            $slPec = Html::tag(
                                                'div',
                                                Html::tag(
                                                    'div',
                                                    $legalHeadquarter->getAttributeLabel('pec'),
                                                    ['class' => 'col-xs-12 col-sm-4 info-label']
                                                ) .
                                                    Html::tag(
                                                        'div',
                                                        $legalHeadquarter->pec,
                                                        ['class' => 'col-xs-12 col-sm-8 info-value']
                                                    ),
                                                ['class' => 'col-xs-12 nop']
                                            );

                                            $slTelefono = Html::tag(
                                                'div',
                                                Html::tag(
                                                    'div',
                                                    $legalHeadquarter->getAttributeLabel('phone'),
                                                    ['class' => 'col-xs-12 col-sm-4 info-label']
                                                ) .
                                                    Html::tag(
                                                        'div',
                                                        $legalHeadquarter->phone,
                                                        ['class' => 'col-xs-12 col-sm-8 info-value']
                                                    ),
                                                ['class' => 'col-xs-12 nop']
                                            );

                                            $slFax = Html::tag(
                                                'div',
                                                Html::tag(
                                                    'div',
                                                    $legalHeadquarter->getAttributeLabel('fax'),
                                                    ['class' => 'col-xs-12 col-sm-4 info-label']
                                                ) .
                                                    Html::tag(
                                                        'div',
                                                        $legalHeadquarter->fax,
                                                        ['class' => 'col-xs-12 col-sm-8 info-value']
                                                    ),
                                                ['class' => 'col-xs-12 nop']
                                            );

                                            $accordionSedeLegale .= Html::tag(
                                                'div',
                                                $slEmail . $slPec . $slTelefono . $slFax . $slIndirizzo,
                                                ['class' => 'row']
                                            );

                                            $accordionSedeLegale .= Html::tag(
                                                'div',
                                                $mapSedeLegale,
                                                ['class' => 'm-t-30']
                                            );
                                    }
                                ?>
                                        <?= $accordionSedeLegale ?>

                            </div>
                            <div class="col-xs-12 <?= $classSectionSedi ?>">
                                
                                
                                <?php
                                        $accordionSedeLegale = '';
                                        $mapSedeLegale = '';

                                        if (!$organizzazioniModule->oldStyleAddressEnabled) {
                                            if (!is_null($mainLegalHeadquarter)) {
                                                $mapSedeLegale = PlaceWidget::widget([
                                                    'model' => $model,
                                                    'attribute' => 'mainLegalHeadquarterAddress',
                                                    'placeAlias' => 'sedeLegaleIndirizzo'
                                                ]);
                                                echo Html::tag(
                                                    'div',$mapSedeLegale,
                                                    ['class' => 'organization-header-map']
                                                    );
                                            }
                                        }
                                    

                                ?>
                            </div>

                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-12 <?= $classSectionSedi ?>">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <p><span class="mdi mdi-map-marker"></span> <strong><?=Module::t('amosorganizzazioni', 'SEDE OPERATIVA')?>: </strong></p>
                            
                            <p><?= $model->getAddressFieldForView() ?></p>
                        </div>
                        
                        <div class="col-xs-12 col-md-6">
                            <?php
                            if (!$organizzazioniModule->oldStyleAddressEnabled) {
                                if (!is_null($mainOperativeHeadquarter)) {
                                    
                                    $mapSedeLegale = PlaceWidget::widget([
                                        'model' => $model,
                                        'attribute' => 'mainOperativeHeadquarterAddress',
                                        'placeAlias' => 'sedeIndirizzo'
                                    ]);
                                    echo Html::tag(
                                        'div',$mapSedeLegale,
                                        ['class' => 'organization-header-map']
                                        );
                                }
                            }
                            ?>
                        </div>

                    </div>
                </div>

            </div>
            <hr class="m-t-30 m-b-30 border-dotted" style="border-style:dotted">
            <div class="row">
                <div class="col-xs-12">
                    <?= AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => Module::t('amosorganizzazioni', '#other_headquarters'),
                                'content' => $this->render('_other_headquarters', ['model' => $model, 'isView' => true]),
                            ]
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                            'active' => 1,
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                        ],
                    ]); ?>
                </div>
            </div>
            








                

            </div>



            

            
        </div>
     
        <?php if(!empty( $model->presentazione_della_organizzaz)): ?>
            <div class="description-container p-t-30 p-b-30 container">
                <p class="bold text-uppercase"><?=Module::t('amosorganizzazioni', 'Presentazione')?></p>
                <?= $model->presentazione_della_organizzaz?>
            </div>
        <?php endif; ?>

        <div class="allegati-container  p-t-30 p-b-30 container">
            <?php if ($organizzazioniModule->enableOrganizationAttachments) : ?>
                <?php
                    $attachmentsWidget = '';
                    $attachmentsWidget = AttachmentsList::widget([
                        'model' => $model,
                        'attribute' => 'allegati',
                        'viewDeleteBtn' => false,
                        'viewDownloadBtn' => true,
                        'viewFilesCounter' => true,
                    ]);
                    ?>
                    <?= $attachmentsWidget ?>
            <?php endif; ?>
        </div>

        <?php if(!empty($model->responsabile || !empty($model->rappresentante_legale_text) ||!empty($model->rappresentanteLegale) || !empty($model->referenteOperativo->nomeCognome))): ?>
            <div class="responsabili-container p-t-30 p-b-30">
                <div class="container">
                    <p class="bold text-uppercase"><?=Module::t('amosorganizzazioni', 'Responsabili')?></p>

                    <div class="row">
                        <?php if(!empty($model->responsabile)): ?>
                            <div class="col-xs-12 col-md-4">
                                <div class="user-container">
                                    <span class="mdi mdi-account-network"></span>
                                    <p>
                                        <span class="bold"><?=$model->responsabile ?></span>
                                        <span class="small"><?= $model->getAttributeLabel('responsabile') ?></span>
                                    </p>
                                </div>
                                
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($model->rappresentante_legale_text) || !empty($model->rappresentanteLegale)): ?>
                        <div class="col-xs-12 col-md-4">
                            <div class="user-container">
                                <span class="mdi mdi-account-tie"></span>
                                <p>
                                    <span class="bold"><?= !empty($model->rappresentante_legale_text) ? $model->rappresentante_legale_text : $model->rappresentanteLegale->nomeCognome ?></span>
                                    <span class="small"><?= $model->getAttributeLabel('rappresentante_legale') ?></span>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if(!empty($model->referenteOperativo->nomeCognome)): ?>

                            <div class="col-xs-12 col-md-4">
                                <div class="user-container">
                                    <span class="mdi mdi-account-group"></span>
                                    <p>
                                        <span class="bold"><?= $model->referenteOperativo->nomeCognome ?></span>
                                        <span class="small"><?= $model->getAttributeLabel('referente_operativo') ?></span>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        <?php endif; ?>


        <div class="dipendenti-container  p-t-30 p-b-30 container">
        	<p class="bold text-uppercase"><?=  Module::t('amosorganizzazioni', '#employees')?></p>
        	
            <?= $this->render('organization-employees', ['model' => $model, 'isView' => true]) ?>
        </div>

        


      


       


        

        <?php if ($organizzazioniModule->enableMembershipOrganizations && !is_null($model->parent)) : ?>
            <div class="membership-container container m-b-30 m-t-30">
                <div class="row">
                    <div class="father-organization-image col-md-4 col-xs-12">
                        <?php
                        $urlParent = '/img/img_default.jpg';
                        if (!is_null($model->parent->logoOrganization)) {
                            $urlParent = $model->parent->logoOrganization->getUrl('original', [
                                'class' => 'img-responsive'
                            ]);
                        }
                        ?>
                        <?= Html::img($urlParent, [
                            'alt' => $model->parent->name
                        ]) ?>
                    </div>
                    <div class="col-xs-12 m-t-15">
                        <span><?= $model->parent->getAttributeLabel('responsabile') ?></span>
                        <span><?= $model->parent->responsabile ?></span>
                    </div>
                    <div>
                        <span><?= Module::t('amosorganizzazioni', '#father_organization'); ?></span>
                        <p><?= $model->parent->name ?></p>
                        <?= $model->parent->sito_web ?>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>

        <?php if ($organizzazioniModule->enableMembershipOrganizations) : ?>
            <?php $childrenOrganizations = $model->children; ?>
            <?php if (!empty($childrenOrganizations)) : ?>
                <?php
                $childrenH2Content = AmosIcons::show('building', ['class' => 'm-r-5'], 'dash');
                $childrenH2Content .= Module::t('amosorganizzazioni', '#children_organizations');
                $childrenH2Content .= ' (' . count($childrenOrganizations) . ')';
                $counter = 1;
                ?>
                <div class="membership-organization-container container m-b-30 m-t-30">

                    <div class="row">
                        <div class="col-xs-12">
                            <?= Html::tag('h2', $childrenH2Content) ?>
                        </div>
                        <?php foreach ($childrenOrganizations as $childrenOrganization) : ?>
                            <div class="col-xs-12">
                                <?php
                                $urlChild = '/img/img_default.jpg';
                                if (!is_null($childrenOrganization->logoOrganization)) {
                                    $urlChild = $childrenOrganization->logoOrganization->getUrl('original', [
                                        'class' => 'img-responsive'
                                    ]);
                                }
                                ?>
                                <?= Html::img($urlChild, [
                                    'class' => 'gridview-image',
                                    'alt' => $childrenOrganization->name
                                ]) ?>
                                <span><?= Module::t('amosorganizzazioni', '#child_organization') . ' ' . $counter; ?></span>
                                <p><?= $childrenOrganization->name; ?></p>
                            </div>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>


    </div>
<?php
        $widgetClassname3 = open20\amos\organizzazioni\widgets\WidgetGraphicsUltimeNews::className();
        $widget3 = Yii::createObject($widgetClassname3);
        $allWidget .= '<div data-code="' . $widget3::classname() . '" data-module-name>' . $widget3::widget(['id' => $model->id]) . '</div>';
       ?>
       <div class="container">
            <p class="bold text-uppercase"><?=  Module::t('amosorganizzazioni', 'Ultime news da')?> <?= $model->name?></p>
            <?= $allWidget; ?>
       </div>

</div>