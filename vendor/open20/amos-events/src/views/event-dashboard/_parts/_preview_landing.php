<?php
$logoUrl = '/img/img_default.jpg';
if (!empty($model->eventLogo)) {
    $logoUrl = $model->eventLogo->getWebUrl();
}
$logoUrl = Yii::$app->urlManager->createAbsoluteUrl($logoUrl);
$beginDateHour = new \DateTime($model->begin_date_hour);
if(!empty($eventLanding)){
    $class = $htmlTemplate[$eventLanding->luya_template_id];
}
else {
    $class = $classTemplate;
}
?>
<div class="preview-landing position-relative mb-4 <?= $class ?>"
     style="background-image: url('<?= $logoUrl ?>');">
    <div class="preview-info-content container-fluid d-flex h-100 p-5">
        <div class="row variable-gutters text-white w-100 m-auto">
            <div class="col-md-7 d-flex flex-column justify-content-center">
                <div class="preview-title-landing text-md-right h2 m-0 text-uppercase">
                    <?= !empty($model) ? $model->getTitle() : ''?>
                </div>
            </div>
            <div class="col-md-5 border-left border-white d-flex flex-column justify-content-center mt-3 mt-md-0 text-uppercase mb-0">
                <div class="preview-info-event h5 font-weight-semibold">
                    <?= !empty($model->eventLocation) ? $model->eventLocation->name : '' ?>
                </div>
                <div class="preview-date-event lead font-weight-light">
                    <?= !empty($model) ? $beginDateHour->format('d/m/Y') : ''?>
                </div>
            </div>

        </div>
    </div>
</div>
