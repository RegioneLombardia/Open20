<?php

use open20\amos\core\module\BaseAmosModule;


//\yii\helpers\VarDumper::dump($media->id,6,1);
//\yii\helpers\VarDumper::dump($media->userinfo['username'],6,1);
$postLink = null;
if (!empty($media->id) && !empty($media->userinfo['username'])) {
    $postLink = 'https://twitter.com/'.$media->userinfo['username'].'/status/'.$media->id;
}
?>

    <div class="col-12 twitter-wall">
        <div class="it-grid-item-wrapper p-2">
            <div class="p-3">
                <div class="twitter_logo d-flex justify-content-between mb-4">
                <span class="mdi mdi-twitter mdi-36px twitter"></span>
                    <?php
                    if (!is_null($postLink)):
                        ?>
                        <a href="<?= $postLink ?>" title="Vai al post" target="_blank"><span class="mdi mdi-open-in-new mdi-24px"></span></a>
                        <?php
                    endif;
                    ?>
                </div>

                <div class="avatar-wrapper avatar-extra-text mb-1">
                    <div class="avatar size-lg">
                        <a href="https://twitter.com/<?= $media->userinfo['username'] ?>" target="_blank">
                        <?php
                            echo '<img src="'. $media->userinfo["profile_image_url"] .'">';
                        ?><!-- <img src="https://randomuser.me/api/portraits/women/33.jpg" alt="Giulia Neri"> -->
                        </a>
                    </div>
                    <div class="extra-text">
                        <a href="https://twitter.com/<?= $media->userinfo['username'] ?>" target="_blank"><?= BaseAmosModule::t('app', "" )?> <?= $media->userinfo["name"] ?></a>
                        <p class="public_date"><?= BaseAmosModule::t('app', "" )?> <?= $media->publication_date() ?></p>
                    </div>
                </div>
                
                <div class="it-list-wrapper mb-2">
                    <ul class="twit-counter it-list d-flex justify-content-between pb-1">
                        <?php
//                            echo '<li class="info_tweet pr-2">Follower'.'<strong>'.' '.$media->userinfo["public_metrics"]["followers_count"].'</strong>'.'</li>';
//                            echo '<li class="info_tweet pr-2">Following'.'<strong>'.' '.$media->userinfo["public_metrics"]["following_count"].'</strong>'.'</li>';
//                            echo '<li class="info_tweet pr-2">Tweet'.'<strong>'.' '.$media->userinfo["public_metrics"]["tweet_count"].'</strong>'.'</li>';
                        ?>
                    </ul>
                </div>

<!-- 
                <div class="img-responsive-wrapper">
                    <div class="img-responsive">
                        <div class="img-wrapper">
                            <img src="https://randomuser.me/api/portraits/women/33.jpg" alt="Giulia Neri">
                        </div>
                    </div>
                </div>
 -->
                <div class="twit_description pb-4">
                    <p class=""><?= nl2br($media->description()) ?></p>
                </div>
                <ul class="twit-post-counter it-list d-flex justify-content-between pb-1">
                        <li class="info_tweet retwit pr-2"><p><span class="mdi mdi-repeat icon"></span><?php echo(' '.$media->public_metrics['retweet_count']) ?></p></li>
                        <li class="info_tweet pr-2"><p><span class="mdi mdi-chat-outline icon"></span><?php echo(' '.$media->public_metrics['reply_count']) ?></p></li>
                        <li class="info_tweet pr-2"><p><span class="mdi mdi-heart-outline"></span><?php echo(' '.$media->public_metrics['like_count']) ?></p></li>
                    </ul>
                </div>
            </div>
        </div>


