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
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use kartik\alert\Alert;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var bool $external
 */

$adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
$enableExternalFacilitator = $adminModule->enableExternalFacilitator;

/** @var \open20\amos\admin\models\UserProfile $facilitatorUserProfile */
$facilitatorUserProfile = $model->facilitatore; // Non modificare! Dev'essere usata la relazione!!!
$titleBox = AmosAdmin::t('amosadmin', 'External facilitator in platform');
$url = '/'.AmosAdmin::getModuleName().'/user-profile/send-request-external-facilitator';
$isRequestPending = false;
$facilitatorRequest = null;


$facilitatorUserProfile = $model->externalFacilitator;
$facilitatorRequest = $model->userProfileExternalFacilitator;
if ($facilitatorRequest && $facilitatorRequest->status == \open20\amos\admin\models\UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_REQUEST) {
    $isRequestPending = true;
}


?>

<section>
    <div class="col-xs-12 facilitator-content">
        <div class="col-xs-12 facilitator-textarea">
            <h4>
                <strong><?= $titleBox ?></strong>
                <?= Html::beginTag('span', [
                    'title' => AmosAdmin::t('amosadmin', '#tooltip_facilitator'),
                    'data-toggle' => 'tooltip',
                    'data-html' => 'true',
                    'class' => 'amos-tooltip',
                ]) . AmosIcons::show('info'); ?>
            </h4>
            <p><?= AmosAdmin::t('amosadmin', 'The facilitator is a user with an in-depth knowledge of the platform\'s objectives and methodology and is responsible for providing assistance to users.') ?></p>
            <p><?= AmosAdmin::t('amosadmin', 'You can contact the facilitator at any time for informations on compiling your profile data and using the platform.') ?></p>
        </div>

        <div class="col-xs-12 ">
            <div class="col-xs-12 facilitator-id m-t-15">
                <?php if ($model->isNewRecord) { ?>
                    <div class="m-t-20">
                        <?= Alert::widget([
                            'type' => Alert::TYPE_WARNING,
                            'body' => AmosAdmin::t('amosadmin', '#facilitator_box_new_record_message'),
                            'closeButton' => false
                        ]); ?>
                    </div>
                <?php } else { ?>
                    <?php  if ($isRequestPending) { ?>
                        <p><?= AmosAdmin::tHtml('amosadmin', '<strong>Richiesta inviata:</strong> in attesa di risposta da <strong>{nomeCognome}</strong>', [
                                'nomeCognome' => $facilitatorRequest->externalFacilitator->nomeCognome
                            ]) ?>
                        </p>
                    <?php } else if (!is_null($facilitatorUserProfile)) { ?>
                        <!--                        <div class="col-sm-1 col-xs-4 m-t-10 m-b-10">-->
                        <?php
                        Yii::$app->imageUtility->methodGetImageUrl = "getAvatarUrl";
                        echo Html::tag('div', Html::img($facilitatorUserProfile->getAvatarUrl(), [
                            'class' => Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['class'],
                            'style' => "margin-left: " . Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['margin-left'] . "%; margin-top: " . Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['margin-top'] . "%;",
                            'alt' => $facilitatorUserProfile->getNomeCognome()
                        ]),
                            ['class' => 'container-round-img-sm']);
                        $prevalentPartnershipName = '-';
                        $prevalentPartnershipObj = $facilitatorUserProfile->prevalentPartnership;
                        if (!is_null($prevalentPartnershipObj)) {
                            if (strlen($prevalentPartnershipObj->getTitle()) > 60) {
                                $stringCut = substr(strip_tags($prevalentPartnershipObj->getTitle()), 0, 70);
                                $stringCut = $stringCut . '... ';
                            } else {
                                $stringCut = $prevalentPartnershipObj->getTitle();
                            }
                            $prevalentPartnershipName = Html::tag('span', $stringCut);
                        }
                        ?>

                        <!--                        </div>-->
                        <!--                        <div class="col-sm-11 col-xs-8">-->
                        <p><?= Html::tag('span', AmosAdmin::t('amosadmin', 'Nome')) . Html::tag('span', $facilitatorUserProfile->nome); ?></p>
                        <p><?= Html::tag('span', AmosAdmin::t('amosadmin', 'Cognome')) . Html::tag('span', $facilitatorUserProfile->cognome); ?></p>
                        <p><?= Html::tag('span', AmosAdmin::t('amosadmin', 'Prevalent partnership')) . $prevalentPartnershipName ?></p>
                        <p><?= Html::a(AmosAdmin::t('amosadmin', 'Change facilitator'), [$url, 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true, 'external' => $external]) ?></p>
                        <!--                        </div>-->
                    <?php } else {  ?>
                            <p><?= AmosAdmin::tHtml('amosadmin', 'Facilitator not selected') ?></p>
                            <p><?= Html::a(AmosAdmin::t('amosadmin', 'Select facilitator'), [$url, 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true, 'external' => $external]) ?></p>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
