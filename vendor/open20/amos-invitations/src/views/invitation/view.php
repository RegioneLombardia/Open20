<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\views\invitation
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\invitations\Module;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\invitations\models\Invitation $model
 */

/** @var Module $invitationsModule */
$invitationsModule = Module::instance();

$this->title = Module::t('amosinvitations', '#invite_view_title', ['nameSurname' => $model->getNameSurname()]);

$attributes = [
    'name',
    'surname',
];
if ($invitationsModule->enableFiscalCode) {
    $attributes[] = 'fiscal_code';
}
if ($invitationsModule->enableInviteMessage) {
    $attributes[] = 'message:html';
}
$attributes[] = 'send_time:datetime';
$attributes[] = 'send:boolean';
$attributes[] = 'invitationUser.email';
$attributes[] = 'invitationUser.numberNotificationSended';
$attributes[] = 'invitationUser.numberNotificationSendedByMe';
$attributes[] = 'created_at:datetime';

?>
<div class="invitation-view col-xs-12">
    <div class="row">
        <div class="col-xs-12 m-t-5 m-b-5">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
            ]); ?>
        </div>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::a(Module::t('amosinvitations', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
