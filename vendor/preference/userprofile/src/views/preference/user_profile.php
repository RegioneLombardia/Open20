<?php

use open20\amos\admin\models\UserProfile;
use open20\design\components\bootstrapitalia\ActiveForm;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\socialauth\models\SocialAuthUsers;

$customAsset = BootstrapItaliaDesignAsset::register($this);

 
/**
 * @var UserProfile $userProfile
 * 
 */
?>

<div class="row variable-gutters">
    <!-- START SIDEBAR -->
    <div class="col-lg-3 affix-parent d-none d-lg-block">
        <div class="sidebar-wrapper it-line-right-side sidebar-preference h-100 pt-5">
        </div>
    </div>
    <!-- START MAIN -->
    <div class="col-lg-9 mt-4 pb-5">


        <div class="mb-5">
            <h1 class="h3">Il mio profilo</h1>
            <p class="text-muted">In questa pagina puoi gestire tutte le informazioni relative al tuo profilo</p>
            <?php
            $form = ActiveForm::begin([
                'enableClientScript' => true,
                'options' => [
                    'id' => 'personal-data-form',
                    'class' => 'needs-validation form-rounded',
                    'enctype' => 'multipart/form-data',
                ],
            ]);
            ?>
            <div class="py-5 mb-5 container external-personal-data-container  border-bottom border-light d-flex flex-column order-bottom border-light ">
                <div class="informazioni-personali-container  mt-3 mb-5  d-flex flex-column">
                    <h2 class="mb-4 text-uppercase h5"> Informazioni personali </h2>

                    <div class="row variable-gutters mb-5">
                        <?php
                        foreach (\preference\userprofile\utility\UserProfileUtility::getIDMFields(Yii::$app->user->id) as $key => $value):
                            ?>
                            <div class="col-md-4">
                                <b><?= \preference\userprofile\utility\UserProfileUtility::getIDMLabels($key)?>: </b>
                                <br /><?= $value ?>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>

                    <div class="row variable-gutters">

                        <div class="col-md-4">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'surname')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <div class="label-style tertiary-color">Sesso</div>
                            <?= $form->field($model, 'gender')->radioList($model->getGenderChoices()) ?>
                        </div>
                    </div>
                    <div class="row variable-gutters">
                        <div class="col-md-4">
                            <?=
                                $form->field($model, 'birth_date')->inputCalendar(['placeholder' => 'dd/mm/YYYY'], 'd/m/Y')
                            ?>
                        </div>
                        <div class="col-md-4">

                            <?=
                                $form->field($model, 'residence_province')->select($items, ['placeholder' => 'Scegli una provincia', 'id' => 'residence_province-id'])
                            ?>

                        </div>
                        <div class="col-md-4">
                            <?php
                            // VarDumper::dump( $model->residence_city, $depth = 3, $highlight = true);
                            ?>
                            <?=
                                $form->field($model, 'residence_city')->select(null, [
                                    'placeholder' => 'Scegli un comune',
                                    'data-action' => Url::to('/preferenceuser/registration/test-data-ajax'),
                                    'related-id' => 'residence_province-id',
                                ])
                            ?>

                        </div>
                        <?php
                            /*
                                <div class="col-md-4">
                                    <?= $form->field($model, 'fiscal_code')->textInput([
                                        'aria-describedby' => 'Perchè devo fornire il mio Codice Fiscale?',
                                        'infoTooltip' => 'Questo dato consentirà il tuo riconoscimento come utente del sistema anche in caso di accesso tramite SPID, in questo modo non rischierai di ricevere più volte lo stesso messaggio',
                                    ]); ?>
                                </div>
                            */
                        ?>
                    </div>

                    <div class="row variable-gutters">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4 text-md-right">
                            <?php
                            echo Html::submitButton(
                                'Salva modifiche',
                                ['class' => 'btn btn-primary px-5', 'name' => 'submit-action']
                            );
                            ?>
                        </div>
                    </div>
                </div>


            </div>

            <?php ActiveForm::end(); ?>

        </div>
        <div class="mb-5">
<!--            <p class="h5 text-uppercase">CREDENZIALI</p>-->
            <div class="px-4 py-3 lightgrey-bg-c1 my-3">
                <div class="row">
                    <div class="col-lg-3 mb-3 mb-lg-0">
                        <span class="h5">Email</span>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center">
                        <small><?= $userProfile->user->email ?></small>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-end align-items-center">
                        <small class="font-weight-bold"><?= Html::a('Aggiorna >', Url::to(['user-profile-update-email']), ['title' => 'Aggiorna email']); ?></small>
                    </div>
                </div>

            </div>
            <?php
            /*
            ?>
            <div class="px-4 py-3 lightgrey-bg-c1 my-3">
                <div class="row">
                    <div class="col-lg-3 mb-3 mb-lg-0">
                        <span class="h5">Password</span>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center">
                        <?php
                        $labelActionPassword = 'Aggiorna >';
                        if (!empty($userProfile->user->password_hash)) :
                            $labelActionPassword = 'Aggiorna >';
                        ?>
                            <small>*********</small>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-end align-items-center">
                        <small class="font-weight-bold"><?= Html::a($labelActionPassword, Url::to(['user-profile-password']), ['title' => 'Aggiorna password']); ?></small>
                    </div>
                </div>

            </div>
            <?php
            */
            ?>

        </div>



        <?php

        /** @var AmosAdmin $adminModule */
        $adminModule = Yii::$app->getModule('amosadmin');

        /**
         * @var $socialAuthModule \open20\amos\socialauth\Module
         */
        $socialAuthModule = Yii::$app->getModule('socialauth');
        $moduleName = AmosAdmin::getModuleName();
        $js = <<<JS

function isLinkedSocial(userProfileId, provider){
    var linkBtn = $('#link-'+provider);
    var unlinkBtn = $('#unlink-'+provider);
    var boxServices = $('#'+provider+'-services');

    jQuery.getJSON( "/$moduleName/user-profile/get-social-user", {id: "$userProfile->id", provider: provider}, function( data ) {
        console.log(data);
        if(data <= 0 ){
            if(boxServices.length && !boxServices.hasClass('d-none')){
                boxServices.addClass('d-none');
            }
            if(!unlinkBtn.hasClass('d-none')){
                unlinkBtn.addClass('d-none');
            }
            if(linkBtn.hasClass('d-none')){
                linkBtn.removeClass('d-none');
            }
            $(window).focus(function(){
                $('#loader').show();
                isLinkedSocial(userProfileId, provider);
                $('#loader').hide();
            });
        } else {
            if(boxServices.length && boxServices.hasClass('d-none')){
                boxServices.removeClass('d-none');
            }
            if(unlinkBtn.hasClass('d-none')){
                unlinkBtn.removeClass('d-none');
            }
            if(!linkBtn.hasClass('d-none')){
                linkBtn.addClass('d-none');
            }
            unlinkBtn.on('click', function(e) {
                e.preventDefault();
                $('#loader').show();
                $.post(unlinkBtn.attr('href'), {id: userProfileId} ).done(function( data ) {
                    if(data){
                       isLinkedSocial(userProfileId, provider);
                    }
                    $('#loader').hide();
                });
            });
                
        }
    });
}

JS
;
        $jsServices = <<<JS
function isEnabledService(userProfileId, provider, serviceName){
    var serviceBtn = $('#enable-'+serviceName+'-btn');
    var disableServiceBtn = $('#disable-'+serviceName+'-btn');
  
    jQuery.getJSON( "/$moduleName/user-profile/get-social-service-status", {id: "$userProfile->id", provider: provider, serviceName: serviceName}, function( data ) {
        console.log(data);
        if(data.enabled < 0 ){
            serviceBtn.attr('href', null);
            disableServiceBtn.attr('href', null);
        } else{
            if(data.enabled > 0){
                 if(disableServiceBtn.hasClass('d-none')){
                    disableServiceBtn.removeClass('d-none');
                }
                 if(!serviceBtn.hasClass('d-none')){
                    serviceBtn.addClass('d-none');
                }
                disableServiceBtn.on('click', function(e) {
                    e.preventDefault();
                    $('#loader').show();
                    $.post(disableServiceBtn.attr('href'), {id: userProfileId, serviceName: serviceName} ).done(function( data2 ) {
                        if(data2){
                           isEnabledService(userProfileId, provider, serviceName);
                        }
                        $('#loader').hide();
                    });
                });
            }else{
                 if(!disableServiceBtn.hasClass('d-none')){
                    disableServiceBtn.addClass('d-none');
                }
                 if(serviceBtn.hasClass('d-none')){
                    serviceBtn.removeClass('d-none');
                }
                $(window).focus(function(){
                    $('#loader').show();
                    isEnabledService(userProfileId, provider, serviceName);
                    $('#loader').hide();
                });
            }
        }
    });
}
JS
;

        ?>
        <?php if (!is_null($socialAuthModule) && $socialAuthModule->enableLink) {

            $this->registerJs($js);
            $socialAuthUsers = [];
        ?>
            <section class="social-admin-section mb-5">
                <h5 class="text-uppercase">
                    <!--            < ?= AmosIcons::show('settings') ?>-->
                    <?= AmosAdmin::tHtml('amosadmin', 'Access with social account') ?>
                </h5>
                <p><?= 'Puoi collegare i tuoi account social e successivamente accedere alla Piattaforma di Lombardia Informa con uno qualsiasi di questi account.' ?></p>
                <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#choose_social'); ?></strong></p>
                <div class="wrap-btn-social">
                    <?php foreach ($socialAuthModule->providers as $name => $config) {


                        $providerName = strtolower($name);
                        $this->registerJs(<<<JS
                            isLinkedSocial($userProfile->id, "$providerName");  
JS
                        );

                    ?>
                        <?php if ($adminModule->confManager->isVisibleField($providerName, ConfigurationManager::VIEW_TYPE_FORM)) : ?>
                            <?php

                            $alreadyLinkedSocial = SocialAuthUsers::findOne([
                                'user_id' => $user->id,
                                'provider' => $providerName
                            ]);
                            $connected = $alreadyLinkedSocial && $alreadyLinkedSocial->id;
                            if ($connected) {
                                $socialAuthUsers[$providerName] = $alreadyLinkedSocial;
                            }
                            ?>
                            <?php 
                            $iconClass = ($providerName == 'twitter') ? 'icon-black' : 'icon-white';
                            ?>
                            <?= Html::a('<svg class="icon icon-xs ' . $iconClass . ' mr-1"><use xlink:href="'. $customAsset->baseUrl . '/sprite/material-sprite.svg#'. $providerName .'"></use></svg>' . AmosAdmin::t('amosadmin', 'Disconnect'),
                                Yii::$app->urlManager->createAbsoluteUrl('/socialauth/social-auth/unlink-social-account?provider=' . $providerName),
                                [
                                    'id' => 'unlink-' . $providerName,
                                    'class' => 'btn btn-icon btn-xs btn-' . $providerName . ($connected ? ' btn-' . $providerName . '-disconnect' : ' hidden'),
                                    'title' => AmosAdmin::t('amosadmin', 'Disconnect from your account')
                                ]
                            ) ?>  
                            <?= Html::a('<svg class="icon icon-xs ' . $iconClass . ' mr-1"><use xlink:href="'. $customAsset->baseUrl . '/sprite/material-sprite.svg#'. $providerName .'"></use></svg>' . AmosAdmin::t('amosadmin', 'Connect'),
                                Yii::$app->urlManager->createAbsoluteUrl('/socialauth/social-auth/link-social-account?provider=' . strtolower($name)),
                                [
                                    'id' => 'link-' . $providerName,
                                    'class' => 'btn btn-icon btn-xs btn-' . $providerName . ($connected ? ' hidden' : ''),
                                    'title' => AmosAdmin::t('amosadmin', 'Connect with your account'),
                                    'onclick' => "window.open(this.href, '$providerName', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                                ]
                            ) ?>  

                            
                           
                        <?php endif; ?>
                        
                    <?php } ?>
                </div>

            </section>


        <?php } ?>



        <div class="mb-5">
            <p class="h5 text-uppercase">GESTIONE ISCRIZIONI</p>
            <p class="text-muted">In questa sezione puoi disabilitare o attivare in qualunque momento la ricezione delle comunicazioni e gestire le modalità di contatto per: Cittadino, Impresa o Ente e Operatore</p>
        </div>
        <div class="row text-center border-bottom border-tertiary pb-2 mt-5 mb-3 d-none d-md-flex">
            <div class="col-md-3">
                <div class="h5 m-0">Target</div>
            </div>
            <div class="col-md-6 border border-tertiary border-top-0 border-bottom-0">
                <div class="h5 m-0">Modalità di contatto</div>
            </div>
            <div class="col-md-3">
                <div class="h5 m-0">Stato</div>
            </div>
        </div>
        <?php
        foreach ($listOfTarget as $key => $target) :
            $targetAttributes = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, $target->codice);
            $isSelected = TargetTagUtility::isTargetSelectedForUser($target, Yii::$app->user->id);
        ?>
            <div class="row shadow-sm mb-4">
                <div class="col-md-3 col-6 lightgrey-bg-c1 text-center d-flex align-items-center <?= ($isSelected) ?: 'opacity-5' ?>">
                    <div class="py-5 px-3 mx-auto">
                        <img src="/img/icon-user.svg" class="" alt="icona cittadino">
                        <div class="font-weight-bold tertiary-color"><?= $target->nome ?></div>
                    </div>

                </div>

                <div class="col-md-3 col-6 d-flex align-items-center order-md-3 border-left border-light">
                    <div class="mx-auto">
                        <div class="toggles text-center">
                            <label for="toggle-stato-id-<?= $targetAttributes->id ?>">
                                <input type="checkbox" id="toggle-stato-id-<?= $targetAttributes->id ?>" <?= $isSelected ? 'checked' : ''; ?>>
                                <span class="lever"></span>
                                <span class="d-block jsSwitchLabel"></span>
                            </label>
                        </div>
                    </div>
                    <?php
                    $targetCode = $target->codice;
                    $jsId = $targetAttributes->id;
                    $this->registerJs(
                        <<<JS

    $("#toggle-stato-id-{$jsId}").click(function(event) {

        if($(this).prop("checked") == true) {
            $(this).parent().find('.jsSwitchLabel').text('Attivo').delay(1000);
            $.post({
                url: '/preferenceuser/preference/enable-target-ajax',
                type: 'post',
                data: {
                        target_code: "{$targetCode}",
                    },
                success: function (data) {
                    if(data == 'true'){                     
                        location.reload();
                    } else {                                                
                        
                        //$(this).prop('checked', false);

                        $('#messages-attributes-id').html(' \
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                        Errore, impossibile abilitare il target: ' + data + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                            <span aria-hidden="true">&times;</span> \
                        </button> \
                        </div> \
                        ');
                        
                    }
                }
            });
        }

        if($(this).prop("checked") == false) {
            $(this).parent().find('.jsSwitchLabel').text('Disattivo');
            $.post({
                url: '/preferenceuser/preference/disable-target-ajax',
                type: 'post',
                data: {
                        target_code: "{$targetCode}",
                    },
                success: function (data) {
                    if(data == 'true'){                     
                        location.reload();
                    } else {                         

                        //$(this).prop('checked', true);

                        $('#messages-attributes-id').html(' \
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                        Errore, impossibile disabilitare il target: ' + data + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                            <span aria-hidden="true">&times;</span> \
                        </button> \
                        </div> \
                        ');
                    }
                }
            });
        }
    });

JS
,
                        View::POS_READY
                    );

                    $this->registerJs(
                        <<<JS

    $(document).ready(function(event) {

        var allToggle = $(".toggles");
        allToggle.each(function(){
            var input = $(this).find('input');
            if((input).prop("checked") == true){
                $(this).find('.jsSwitchLabel').text('Attivo');
            } else {
                $(this).find('.jsSwitchLabel').text('Disattivo');
            }
        })

    });

JS
,
                        View::POS_READY
                    );
                    ?>
                </div>
                <div class="col-md-6 py-4 order-md-2">
                    <div class="px-3">
                        <?php
                        if ($isSelected) :
                        ?>
                            <div class="py-4">
                                <?php
                                echo Yii::$app->controller->renderPartial('_modal_target_mail', [
                                    'targetAttributes' => $targetAttributes,
                                    'currentTargetTag' => $target,
                                    'htmlId' => $targetAttributes->id
                                ]);
                                ?>
                            </div>
                            <div class="pb-4">
                                <?php
                                echo Yii::$app->controller->renderPartial('_modal_target_phone', [
                                    'targetAttributes' => $targetAttributes,
                                    'currentTargetTag' => $target,
                                    'htmlId' => $targetAttributes->id
                                ]);
                                ?>
                            </div>
                        <?php else : ?>
                            <!-- FAKE FORM -->
                            <div class="form-rounded opacity-5 px-3">
                                <div class="py-4">
                                    <fieldset disabled aria-label="Form disabilitato">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="inserisci l'indirizzo email">
                                            <label for="disabledTextInput">Email</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="pb-4">
                                    <fieldset disabled aria-label="Form disabilitato">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="inserisci il numero di cellulare">
                                            <label for="disabledTextInput">Cellulare</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>


        <div id="messages-attributes-id"></div>
    </div>
</div>