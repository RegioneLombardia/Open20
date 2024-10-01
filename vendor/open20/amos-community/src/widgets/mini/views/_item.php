<?php
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\CommunityUserMm;

/**
 * @var $model \open20\amos\community\models\CommunityUserMm
 */
?>
<?php

$controller = Yii::$app->controller;
$isActionUpdate = ($controller->action->id == 'update');
$confirm = $isActionUpdate ? [
    'confirm' => AmosCommunity::t('amoscommunity','#confirm_exit_without_saving')
] : null;

$view_email_partecipants = false;
if (isset(Yii::$app->getModule('community')->view_email_partecipants)) {
    $view_email_partecipants = Yii::$app->getModule('community')->view_email_partecipants;
}

?>
<div class="member-items">
    <p class="member-item-name">
        <?php
        $adminModuleName = \open20\amos\admin\AmosAdmin::getModuleName();
        ?>
        <?= \open20\amos\core\helpers\Html::a('<strong>' . $model->user->userProfile->surnameName . '</strong>', ['/'.$adminModuleName.'/user-profile/view', 'id' => $model->user->userProfile->id ], [
            'data' => $confirm
        ])?>
    </p>

    <p class="member-item-role <?= $model->role ?>">
        <?= AmosCommunity::t('amoscommunity', $model->role) ?>
    </p>
    <span class="member-item-status <?= $model->status ?>">
        <?= AmosCommunity::t('amoscommunity', $model->status) ?>
    </span>
    <p>
        <?= ($view_email_partecipants && $checkCM) ? $model->user->email : '' ?>
    </p>
</div>

