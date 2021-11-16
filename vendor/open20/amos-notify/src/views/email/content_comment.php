<?php
use open20\amos\admin\widgets\UserCardWidget;
?>
    <div class="media-left">
        <?= UserCardWidget::widget(['model' => $comment->createdUserProfile, 'enableLink' => false]) ?>
    </div>
     <div class="clearfix"></div>
     <p class="answer_text"><?= Yii::$app->getFormatter()->asRaw(strip_tags($comment->comment_text)) ?></p>
