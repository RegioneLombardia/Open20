<?php

use open20\amos\videoconference\AmosVideoconference;

$nParticipants = count($participants);
$names = [];
foreach ($participants as $profile){
    $names []= $profile->nomeCognome;
}
$implodedNames = '';
if(!empty($names)){
    $implodedNames = implode(', ',$names);
}
?>
<p>
    <strong><?=AmosVideoconference::t('amosvideoconference', 'Description').": " ?></strong><?php echo $model->description?>
    <br><strong><?php echo AmosVideoconference::t('amosvideoconference', 'Partecipanti')?><?php echo " ($nParticipants): " ?></strong><?php echo $implodedNames?>
    <br><strong><?php echo AmosVideoconference::t('amosvideoconference', 'Notifica prima dell\'inizio').": " ?></strong><?php echo $model->notification_before_conference . ' ' . AmosVideoconference::t('amosvideoconference', '#minutes', ['n' => $model->notification_before_conference])?>
</p>
