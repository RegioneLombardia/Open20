<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\views\my-activities
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileValidationNotify;
use open20\amos\core\helpers\Html;
use open20\amos\myactivities\AmosMyActivities;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\admin\AmosAdmin;

/** @var $model open20\amos\myactivities\basic\ProfileValidationNotifyToRead */

/** @var UserProfile $userProfile */
$userProfile = $model->user->userProfile;

?>
	<div class="wrap-activity">
		<div class="col-md-1 col-xs-2 icon-plugin">
			<?= AmosIcons::show('users', [], 'dash') ?>
		</div>
		<div class="col-md-3 col-xs-5 wrap-user">
			<?= UserCardWidget::widget(['model' => $userProfile]) ?>
			<span class="user"><?= $userProfile->nomeCognome ?></span>
			<br>
		</div>
		<div class="col-md-5 col-xs-5 wrap-report">
            <div class="col-lg-12 col-xs-12">
                <strong><?= AmosMyActivities::t('amosmyactivities', 'Reporting'); ?></strong>
            </div>
            <div class="col-lg-12 col-xs-12">
                <?= Yii::$app->formatter->asDatetime($model->getUpdatedAt()) ?>
            </div>
		</div>
		<div class="col-lg-12 col-xs-12 m-t-10">
			<p class="user-report"><?= $userProfile->nomeCognome ?></p>
			
			<?php if($model->status === UserProfileValidationNotify::STATUS_ACTIVE)
						echo Amosadmin::t('amosadmin', 'Profilo validato'); 
					else  
						echo Amosadmin::t('amosadmin', 'Profilo rifiutato'); 
					?>
		</div>
		<div class="col-md-3 col-xs-12 wrap-action">
            <?php
            echo Html::a(AmosIcons::show('check') . ' ' . AmosMyActivities::t('amosmyactivities',
                    'Reading confirmation'),
                Yii::$app->urlManager->createUrl([
                    '/' . AmosAdmin::getModuleName() . '/user-profile/read-confirmation',
                    'id' => $model->id,
                ]),
                [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'confirm' => AmosMyActivities::t('amosmyactivities', 'Do you really want to mark as read?')
                    ]
                ]
            )
            ?>
        </div>
	</div>
	<hr>
