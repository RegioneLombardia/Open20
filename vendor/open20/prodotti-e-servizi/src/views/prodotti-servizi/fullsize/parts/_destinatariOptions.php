<?php
    foreach($dataProvider->getModels() as $model):
        echo "<option value='{$model->id}'>{$model->name}</option>" . PHP_EOL;
    endforeach;
?>