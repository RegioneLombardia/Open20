<?php

use app\assets\ResourcesAsset;
use app\modules\cms\helpers\CmsHelper;

//$resourceAsset = ResourcesAsset::register($this);


$truncate = 250;
?>
<div class="avatar-wrapper avatar-extra-text mx-4">
    <div class="avatar size-xxl">
        <?php
        $image = $model->image;
        if (!is_null($image)) {
            echo $contentImage = CmsHelper::img(
                $image->getWebUrl('square_medium', false, true),
                [
                    'class' => 'el-image img',
                    'alt' => $model->name . $model->surname
                ]
            );
        }
        ?>
    </div>
    <div class="extra-text">

        <h4><?= $model->name . ' ' . $model->surname ?></h4>
        <p><?= $model->company ?></p>
    </div>
</div>