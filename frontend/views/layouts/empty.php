<?php

$module = \Yii::$app->getModule('design');

if ($module) {
    echo $this->render(
        '@vendor/open20/design/src/views/layouts/empty',
        [
            'content' => $content,
        ]
    );
}