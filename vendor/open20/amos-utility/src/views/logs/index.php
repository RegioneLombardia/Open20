<?php
/**@var $this \yii\web\View */
/**@var $content string */
?>
<div class="container">
    <h1>Logs</h1>
    <?php /*pr($log,'LOG');*/ ?>
    <?php
    //Parse error lines
    foreach($matches as $match) {
        pr($match, 'Match');
        //Parse Sub blocks
        foreach($match as $block) {
            pr($block,'Block');
        }
    }
    ?>
</div>