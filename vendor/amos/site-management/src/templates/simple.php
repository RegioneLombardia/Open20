<div class="box item-background-img"
     style="background-image:url(<?= !empty($widgetsValues['image']) ? $widgetsValues['image']  : '' ?>);height:350px;">
    <h1><?= !empty($widgetsValues['title']) ? $widgetsValues['title']  : '' ?></h1>
    <p><?= !empty($widgetsValues['description']) ? $widgetsValues['description']  : '' ?></p>
    <p class="code"><?= !empty($widgetsValues['code']) ? $widgetsValues['code'] : '' ?></p>
    <a class="btn btn-primary" href="<?= !empty($widgetsValues['button_link']) ? $widgetsValues['button_link']  : '' ?>" title="<?= !empty($widgetsValues['button_text']) ? $widgetsValues['button_text']  : '' ?>"><?= !empty($widgetsValues['button_text']) ? $widgetsValues['button_text']  : '' ?></a>
</div>