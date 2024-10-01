<?php

use app\modules\utility\Utility;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\admin\models\UserContact;
use open20\amos\admin\widgets\BiConnectToUserWidget;
use open20\amos\admin\widgets\BiSendMessageToUserWidget;
use open20\amos\core\utilities\CurrentUser;
use yii\helpers\Url;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 * @var \open20\amos\admin\AmosAdmin $adminModule
 */


$adminModule = AmosAdmin::instance();
$url = Url::to(['/amosadmin/user-profile/view', 'id' => $model->user_id]);
$cta = '';

if (!\Yii::$app->user->isGuest) {
    if (Yii::$app->user->id != $model->user_id) {
        if ($adminModule->enableUserContacts) {
            $userContactsCount = Utility::getUserContactsCount($model);
        }
        if ($adminModule->enableUserContacts && !$adminModule->enableSendMessage) {
            $cta = BiConnectToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => '']);
        }
        if (!$adminModule->enableUserContacts && $adminModule->enableSendMessage) {
            $cta = BiSendMessageToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => '']);
        }
    }
}


echo $this->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar', [
    'avatarWrapperSize' => 'xl',
    'url' => $url,
    'imageAvatar' => $model->getAvatarWebUrl('card_users'),
    'nameSurname' => $model->nomeCognome,
    'additionalInfo' => isset($userContactsCount) ? Yii::t('amosadmin', '{x} collegamenti', ['x' => $userContactsCount]) : null,
    'customCallsToAction' => $cta,
    'widthColumn' => '',
    'additionalClass' => 'mb-4',
    'model' => $model,
    'actionModify' => '/amosadmin/user-profile/update?id=' . $model->id,
    'actionDelete' => '/amosadmin/user-profile/delete?id=' . $model->id,
//        'removeLink' => CurrentUser::isPlatformGuest()
]);