<?php
$widgetClassname2 = \openinnovation\landing\widgets\graphics\WidgetGraphicLiveChat::className();
$widget2 = \Yii::createObject($widgetClassname2);
echo '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget(['url' => '/community/join/live-chat']) . '</div>';