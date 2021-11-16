<?php

use open20\amos\core\helpers\Html;

$avatar = '';
if (isset($model->profile)) {
$modelProfile =  $model->profile;
$avatar = $modelProfile->getAvatarUrl('square_small');
//$avatar = $model->profile->getAvatarUrl('small');
//$avatar_id = $model->profile->avatar_id;
}
?>
<a href="<?= '/messages/' . $model->id ?>">
    <div class="item-chat media nop" data-key=""
         data-user-contact="<?= $model->id ?>">
        <div class="media-left">
            <div class="container-round-img">
                <?= Html::img($modelProfile->getAvatarUrl('square_small'), [
                    'class' => Yii::$app->imageUtility->getRoundRelativeImage($modelProfile)['class'],
                    'alt' => $modelProfile->id
                ]) ?>
            </div>
        </div>
        <div class="media-body">
            <h5 class="media-heading"><strong><?= $model->name ?></strong></h5>
            <?= $model->username ?>
        </div>
    </div>
</a>
