<?php

/**
 * Lombardia Informatica S.p.A.
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

<section>
    <div class="row">
        <div class="col-xs-3 col-md-2">
            <div class="img-profile">
                <?php
                $url = $model->getAvatarUrl('original', [
                    'class' => 'img-responsive'
                ]);
                Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                try {
                    $getHorizontalImageClass = Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)['class'];
                    $getHorizontalImageMarginLeft = 'margin-left:' . Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)["margin-left"] . 'px;margin-top:' . Yii::$app->imageUtility->getHorizontalImage($model->userProfileImage)["margin-top"] . 'px;';
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
        <div class="col-xs-9 col-md-10 nop">
            <div>
                <h2><strong><?= $model->getNomeCognome() ?></strong></h2>
            </div>
            <div>
                <?= ($model->presentazione_breve ? $model->presentazione_breve : '-') ?>
            </div>
        </div>
    </div>
</section>
<hr>
