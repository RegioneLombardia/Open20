<?php

use open20\amos\admin\utility\UserProfileUtility;
use yii\helpers\Html;

/**
 * @var \open20\amos\admin\models\UserProfile $userProfile
 * @var \open20\amos\admin\models\UserProfile $lastOnlineUserProfiles
 * @var \open20\design\assets\BootstrapItaliaDesignAsset $currentAsset
 * @var \yii\web\View $this
 * @var \open20\amos\admin\AmosAdmin $moduleAdmin
 */


$lastOnlineUserProfiles = UserProfileUtility::getLastOnlineUserProfiles(5, true);
$dateNow = new DateTime();
?>

<!-- online user dialog -->

<div class="dialog-online-user modal" role="dialog" id="onlineUserDialog" aria-labelledby="onlineUserDialogTite">
    <div class="dialog-body" role="document">
            <div class="dialog-header">
                <h5 class="dialog-title" id="onlineUserDialogTite"><?= Yii::t('amosadmin', 'Utenti online') ?></h5>
                <button class="close" title="Chiudi" type="button" onclick="toggleOnlineUserDialog()">
                    <span class="mdi mdi-close "></span>
                </button>
            </div>
            <div class="dialog-content">
                <?php
                if (!empty($lastOnlineUserProfiles)) {
                    foreach ($lastOnlineUserProfiles as $userProfile) {
                        echo $this->render('_itemOnlineUser', [
                            'userProfile' => $userProfile,
                            'dateNow' => $dateNow,
                            'currentAsset' => $currentAsset
                        ]);
                    }
                    ?>
                    <div class="cta text-center">
                        <?= Html::a(Yii::t('amosadmin', 'Tutti gli utenti'), [
                            '/' . $moduleAdmin::getModuleName() . '/user-profile/index', 'UserProfileSearch' => [
                                'orderAttribute' => $userProfile::tableName() . '.last_online',
                                'orderType' => SORT_DESC
                            ]
                        ], [
                            'title' => Yii::t('amosadmin', 'Visualizza tutti gli utenti'),
                        ]); ?>
                    </div>
                <?php } ?>
            </div>

    </div>
</div>