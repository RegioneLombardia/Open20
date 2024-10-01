<?php

//$model->usePrettyUrl = true;

$url = (isset($model->community_id)) ? '/community/join/open-join?=' . $model->community_id : '/events/event/view?id=' . $model->id;

if($model->seats_management){
  $availableSeats = $model->getEventSeats()->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]])->count();
}
?>

<?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event-item',
    [
      'image' => $model->getModelImageUrl(),
      'dateHourStart' => $model->begin_date_hour,
      'title' => $model->title,
      'url' => $url,
      'type' => $tag,
      'isPaid' => $model->paid_event,
      'availableSeats' => $availableSeats,
      'model' => $model,
      'actionModify' => '/events/event/update?id='.$model->id,
      'actionDelete' => '/events/event/delete?id='.$model->id,
    ]
  );
?>