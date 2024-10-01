<?php

//$model->usePrettyUrl = true;

$userNotSignup = true;
$url = $model->getFullViewUrl();
$urlCta = '/community/join/open-join?id=' . $model->community_id;

if($model->seats_management){
  $availableSeats = $model->getEventSeats()->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]])->count();
}

$image='';
if($model->eventLogo){
  $image=$model->eventLogo->getWebUrl('dashboard_news', false, true);
}else{
  $image=$model->getModelImageUrl();
}

?>

<?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event',
    [
      'image' => $image,
      'dateHourStart' => $model->begin_date_hour,
      'title' => $model->title,
      'summary'  => $model->summary,
      'url' => $url,
      'urlCta' => $urlCta,
      'type' => $tag,
      'isPaid' => $model->paid_event,
      'availableSeats' => $availableSeats,
      'customlabelCreateView' => 'Visualizza evento',
      'customlabelCreateSubscribe' => 'Visualizza evento',
      'model' => $model,
      'actionModify' => '/events/event/update?id='.$model->id,
      'actionDelete' => '/events/event/delete?id='.$model->id,
    ]
  );
?>