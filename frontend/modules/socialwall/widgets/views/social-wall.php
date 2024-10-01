<?php
$viewSocialWall = $this->render(
    $viewToRender,
    [
        'medias' => $medias,
        'widget' => $widget,
        'viewPath' => $viewPath
    ]
);

if(!empty($viewSocialWall)):
?>

<div class="m-t-25">
    <strong class="text-uppercase"><?= Yii::t('socialwall', 'Social Wall') ?></strong>
</div>

<?= $viewSocialWall ?>

<?php endif; ?>