<?php

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\admin\widgets\BiConnectToUserWidget;
use open20\amos\admin\widgets\BiSendMessageToUserWidget;
use open20\amos\core\utilities\CurrentUser;
use yii\helpers\Url;

$adminModule = AmosAdmin::instance();
//$isCommunityManager = (UserProfileSearch::isCommunityManagerOfAtLeastOne($model->user_id));

$url = Url::to(['/amosadmin/user-profile/view', 'id' => $model->user_id]);
$cta = '';
if (!\Yii::$app->user->isGuest) {
    if (Yii::$app->user->id != $model->user_id) {
        if ($adminModule->enableUserContacts && !$adminModule->enableSendMessage) {
            $cta = BiConnectToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => '']);
        }
        if (!$adminModule->enableUserContacts && $adminModule->enableSendMessage) {
            $cta = BiSendMessageToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => '']);
        }
    }
}

if ($adminModule && class_exists('open20\amos\admin\models\UserProfileClasses')) {
    $userProfileClass = \open20\amos\admin\models\UserProfileClasses::find()
        ->innerJoin('user_profile_classes_user_mm', 'user_profile_classes.id = user_profile_classes_user_mm.user_profile_classes_id')
        ->andWhere(['user_profile_classes_user_mm.user_id' => $model->user_id])
        ->one();
}


echo $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
    [
        'avatarWrapperSize' => 'xl',
        'url' => $url,
        'imageAvatar' => $model->getAvatarWebUrl('card_users'),
        'nameSurname' => $model->nomeCognome,
        'additionalInfo' => !empty($userProfileClass->name) ? $userProfileClass->name : '',
//        'isCommunityManager' => $isCommunityManager,
        'customCallsToAction' => $cta,
        'widthColumn' => 'col-md-6 col-lg-4',
        'additionalClass' => 'mb-4',
        'model' => $model,
        'actionModify' => '/amosadmin/user-profile/update?id=' . $model->id,
        'actionDelete' => '/amosadmin/user-profile/delete?id=' . $model->id,
        'removeLink' => CurrentUser::isPlatformGuest()
    ]
);