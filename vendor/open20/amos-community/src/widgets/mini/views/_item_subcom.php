<?php

use open20\amos\community\AmosCommunity;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;

/**
 * @var yii\web\View $this
 * @var $model \open20\amos\community\models\CommunityUserMm
 */

$controller = Yii::$app->controller;
$isActionUpdate = ($controller->action->id == 'update');
$confirm = $isActionUpdate ? [
    'confirm' => BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
] : null;

?>
<div class="member-items">
    <p class="member-item-name">
        <?= Html::a('<strong>' . $model->name . '</strong>', ['/community/community/view', 'id' => $model->id], [
            'data' => $confirm
        ]) ?>
    </p>
    <p class="member-item-role">
        <?= AmosCommunity::t('amoscommunity', $model->communityType->name) ?>
    </p>
    <span class="member-item-status">
         <?= $model->workflowStatus->label ?>
    </span>
</div>
