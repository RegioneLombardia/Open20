<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    DesignPackage
 */

use open20\amos\admin\AmosAdmin;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
$urlTagManager = (isset($urlTagManager)) ? $urlTagManager : false;

$userprofileGroup = [];
$maxTags = 5;
$amosadmin = AmosAdmin::getModuleName();

$modelId = $model->id;

$js = <<<JS
$.ajax({
    url: '/design/design-ajax/my-profile-widget-ajax',
    type: 'get',
    data: {
        id : $modelId
    },
    success: function (data) {
        $('#widget-myprofile-home').html(data);
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle-second="tooltip"]').tooltip();
    }
});
JS;

$this->registerJs($js);
?>
<div id="widget-myprofile-home" class="bi-widget-myprofile-wrapper bg-secondary py-3">
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
                        'nameSurname' => $model->nomeCognome,
                        'showAvatar' => true,
                        'avatarWrapperSize' => 'xl',
                        'showWelcomeLabel' => true,
                        'isFacilitator' => $isFacilitator,
                        'isCommunityManager' => $isFacilitator,
                    ]
                );
            ?>

            <div class="ml-auto">
                <div class="progress-spinner progress-spinner-active size-sm">
                    <span class="sr-only"><?= Module::t('amosdesign', 'Caricamento dati utente in corso') . '...' ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
