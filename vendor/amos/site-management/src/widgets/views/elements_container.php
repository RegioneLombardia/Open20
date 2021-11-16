<?php
/**
 * @var $elements array
 */
?>
<?php
$isMasonry = 1;
if(!empty($container)){
    $isMasonry = $container->is_masonry;
}
?>

    <div class="<?= $isMasonry ? 'grid' : ''?> <?= !empty($options['class']) ? $options['class'] : ''?>" <?= !empty($options['id']) ? "id='".$options['id']."'" : ''?>>
        <?php if($isMasonry){ ?>
            <div class="grid-sizer"></div>
            <div class="gutter-sizer"></div>
        <?php } ?>
        <?php
        /** @var  $element \amos\sitemanagement\models\SiteManagementElement*/
        foreach ($elements as $element){
            if(!empty($template)){
                $widgetsValues = \amos\sitemanagement\widgets\SMContainerWidget::getWidgetExampleValuesFromTemplate($template);
                $view_path = $template->view_path;
            } else {
                $widgetsValues = \amos\sitemanagement\widgets\SMContainerWidget::getWidgetValuesFromElement($element);
                $view_path = $element->siteManagementTemplate->view_path;
            }
            ?>
            <div class="<?= $isMasonry ? 'grid-item' : 'col-md-'.(12/$container->num_columns) . ' ' . 'col-xs-12 item-no-masonry'?>">
                <div class="wrap-item-sitemanagement">
                    <?php echo $this->render($view_path, ['element' => $element, 'widgetsValues' => $widgetsValues])?>
                </div>
            </div>
        <?php } ?>
    </div>