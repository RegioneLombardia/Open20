<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\views\my-activities
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\myactivities\AmosMyActivities;

/** @var $model \open20\amos\myactivities\basic\RequestToParticipateCommunityForManager */
//pr($model->getSearchString(), 'CLASSE: ' . $model->className() . ' creato: ' . $model->getCreatedAt());
//\open20\amos\core\views\assets\AmosCoreAsset::register($this);
$userProfile = UserProfile::find()->andWhere(['user_id' => $model->updated_by])->one();
?>
<?php if (!empty($userProfile)): ?>
    <div class="wrap-activity">
        <div class="col-md-1 col-xs-2 icon-plugin">
            <?= AmosIcons::show('users', [], 'dash') ?>
        </div>
        <div class="col-md-3 col-xs-5 wrap-user">
            <?= UserCardWidget::widget(['model' => $userProfile]) ?>
            <span class="user"><?= $userProfile->getNomeCognome() ?></span>
            <br>
            <?= AmosAdmin::t('amosadmin', $userProfile->workflowStatus->label) ?>
        </div>
        <div class="col-md-5 col-xs-5 wrap-report">
            <div class="col-lg-12 col-xs-12">
                <strong><?= AmosMyActivities::t('amosmyactivities', 'Request for community participation'); ?></strong>
            </div>
            <div class="col-lg-12 col-xs-12">
                <?= Yii::$app->formatter->asDatetime($model->getUpdatedAt()) ?>
            </div>
            <div class="col-lg-12 col-xs-12">
                <p class="user-report"><?= $userProfile->getNomeCognome() ?></p>
                <?= AmosMyActivities::t('amosmyactivities',
                    ' asks to accept the invitation to participate you to the community:'); ?>
                <?= $model->community->name; ?>
            </div>
            <div class="col-lg-12 col-xs-12">
                <?= Html::a(AmosIcons::show('search', [], 'dash') . ' ' . AmosMyActivities::t('amosmyactivities',
                        'View card'),
                    Yii::$app->urlManager->createUrl([
                        '/community/community/view',
                        'id' => $model->community_id
                    ])
                ) ?>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 wrap-action">
            <?= JoinCommunityWidget::widget(['model' => $model->community]) ?>
        </div>
    </div>
    <hr>
<?php endif; ?>
