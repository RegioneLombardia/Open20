<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\socialauth\models\SocialAuthUsers;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;
$adminModuleName = AmosAdmin::getModuleName();


/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

$js = <<<JS

function isLinkedSocial(userProfileId, provider){
    var linkBtn = $('#link-'+provider);
    var unlinkBtn = $('#unlink-'+provider);
    var boxServices = $('#'+provider+'-services');

    jQuery.getJSON( "/$adminModuleName/user-profile/get-social-user", {id: "$model->id", provider: provider}, function( data ) {

        if(data <= 0 ){
            if(boxServices.length && !boxServices.hasClass('hidden')){
                boxServices.addClass('hidden');
            }
            if(!unlinkBtn.hasClass('hidden')){
                unlinkBtn.addClass('hidden');
            }
            if(linkBtn.hasClass('hidden')){
                linkBtn.removeClass('hidden');
            }
            $(window).focus(function(){
                $('#loader').show();
                isLinkedSocial(userProfileId, provider);
                $('#loader').hide();
            });
        } else{
            if(boxServices.length && boxServices.hasClass('hidden')){
                boxServices.removeClass('hidden');
            }
            if(unlinkBtn.hasClass('hidden')){
                unlinkBtn.removeClass('hidden');
            }
            if(!linkBtn.hasClass('hidden')){
                linkBtn.addClass('hidden');
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

JS;
$jsServices = <<<JS
function isEnabledService(userProfileId, provider, serviceName){
    var serviceBtn = $('#enable-'+serviceName+'-btn');
    var disableServiceBtn = $('#disable-'+serviceName+'-btn');
  
    jQuery.getJSON( "/$adminModuleName/user-profile/get-social-service-status", {id: "$model->id", provider: provider, serviceName: serviceName}, function( data ) {

        if(data.enabled < 0 ){
            serviceBtn.attr('href', null);
            disableServiceBtn.attr('href', null);
        } else{
            if(data.enabled > 0){
                 if(disableServiceBtn.hasClass('hidden')){
                    disableServiceBtn.removeClass('hidden');
                }
                 if(!serviceBtn.hasClass('hidden')){
                    serviceBtn.addClass('hidden');
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
                 if(!disableServiceBtn.hasClass('hidden')){
                    disableServiceBtn.addClass('hidden');
                }
                 if(serviceBtn.hasClass('hidden')){
                    serviceBtn.removeClass('hidden');
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
JS;

?>
<?php
if ($socialAuthModule->enableSpid) {
    ?>
    <section class="social-admin-section col-xs-12 nop">
        <h2 class="spid-title">
            <?=AmosAdmin::t('amosadmin', '#fullsize_spid')?>
        </h2>
        <?php
        $connected = false;
        $label = AmosAdmin::t('amosadmin', $socialAuthModule->shibbolethConfig['buttonLabel']);
        $url = Yii::$app->urlManager->createAbsoluteUrl(['/admin/user-profile/connect-spid', 'id' => $model->id]);
        $idm = \open20\amos\socialauth\models\SocialIdmUser::find()->andWhere(['user_id' => $model->user_id])->one();
        
        if ($idm) {
            $label = AmosAdmin::t('amosadmin', 'Disconnetti la tua identità digitale');
            $connected = true;
            $url = Yii::$app->urlManager->createAbsoluteUrl(['/socialauth/shibboleth/remove-spid', 'urlRedirect' => '/admin/user-profile/update?id=' . $model->id . '#tab-settings']);
            
        }
        ?>
        
        <?php if ($adminModule->enableDlSemplification && $idm) { ?>
            <p><?= AmosAdmin::t('amosadmin', 'Hai già associato la tua identità digitale') ?></p>
        <?php } else { ?>
            <div class="wrap-btn-social spid-container nop">
                <?= Html::a(
                    $label,
                    $url,
                    [
                        'id' => 'link-spid',
                        'class' => 'btn btn-spid',
                        'title' => $label,
                    ]) ?>
            </div>
        <?php } ?>
    </section>
<?php } ?>

<?php if (!is_null($socialAuthModule) && $socialAuthModule->enableLink) {
    
    $this->registerJs($js);
    $socialAuthUsers = [];
    ?>
    <section class="social-admin-section col-xs-12 nop">
        <h2>
            <!--            < ?= AmosIcons::show('settings') ?>-->
            <?= AmosAdmin::tHtml('amosadmin', 'Access with social account') ?>
        </h2>
        <p><?= AmosAdmin::t('amosadmin', 'You can link your social accounts and then access the Open Innovation Platform with any of these accounts') . '.' ?></p>
        <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#choose_social'); ?></strong></p>
        <div class="wrap-btn-social">
            <?php foreach ($socialAuthModule->providers as $name => $config) {
                $providerName = strtolower($name);
                $this->registerJs(<<<JS
                  isLinkedSocial($model->id, '$providerName');  
JS
                );
                ?>
                <?php if ($adminModule->confManager->isVisibleField($providerName, ConfigurationManager::VIEW_TYPE_FORM)): ?>
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
                    <?= Html::a(
                        AmosIcons::show($providerName) . AmosAdmin::t('amosadmin', 'Disconnect'),
                        Yii::$app->urlManager->createAbsoluteUrl('/socialauth/social-auth/unlink-social-account?provider=' . $providerName),
                        [
                            'id' => 'unlink-' . $providerName,
                            'class' => 'btn btn-' . $providerName . ($connected ? ' btn-' . $providerName . '-disconnect' : ' hidden'),
                            'title' => AmosAdmin::t('amosadmin', 'Disconnect from your account')
                        ]) ?>
                    <?= Html::a(
                        AmosIcons::show($providerName),
                        Yii::$app->urlManager->createAbsoluteUrl('/socialauth/social-auth/link-social-account?provider=' . strtolower($name)),
                        [
                            'id' => 'link-' . $providerName,
                            'class' => 'btn  btn-' . $providerName . ' btn-' . $providerName . '-square' . ($connected ? ' hidden' : ''),
                            'title' => AmosAdmin::t('amosadmin', 'Connect with your account'),
                            'onclick' => "window.open(this.href, '$providerName', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                        ]) ?>
                <?php endif; ?>
            <?php } ?>

        </div>

    </section>
    
    
    <?php if ($socialAuthModule->providers && !empty($socialAuthModule->enableServices)) {
        $this->registerJs($jsServices);
        ?>
        <section class="social-admin-section col-xs-12 nop">
            <h2>
                <?= AmosAdmin::tHtml('amosadmin', 'Services') ?>
            </h2>
            <?php if (array_key_exists('Google', $socialAuthModule->providers)): ?>
                <?= $this->render('box_google_services', ['form' => $form, 'model' => $model, 'socialAuthUsers' => $socialAuthUsers, 'enableServices' => $socialAuthModule->enableServices]); ?>
            <?php endif; ?>
        </section>
    <?php } ?>
<?php } ?>
