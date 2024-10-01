<?php
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;

$expireDate = PartnershipProfilesUtility::calcExpiryDateStr($model);
$publishedAt = $model->getPublicatedAt();
$statesCounter = $model->getExpressionsOfInterestStatesCounter();
$url = $model->getFullViewUrl();
$pubblicationRule = $model->regola_pubblicazione;
$status = $model->getWorkflowStatusLabel();
$title = $model->getTitle();
$shortTitle = $model->getShortDescription();
?>

<div class="bg-white shadow mb-4 p-3 rounded">
  <?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-proposte-collaborazione',
    [
      'model' => $model,
      'expireDate' => $expireDate,
      'publishedAt' => $publishedAt,
      'title' => $title,
      'shortTitle' => $shortTitle,
      'url' => $url,
      'status' => $status,
      'statesCounter' => $statesCounter,
      'pubblicationRule' => $pubblicationRule,
            
    ]
  );
  ?>
</div>

