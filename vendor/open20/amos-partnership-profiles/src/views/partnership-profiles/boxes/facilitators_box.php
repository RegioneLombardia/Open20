<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\partnershipprofiles\Module;
use kartik\alert\Alert;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 */

?>
<div class="row m-b-30">
    <?php if ($model->isNewRecord): ?>
        <div class="col-xs-12">
            <?= Alert::widget([
                'type' => Alert::TYPE_WARNING,
                'body' => Module::t('amospartnershipprofiles', 'Before choose the facilitator click on the CREATE button in the bottom to save the partnership profile.'),
                'closeButton' => false
            ]); ?>
        </div>
    <?php else: ?>
        <?php
        $facilitatorUserProfile = $model->partnershipProfileFacilitator;
        ?>
        <div class="col-xs-12 facilitator-content">
            <div class="col-xs-12 facilitator-textarea">
                <h4><strong><?= Module::t('amospartnershipprofiles', '#facilitator_box_facilitator') ?></strong></h4>
                <p><?= Module::t('amospartnershipprofiles', '#facilitator_box_facilitator_description_1') ?></p>
                <p><?= Module::t('amospartnershipprofiles', '#facilitator_box_facilitator_description_2') ?></p>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <div class="col-md-6 facilitator-id m-t-15 nop">
                    <?php if (!is_null($facilitatorUserProfile)): ?>
                        <div class="col-xs-4 m-t-5 m-b-15">
                            <?php
                            Yii::$app->imageUtility->methodGetImageUrl = "getAvatarUrl";
                            echo Html::tag('div', Html::img($facilitatorUserProfile->getAvatarUrl(), [
                                'class' => Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['class'],
                                'style' => "margin-left: " . Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['margin-left'] . "%; margin-top: " . Yii::$app->imageUtility->getRoundImage($facilitatorUserProfile)['margin-top'] . "%;",
                                'alt' => $facilitatorUserProfile->getNomeCognome()
                            ]),
                                ['class' => 'container-round-img-md']);
                            ?>
                        </div>
                        <div class="col-xs-8">
                            <p><strong><?= $facilitatorUserProfile->getNomeCognome() ?></strong></p>
                            <div><?= Html::a(Module::t('amospartnershipprofiles', 'Change facilitator'), ['/partnershipprofiles/partnership-profiles/associate-facilitator', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true]) ?></div>
                        </div>
                    <?php else: ?>
                        <div><?= Module::tHtml('amospartnershipprofiles', 'Facilitator not selected') ?></div>
                        <div><?= Html::a(Module::t('amospartnershipprofiles', 'Select facilitator'), ['/partnershipprofiles/partnership-profiles/associate-facilitator', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true]) ?></div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>
                <?php if (!is_null($facilitatorUserProfile)): ?>
                    <div class="col-xs-12 col-md-6 m-t-15">
                        <div class="col-xs-1 nop text-right">
                            <?= AmosIcons::show('info') ?>
                        </div>
                        <div class="col-xs-11">
                            <?= Module::t('amospartnershipprofiles', '#facilitator_box_auto_facilitator') ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
