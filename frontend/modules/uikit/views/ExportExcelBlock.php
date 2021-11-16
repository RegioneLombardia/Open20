<!DOCTYPE html>
<?php

use app\assets\SocialAsset;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;

SocialAsset::register($this);

?>
<div>
    <?php
    //Uikit::trace($data);
    //Uikit::trace($debug);
    //Uikit::trace($request);
    //Uikit::trace($model->attributes());
    $form            = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data', 'autocomplete' => "off",'target'=>'_blank'],
            'encodeErrorSummary' => false,
            'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']]
    ]);
    ?>


    <div class="uk-form-controls">
        <div>
            <?php
                echo Html::hiddenInput('id', $data['id']);
                echo !empty($data['description']) ? $data['description'] : 'Export';
            ?>
        </div>
        <?=
        Html::submitButton(!empty($data['submitlabel']) ? $data['submitlabel'] : 'Submit',
            ['class' => 'btn btn-primary'])
        ?>
    </div>
</div>
<?php
ActiveForm::end();
?>
</div>
