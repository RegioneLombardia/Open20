<?php
//$model->usePrettyUrl = true;

$title = $model->content_title;
$idProposal = $model->reference_external;
$dateEnd = $model->datum_deadline;
$dateSubmit = $model->datum_submit;
$dateUpdate = $model->datum_update;
$country = $model->company_country_label;
$description = $model->content_summary;
$type = $model->getReferenceTypeLabel();
$url = $model->getFullViewUrl();
?>
<div class="bg-white shadow mb-4 p-3 rounded">
  <?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-proposte-collaborazione-een',
    [
      'model' => $model,
      'title' => $title,
      'idProposal' => $idProposal,
      'dateEnd' => $dateEnd,
      'dateSubmit' => $dateSubmit,
      'dateUpdate' => $dateUpdate,
      'country' => $country,
      'description' => $description,
      'type' => $type,
      'url' => $url,
    ]
  );
  ?>
</div>