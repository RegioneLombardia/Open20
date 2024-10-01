<?php

use open20\amos\layout\Module;

?>
<?php
$currentAction = $this->context->action->id;
$currentController = Yii::$app->controller->id;
?>

<nav role="navigation" aria-label="Form sidebar" class="sidebarForm white-color neutral-2-bg-a6 collapse" id="sidebarForm">

  <div class="sidebar-sticky sidebar-content py-4">
    <ul class="nav flex-md-column justify-content-center">
      <?php
      if (!empty($viewParams)) {
        $menuSidebar = $viewParams;
        foreach ($menuSidebar as $item) {
          $sidebarParams = [
            'currentAsset' => $currentAsset,
            'currentAction' => $currentAction,
            'currentController' => $currentController,
          ];
          $sidebarParams = \yii\helpers\ArrayHelper::merge($sidebarParams, $item);

          echo $this->render('_bi-less-sidebarPluginMenuCollapse', $sidebarParams);
        }
      }
      ?>
    </ul>
  </div>
</nav>