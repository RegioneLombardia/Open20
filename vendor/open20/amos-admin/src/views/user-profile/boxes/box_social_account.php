<?php

/**
 * Aria S.p.A.
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
$this->registerCss(".hidden{ display:none !important;}");

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

$js = <<<JS

function isLinkedSocial(userProfileId, provider){
    var linkBtn = $('#link-'+provider);
    var divlinkBtn = $('#div-link-'+provider);
    var unlinkBtn = $('#unlink-'+provider);
    var divunlinkBtn = $('#div-unlink-'+provider);

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
$moduleName = AmosAdmin::getModuleName();
if ($socialAuthModule->enableSpid) {
?>
    <section class="social-admin-section">
        <h3><?= AmosAdmin::t('amosadmin', 'Gestione accessi') ?></h3>

        <div class="row m-t-30">
            <div class="col-md-8">
                <p><?= AmosAdmin::t('amosadmin', '#fullsize_spid') ?></p>
            </div>
            <div class="col-md-3 col-md-offset-1">
                <?php
                $connected = false;
                $label = AmosAdmin::t('amosadmin', $socialAuthModule->shibbolethConfig['buttonLabel']);
                $url = \Yii::$app->params['platform']['BackednUrl'] . "/$moduleName/user-profile/connect-spid?id=" . $model->id;
                $idm = \open20\amos\socialauth\models\SocialIdmUser::find()->andWhere(['user_id' => $model->user_id])->one();

                if ($idm) {
                    $label = AmosAdmin::t('amosadmin', 'Disconnetti la tua identità digitale');
                    $connected = true;
                    $url = Yii::$app->urlManager->createAbsoluteUrl(['/socialauth/shibboleth/remove-spid', 'urlRedirect' => "/$moduleName/user-profile/update?id=" . $model->id . '#tab-settings']);
                }
                ?>

                <?php if ($enableDlSemplificazione && $idm) { ?>
                    <p class="text-center text-success"><strong><?= AmosAdmin::t('amosadmin', 'IDPC già associato') ?></strong></p>
                <?php } else { ?>
                    <div class="wrap-btn-social spid-container nop">
                        <?= Html::a(
                            $label,
                            $url,
                            [
                                'id' => 'link-spid',
                                'class' => 'btn btn-spid',
                                'title' => $label,
                            ]
                        ) ?>
                    </div>
                <?php } ?>
            </div>
        </div>

    </section>
<?php } ?>

<?php if (!is_null($socialAuthModule) && $socialAuthModule->enableLink) {

    $this->registerJs($js);
    $socialAuthUsers = [];
?>
    <section>

        <!-- <p><?= AmosAdmin::t('amosadmin', 'You can link your social accounts and then access the Open Innovation Platform with any of these accounts') . '.' ?></p>
        <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#choose_social'); ?></strong></p> -->
        <div class="row m-t-30">
            <div class="col-md-8">
                <p><?= AmosAdmin::t('amosadmin', 'Puoi collegare il tuo account social e successivamente accedere alla Piattaforma.') ?></p>
            </div>
            <div class="col-md-3 col-md-offset-1">
                <?php foreach ($socialAuthModule->providers as $name => $config) {
                    $providerName = strtolower($name);
                    $this->registerJs(
                        <<<JS
                  isLinkedSocial($model->id, '$providerName');  
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

                        <?php $visibleClass = $connected ? '' : '' ?>
                        <div id="div-unlink-<?= $providerName ?>" class="m-t-5" style="<?= $visibleClass ?>">
                            <?= Html::a(
                                AmosIcons::show($providerName) . Html::tag('span', AmosAdmin::t('amosadmin', 'Disconnect' . ' ' . $providerName)),
                                Yii::$app->urlManager->createAbsoluteUrl('/socialauth/social-auth/unlink-social-account?provider=' . $providerName),
                                [
                                    'id' => 'unlink-' . $providerName,
                                    'class' => 'btn btn-block btn-' . $providerName . ($connected ? ' btn-' . $providerName . '-disconnect' : ' hidden'),
                                    'title' => AmosAdmin::t('amosadmin', 'Disconnect from your account')
                                ]
                            ) ?>
                        </div>

                        <?php $visibleClass = $connected ? '' : '' ?>
                        <div id="div-link-<?= $providerName ?>" style="<?= $visibleClass ?>">
                            <?= Html::a(
                                AmosIcons::show($providerName) . Html::tag('span', AmosAdmin::t('amosadmin', 'Connetti' . ' ' . $providerName)),
                                Yii::$app->urlManager->createAbsoluteUrl('/socialauth/social-auth/link-social-account?provider=' . strtolower($name)),
                                [
                                    'id' => 'link-' . $providerName,
                                    'class' => 'btn btn-block btn-' . $providerName . ' btn-' . ($connected ? ' hidden' : ''),
                                    'title' => AmosAdmin::t('amosadmin', 'Connect with your account'),
                                    'onclick' => "window.open(this.href, '$providerName', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                                ]
                            ) ?>
                        </div>
                    <?php endif; ?>
                <?php } ?>


            </div>
        </div>
        <?php if ($socialAuthModule->providers && !empty($socialAuthModule->enableServices)) {
            $this->registerJs($jsServices);
        ?>

            <?php if (array_key_exists('Google', $socialAuthModule->providers)) : ?>

                <?= $this->render('box_google_services', ['form' => $form, 'model' => $model, 'socialAuthUsers' => $socialAuthUsers, 'enableServices' => $socialAuthModule->enableServices]); ?>
            <?php endif; ?>

        <?php } ?>
    </section>



<?php } ?>