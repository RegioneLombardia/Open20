<?php

//$model->usePrettyUrl = true;

/**
 * @var \open20\amos\sondaggi\models\Sondaggi $model
 * @var \open20\amos\core\record\CmsField[] $viewFields
 */

$image = $model->getModelImageUrl('square_medium', false, '/img/img_default.jpg', false, true);

if (!is_array($model->getSondaggiPubblicaziones()->one()['ruolo'])) {
    if ($model->getSondaggiPubblicaziones()->one()['ruolo'] == 'PUBBLICO') {
        if ($model->getSondaggiPubblicaziones()->one()['tipologie_entita'] > 0) {
            
            $visibility= \Yii::t('app', "Pubblico per attività");
        } else {
            $visibility= \Yii::t('app', "PUBBLICO");
        }
    }
}else{
    $visibility=\Yii::t('app', "Riservato");
}

?>

<?=
$this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-sondaggi-card',
    [
    'image' => $image,
    'dateStart' => $model->publish_date,
    'dateEnd' => $model->close_date,
    'title' => $model->getTitle(),
    'description' => $model->descrizione,
    'url' => $model->getFullViewUrl(),
    'participants' => $model->getNumeroPartecipazioni(),
    'stateLabel' => $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '',
    'contentScopesAvatar' => \open20\amos\core\utilities\CwhUtility::getTargetsString($model),
    'model' => $model,
    'visibility' => $visibility,
    'actionModify' => '/sondaggi/sondaggi/update?id=' . $model->id,
    'actionDelete' => '/sondaggi/sondaggi/delete?id=' . $model->id,
    'widthColumn' => 'col-lg-4 col-md-6',
    ]
);
?>
