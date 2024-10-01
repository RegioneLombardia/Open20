<?=
    \open20\socialwall\widgets\SocialwallWidget::widget([
        'posts' => $posts,
        'socialwallModelId' => $socialwallModelId,
        'loadMoreContentsType' => \open20\socialwall\utility\Social::CONTEXT_LIVE_PREVIEW . "-sess-" . \Yii::$app->session->id,
    ]);
?>