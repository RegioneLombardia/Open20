<?php
use open20\amos\core\module\BaseAmosModule;

$postContainerId = !empty($postContainerId) ? $postContainerId : null;
$showDeleteButton = !empty($showDeleteButton) ? $showDeleteButton : false;

//\yii\helpers\VarDumper::dump($media->id,6,1);
//\yii\helpers\VarDumper::dump($media->userinfo['username'],6,1);

//\yii\helpers\VarDumper::dump($post,6,1);
//die;
$modelOrTemplate = function ($item) use ($post) {
    if (!empty($post))
        return $post[$item];
    return '${' . $item . '}';
};

$postLink = null;
if (!empty($post['permalink'])) {
    $postLink = $post['permalink'];
}
?>

    <div class="col-12 twitter-wall" <?= !empty($postContainerId) ? "data-socialwall-post-id=\"{$postContainerId}\"" : ""; ?>>
        <div class="it-grid-item-wrapper p-2">
            <div class="p-3">
                <?php if($showDeleteButton) : ?>
                    <div class="socialwall-post-action-element m-b-5">
                        <?=
                        \yii\helpers\Html::button(
                            'x',
                            [
                                'class' => 'btn btn-danger',
                                'onClick' => "window.removeSocialwallPostAction({$postContainerId})",
                            ]
                        );
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($showPostCheckboxes)) : ?>
                    <?php if (!empty($post)) : ?>
                    <div class="select-social-post m-b-20 btn" data-post-id="<?= $key; ?>">
                        <?= \open20\amos\core\icons\AmosIcons::show('plus', [
                            'style' => "font-size: 30px;",
                            'class' => "icon-check",
                        ]); ?>
                        <?php if(!empty($postCheckboxesLabel)) : ?>
                            <span class="label-checkboxes">
                                <?= $postCheckboxesLabel ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php else : ?>
                        ${key !== "" && key !== null ?
                        '<div class="select-social-post m-b-20 btn" data-post-id="'+ key +'">' +
                            '<?= \open20\amos\core\icons\AmosIcons::show('plus', [
                                'style' => "font-size: 30px;",
                                'class' => "icon-check",
                            ]); ?>' +
                        '</div>' : ''}
                    <?php endif; ?>
                <?php endif; ?>
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
                        <a href="<?= $modelOrTemplate('profile_url') ?>" target="_blank">
                        <?php
                            echo '<img src="'. $modelOrTemplate("profile_picture_url") .'" width="100%">';
                        ?>
                        </a>
                    </div>
                    <div class="extra-text">
                        <a href="<?= $modelOrTemplate('profile_url') ?>" target="_blank"><?= BaseAmosModule::t('app', "" )?> <?= $modelOrTemplate("name") ?></a>
                        <p class="public_date"><?= BaseAmosModule::t('app', "" )?> <?= $modelOrTemplate('post_nice_datetime') ?></p>
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


                <?php if (!empty($post)) : ?>
                    <?php if (!empty($modelOrTemplate('post_image_url'))): ?>
                        <div class="img-responsive-wrapper">
                            <div class="img-responsive">
                                <div class="img-wrapper">
                                    <img src="<?= $modelOrTemplate('post_image_url') ?>" alt="Twitter Post Image">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    ${post_image_url !== "" && post_image_url !== null ?
                    '<div class="img-responsive-wrapper">'+
                        '<div class="img-responsive">'+
                            '<div class="img-wrapper">'+
                                '<img src="' + post_image_url + '" alt="Twitter Post Image">'+
                            '</div>'+
                        '</div>'+
                    '</div>' : ''}
                <?php endif; ?>

                <div class="twit_description pb-4">
                    <p class=""><?= nl2br($modelOrTemplate('post_text')) ?></p>
                </div>
<!--                <ul class="twit-post-counter it-list d-flex justify-content-between pb-1">-->
<!--                        <li class="info_tweet retwit pr-2"><p><span class="mdi mdi-repeat icon"></span>--><?php //echo(' '.$media->public_metrics['retweet_count']) ?><!--</p></li>-->
<!--                        <li class="info_tweet pr-2"><p><span class="mdi mdi-chat-outline icon"></span>--><?php //echo(' '.$media->public_metrics['reply_count']) ?><!--</p></li>-->
<!--                        <li class="info_tweet pr-2"><p><span class="mdi mdi-heart-outline"></span>--><?php //echo(' '.$media->public_metrics['like_count']) ?><!--</p></li>-->
<!--                    </ul>-->
                </div>
            </div>
        </div>


