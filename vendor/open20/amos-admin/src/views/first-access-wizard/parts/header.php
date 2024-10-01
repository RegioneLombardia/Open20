<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\first-access-wizard\parts
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 */

?>

<section class="m-b-35">
    
        <div class="avatar-wizard-profile">
        <div>
            <div class="img-profile">
                <?php
                $url = $model->getAvatarUrl('original', [
                    'class' => 'img-responsive'
                ]);
                Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                try {
                    $getHorizontalImageClass = (!empty($model->userProfileImage) ? Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)['class'] : '');
                    $getHorizontalImageMarginLeft = (!empty($model->userProfileImage) ? 'margin-left:' . Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)["margin-left"] . 'px;margin-top:' . Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)["margin-top"] . 'px;' : '');
                } catch (\Exception $ex) {
                    $getHorizontalImageClass = '';
                    $getHorizontalImageMarginLeft = '';
                }
                ?>
                <?= Html::img($url, [
                    'class' => 'img-responsive ' . $getHorizontalImageClass,
                    'style' => $getHorizontalImageMarginLeft,
                    'alt' => AmosAdmin::t('amosadmin', 'Profile Image')
                ]);
                ?>
            </div>
            </div>
        <div>
            <div>
                <h3><strong><?= $model->getNomeCognome() ?></strong></h3>
            </div>
            <div class="text-muted small">
                <p><?= ($model->presentazione_breve ? $model->presentazione_breve : '-') ?></p>
            </div>
        </div>
        </div>
</section>

