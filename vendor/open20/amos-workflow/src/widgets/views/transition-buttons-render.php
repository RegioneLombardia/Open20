<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var string $widgetClass
 * @var string $resetButton
 * @var array $buttons
 * @var string $hiddenActions
 * @var string $notificationInput
 * @var string $renderStatusError
 */

?>

<div class="workflow-transition-button-widget col-xs-12">
    <div class="workflow-button-container m-t-10 m-b-30 nop">
        <?= $resetButton ?>
    </div>
    <div class="workflow-buttons-container nop">
        <?php foreach ($buttons as $button) : ?>
        <?php
            $class = '';
            if(!empty($button['class'])){
            $class = $button['class'];
            }?>
            <div class="workflow-form-actions workflow-button-container m-t-10 m-b-10 <?= $class?>">
                <?= $button['button']; ?>
                <p><?= $button['stateDescriptor'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="field-workflow-status_id required has-error">
    <div class="row">
        <div class="col-xs-12">
            <div class="tooltip-error-field">
                <?= $renderStatusError; ?>
            </div>
        </div>  
    </div>
</div>

<?= $hiddenActions; ?>
<?= $notificationInput ?>
