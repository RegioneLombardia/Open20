<?php
use open20\gamification\Module;

?>
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <?php if (!empty($model->starting_date)) { ?>
            <div class="starting_date">
                <label><?=Module::t('gamification', 'Data di inizio')?>:</label>
                <?= Yii::$app->formatter->asTime($model->starting_date, 'dd/MM/yyyy HH:mm') ?>
                
            </div>
        <?php } ?>
    </div>
    <div class="col-xs-12 col-sm-4">
        <?php if (!empty($model->finish_date)) { ?>
            <div class="finish_date">
                <label><?=Module::t('gamification', 'Data di fine')?>:</label>
                <?= Yii::$app->formatter->asTime($model->finish_date, 'dd/MM/yyyy HH:mm') ?>
            </div>
        <?php } ?>
    </div>
</div>