<?php if (\Yii::$app->getModule('favorites')) { ?>
    <?php
    echo \open20\amos\favorites\widgets\SelectFavoriteUrlsWidget::widget(['isCms' => true]);
    ?>
<?php } 