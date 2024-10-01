<?php
//$typeFacebook = \open20\socialwall\utility\Social::FACEBOOK;
//$typeInstagram = \open20\socialwall\utility\Social::INSTAGRAM;
//$typeTwitter = \open20\socialwall\utility\Social::TWITTER;
//
//$socialTemplates = [
//    $typeFacebook => $this->render('social-cards/facebook_detail', ['showPostCheckboxes' => $showPostCheckboxes]),
//    $typeInstagram => $this->render('social-cards/instagram_detail', ['showPostCheckboxes' => $showPostCheckboxes]),
//    $typeTwitter => $this->render('social-cards/twitter_detail', ['showPostCheckboxes' => $showPostCheckboxes]),
//];
//
//$showPostCheckboxesText = ($showPostCheckboxes ? "true" : "false");
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">

            <div class="it-grid-list-wrapper it-image-label-grid it-masonry">
                <div id="<?= $postsContainerId; ?>" class="card-columns social-wall-container">
                    <?php
                        foreach($posts as $key => $post) {
                            switch ($post['type']) {
                                case \open20\socialwall\utility\Social::INSTAGRAM:
                                    echo Yii::$app->controller->renderPartial("@vendor/open20/social-wall/src/widgets/views/social-cards/instagram_detail", [
                                        'post' => $post,
                                        'key' => $key,
                                        'showPostCheckboxes' => $showPostCheckboxes,
                                        'postCheckboxesLabel' => $postCheckboxesLabel,
                                    ]);
                                    break;
                                case \open20\socialwall\utility\Social::TWITTER:
                                    echo Yii::$app->controller->renderPartial("@vendor/open20/social-wall/src/widgets/views/social-cards/twitter_detail", [
                                        'post' => $post,
                                        'key' => $key,
                                        'showPostCheckboxes' => $showPostCheckboxes,
                                        'postCheckboxesLabel' => $postCheckboxesLabel,
                                    ]);
                                    break;
                                case \open20\socialwall\utility\Social::FACEBOOK:
                                    echo Yii::$app->controller->renderPartial("@vendor/open20/social-wall/src/widgets/views/social-cards/facebook_detail", [
                                        'post' => $post,
                                        'key' => $key,
                                        'showPostCheckboxes' => $showPostCheckboxes,
                                        'postCheckboxesLabel' => $postCheckboxesLabel,
                                    ]);
                                    break;
                            }
                        }
                    ?>
                </div>
                <?php if($showLoadMoreButton) : ?>
                <div id="socialwall-show-more-container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <?=
                            \yii\helpers\Html::button(\open20\socialwall\Module::t('socialwall', 'Carica altri'), [
                                "id" => $showMoreBtnId,
                                'class' => 'btn btn-primary',
                                'style' => 'margin:0 auto;',
                                'data-elements-shown' => 6,
                                'onclick' => 'loadMoreContents(this);'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>


        </div>
    </div>
</div>

<?php
if($loadMoreContentsType == \open20\socialwall\utility\Social::CONTEXT_LIVE_PREVIEW) {
    echo '<script type="text/javascript">' . \open20\socialwall\utility\Social::jsLoadMoreContents($showPostCheckboxes, $loadMoreContentsType, $socialwallModelId, $postsToLoad, $postsContainerId) . '</script>';
} else {
    if($showLoadMoreButton) {
        $this->registerJs(\open20\socialwall\utility\Social::jsLoadMoreContents($showPostCheckboxes, $loadMoreContentsType, $socialwallModelId, $postsToLoad, $postsContainerId));
    }
}
?>