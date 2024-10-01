
<?= $showSinglePost ? '<div id="'.$postContainerId.'" class="card-columns social-wall-container" style="display: flex; justify-content: center;">' : "" ?>
    <?php
        switch ($post['type']) {
            case \open20\socialwall\utility\Social::INSTAGRAM:
                echo Yii::$app->controller->renderPartial("@vendor/open20/social-wall/src/widgets/views/social-cards/instagram_detail", [
                    'post' => $post,
                    'postContainerId' => ($identifyPost ? $postContainerId : null),
                    'showDeleteButton' => $showDeleteButton,
                ]);
                break;
            case \open20\socialwall\utility\Social::TWITTER:
                echo Yii::$app->controller->renderPartial("@vendor/open20/social-wall/src/widgets/views/social-cards/twitter_detail", [
                    'post' => $post,
                    'postContainerId' => ($identifyPost ? $postContainerId : null),
                    'showDeleteButton' => $showDeleteButton,
                ]);
                break;
            case \open20\socialwall\utility\Social::FACEBOOK:
                echo Yii::$app->controller->renderPartial("@vendor/open20/social-wall/src/widgets/views/social-cards/facebook_detail", [
                    'post' => $post,
                    'postContainerId' => ($identifyPost ? $postContainerId : null),
                    'showDeleteButton' => $showDeleteButton,
                ]);
                break;
        }
    ?>
<?= $showSinglePost ? '</div>': '' ?>
