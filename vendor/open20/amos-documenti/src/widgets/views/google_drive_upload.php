<?php

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;

?>

<div class="col-xs-12">
<!--        --><?php //echo Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-tools-secondary']); ?>
    <p id="loaded-filename"></p>
</div>

<?php echo Html::hiddenInput('authtoken', null, ['id' => 'auth-token']) ?>
<?php echo Html::hiddenInput('fileid', null, ['id' => 'file-id']) ?>
<?php echo Html::hiddenInput('filename', null, ['id' => 'file-name']) ?>

<div hidden>
    <?= $form->field($model, 'drive_file_id')->hiddenInput(['id' => 'drive-file-id']) ?>
</div>

<?php //\yii\bootstrap\Modal::begin([
//    'id' => 'modal-choose-upload'
//]) ?>
<!--<div class="col-xs-12">-->
<!--    --><?php //Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-tools-secondary']); ?>
<!--    --><?php //Html::button(AmosDocumenti::t('amosdocumenti', 'Sfoglia da pc'), ['id' => 'browse-id', 'class' => 'btn btn-tools-secondary']); ?>
<!--</div>-->
<?php //\yii\bootstrap\Modal::end() ?>
