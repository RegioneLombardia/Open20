<?php

use open20\amos\moodle\helpers\MoodleHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
?>
<div class="moodle-topic-index">
    <?php  $moodleUrl = MoodleHelper::getMoodleOAuthLink(\Yii::$app->getModule('moodle')->moodleUrl);  ?>   
     <iframe src="<?=$moodleUrl ?>" width="100%" height="600px" frameborder="0"></iframe>
     <?php /* $moodleUrlScorm = MoodleHelper::getMoodleOAuthLink("http://demo-cyber.cfiscuola.it/mod/scorm/player.php?scoid=79&cm=56&currentorg=legge_organization&display=popup&mode=normal");?>
    
   <A href="<?=$moodleUrlScorm ?>">aaaaa</a> */ ?>
  <?php /*   <iframe src="<?=$moodleUrlScorm ?>" width="100%" height="600px" frameborder="0"></iframe> */ ?>
</div>
