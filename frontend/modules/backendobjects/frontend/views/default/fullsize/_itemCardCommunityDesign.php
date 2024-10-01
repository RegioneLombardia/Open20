<?php

use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\models\CommunityType;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\user\User;
use Yii;

$isOpenCommunity = false;
$isClosedCommunity = false;
$isPrivateCommunity = false;
$isWaitingToSigned = false;
$isSigned = false;
$dateSigned = '';

$loggedUserId  = Yii::$app->getUser()->getId();
if (!empty($loggedUserId)) {
  $userCommunity = CommunityUtil::getMemberCommunityLogged($model->id);
  $userProfile   = User::findOne($loggedUserId)->getProfile();
}

foreach ($viewFields as $field) {
  if ($field->type == 'IMAGE') {
    $image = (!is_null($model[$field->name])) ? $model[$field->name]->getWebUrl('item_community', false, true) : '/img/img_default.jpg';
  }
}

if (!empty($userProfile) && $userProfile->validato_almeno_una_volta && !is_null($userCommunity)) {
  if (in_array($userCommunity->status, [CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER, CommunityUserMm::STATUS_WAITING_OK_USER])) {
    $isWaitingToSigned = true;
  } else {
    $isSigned = true;
  }
} else {
  $isSigned = false;
}

if ($model->community_type_id == CommunityType::COMMUNITY_TYPE_OPEN) {
  $isOpenCommunity = true;
} else if ($model->community_type_id == CommunityType::COMMUNITY_TYPE_CLOSED) {
  $isClosedCommunity = true;
} else if ($model->community_type_id == CommunityType::COMMUNITY_TYPE_PRIVATE) {
  $isPrivateCommunity = true;
} else {
}

?>


<?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-community',
    [
      'image' => $image,
      'title' => $model->getTitle(),
      'url' => '/community/community/view?id=' . $model->id,
      'urlSigned' => '/community/join/open-join?id=' . $model->id,
      'dateSigned' => ($isSigned) ? $userCommunity->created_at : false,
      'isOpenCommunity' => $isOpenCommunity,
      'isClosedCommunity' => $isClosedCommunity,
      'isPrivateCommunity' => $isPrivateCommunity,
      'isSigned' => $isSigned,
      'isWaitingToSigned' => $isWaitingToSigned,
      'hideAllCtaGuest' => $hideAllCtaGuest,
      'widthColumn' => 'col-md-3 col-6',
      'model' => $model,
      'actionModify' => '/community/community/update?id='.$model->id,
      'actionDelete' => '/community/community/delete?id='.$model->id,
    ]
  );
?>