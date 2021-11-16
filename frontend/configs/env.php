<?php
    use \yii\helpers\ArrayHelper;
    return ArrayHelper::merge(require("original_env.php"), require("override.php"));