<div class="box item-background-img text-bottom-left text-clear"
     style="background-image:url('<?= !empty($widgetsValues['image']) ? $widgetsValues['image']  : '' ?>');height:550px;">
    <p class="category"><?= !empty($widgetsValues['new']) ? $widgetsValues['new']  : '' ?></p>
    <p class="date"><?= !empty($widgetsValues['data']) ? $widgetsValues['data']  : '' ?></p>
    <h1><?= !empty($widgetsValues['title']) ? $widgetsValues['title']  : '' ?></h1>
    <p><?= !empty($widgetsValues['description']) ? $widgetsValues['description']  : '' ?></p>
    <a href="<?= !empty($widgetsValues['link_forward']) ? $widgetsValues['link_forward'] : '' ?>"
       title="<?= \amos\sitemanagement\Module::t('amossitemanagement', '#go_details') ?>">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only"><?= \amos\sitemanagement\Module::tHtml('amossitemanagement', '#go_details') ?></span>
    </a>
</div>