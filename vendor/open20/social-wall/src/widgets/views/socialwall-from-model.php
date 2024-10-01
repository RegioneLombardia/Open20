<?php

use open20\socialwall\widgets\SocialwallWidget;

?>

<h4 class="text-uppercase"><?= $title ?></h4>

<?=
SocialwallWidget::widget([
    'posts' => $posts,
    'socialwallModelId' => $socialwallModelId,
    'showLoadMoreButton' => $showLoadMoreButton,
    'loadMoreContentsType' => $loadMoreContentsType,
]);
?>