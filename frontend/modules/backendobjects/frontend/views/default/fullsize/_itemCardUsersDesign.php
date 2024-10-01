<?php

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\widgets\BiConnectToUserWidget;
use open20\amos\admin\widgets\BiSendMessageToUserWidget;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\core\utilities\CurrentUser;
use Yii;

$adminModule = AmosAdmin::instance();

$url = '/' . AmosAdmin::getModuleName() . '/user-profile/view?id=' . $model->id;
$imageAvatar = $model->getAvatarUrl('card_users');
$nameSurname = $model->nomeCognome;

$isCommunityManager = (UserProfileSearch::isCommunityManagerOfAtLeastOne($model->user_id));

$isFacilitator = $model->isFacilitator();

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

$additionalInfo = (!empty($model->prevalentPartnership) ? $model->prevalentPartnership->name : '');

?>


<?=
$this->render(
  '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
  [
    'avatarWrapperSize' => 'xl',
    'url' => $url,
    'imageAvatar' => $imageAvatar,
    'nameSurname' => $nameSurname,
    'additionalInfo' => $additionalInfo,
    'isFacilitator' => $isFacilitator,
    'isCommunityManager' => $isCommunityManager,
    'customCallsToAction' => $cta,
    'widthColumn' => 'col-md-6 col-lg-4',
    'additionalClass' => 'mb-4',
    'model' => $model,
    'actionModify' => '/community/community/update?id=' . $model->id,
    'actionDelete' => '/community/community/delete?id=' . $model->id,
    'removeLink' => CurrentUser::isPlatformGuest()
  ]
);
?>