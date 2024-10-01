<?php

use open20\socialwall\utility\SocialWallItem;
// use open20\design\assets\BootstrapItaliaDesignAsset;

// $bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

if(count($medias) > 0) :
?>


<div class="it-grid-list-wrapper it-image-label-grid it-masonry">
    <div class="card-columns social-wall-container">
        <?php
        /* @var $media SocialWallItem */
        foreach ($medias as $media) {
            ?>
        <?php
        switch ($media->getItem_type()) {
            case \open20\socialwall\utility\Social::INSTAGRAM:
                echo Yii::$app->controller->renderPartial("instagram_detail", [
                                        'media' => $media
                                    ]);
                break;
            case \open20\socialwall\utility\Social::TWITTER:
                echo Yii::$app->controller->renderPartial("twitter_detail", [
                                        'media' => $media
                                    ]);
                break;
            default:
                echo "Unknown type : ".$media->getItem_type();
                break;
        }
        ?>

        <?php
    }
    ?>
    </div>
</div>

<?php endif; ?>