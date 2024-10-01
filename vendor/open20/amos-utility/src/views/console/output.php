<!DOCTYPE html>

<?php
use open20\amos\core\forms\CloseButtonWidget;
?>

<div>
<?php
    echo '<pre style="background-color: #000000; color: #ffffff">'.nl2br ($output).'</pre>';
?>
    
<div class="col-sm-12 ">
    <?= CloseButtonWidget::widget(['urlClose' => '/utility/console/index']) ?>
</div>

</div>
