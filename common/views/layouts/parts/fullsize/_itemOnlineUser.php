<?php

/**
 * @var \open20\amos\admin\models\UserProfile $userProfile
 */

use yii\helpers\Html;


/**
 * @var \open20\amos\admin\models\UserProfile $userProfile
 * @var \DateTime $dateNow
 */


$avatarImg = Html::img($userProfile->getAvatarWebUrl('original'), [
    'alt' => $userProfile->nomeCognome,
]);
$lastOnline = Yii::$app->formatter->asDatetime($userProfile->last_online);
if (!empty($userProfile->last_online) && isset($dateNow)) {
    $lastOnlineDate = new DateTime($userProfile->last_online);
    $diff = $dateNow->diff($lastOnlineDate);
    if ($diff->i < 1) {
        $lastOnline = Yii::t('amosadmin', 'Meno di un minuto fa');
    }
}

?>

<div class="item-online-user">
    <div>
        <div class="avatar">
            <?= Html::a($avatarImg, $userProfile->getFullViewUrl(), [
                'class' => 'lead font-weight-bold',
                'title' => Yii::t('amosdesign', 'Visualizza il profilo di {nameSurname} ', ['nameSurname' => $userProfile->nomeCognome]),
            ]); ?>
        </div>
        <div class="user-information">
            <?= Html::a($userProfile->nomeCognome, $userProfile->getFullViewUrl(), [
                'class' => 'lead font-weight-bold',
                'title' => Yii::t('amosdesign', 'Visualizza il profilo di {nameSurname} ', ['nameSurname' => $userProfile->nomeCognome]),
            ]); ?>
            <p class="small">
                <?= $lastOnline ?>
            </p>
        </div>
    </div>
</div>