<?php


?>
<section>
    <div class="form-group">
        <label class="control-label"><?= \Yii::t('app', 'Azienda') ?></label>
        <?= \yii\helpers\Html::textInput('azienda', $model->azienda, ['class' => 'form-control']) ?>
    </div>
</section>
