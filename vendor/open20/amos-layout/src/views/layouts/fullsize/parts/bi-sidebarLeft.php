<?php

use open20\amos\layout\Module;

?>
<?php
$currentAction = $this->context->action->id;
$currentController =Yii::$app->controller->id;
?>
<nav class="sidebar white-color neutral-2-bg-a6 collapse" id="sidebarLeftMenu">
    <div class="sidebar-sticky sidebar-elite py-4">
        <ul class="nav flex-md-column justify-content-center">
            <?php


            if (!empty(\Yii::$app->getView()->params['bi-menu-sidebar'])) {
                $menuSidebar = \Yii::$app->getView()->params['bi-menu-sidebar'];
                foreach ($menuSidebar as $item){
                    $sidebarParams = [
                        'currentAsset' => $currentAsset,
                        'currentAction' => $currentAction,
                        'currentController' => $currentController,
                    ];
                    $sidebarParams = \yii\helpers\ArrayHelper::merge($sidebarParams, $item);
                    echo $this->render('_sidebarPluginMenuCollapse', $sidebarParams);
                }
            }
            ?>
        </ul>
    </div>
</nav>