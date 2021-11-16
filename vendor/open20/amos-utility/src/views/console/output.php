<!DOCTYPE html>

<?php
use open20\amos\core\forms\CloseButtonWidget;
?>

<div>
<?php
    echo '<blockquote>'.nl2br ($output).'</blockquote>';
?>
    
<div class="col-sm-12 ">
    <?= CloseButtonWidget::widget(['urlClose' => '/utility/console/index']) ?>
</div>

</div>
