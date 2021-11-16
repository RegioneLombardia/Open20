

<?php
$currentControllerAction = $currentController."/".$currentAction;
$activeTargetActionArray = [];
$activeTargetControllerArray = [];


if (isset($dinamicSubMenu) && !empty($dinamicSubMenu)) {
    foreach ($dinamicSubMenu as $k => $singleMenu) {
        if(!empty($singleMenu['activeTargetController'])){
            $activeTargetControllerArray[$k] = $singleMenu['activeTargetController']."/".$singleMenu['activeTargetAction'];
        }
        else {
            $activeTargetActionArray[$k] = $singleMenu['activeTargetAction'];
        }
    }
}

$collapseClass = str_replace(' ','', $mainMenu['label']);
?>

<?php if (isset($dinamicSubMenu) && !empty($dinamicSubMenu)) : ?>

    <li class="nav-item <?= ((in_array($currentAction, $activeTargetActionArray) || in_array($currentControllerAction, $activeTargetControllerArray) )? 'active' : '') ?>">
        <div class="plugin">
            <a class="nav-link align-items-center d-flex" data-toggle="collapse" href="#collapseSidebarMenu<?= $collapseClass ?>" title="<?= $mainMenu['titleLink'] ?>" role="button" aria-expanded="false" aria-controls="collapseSidebarMenu<?= $collapseClass ?>">
                <div class="mx-auto text-center">
                    <svg class="icon icon-md icon-white">
                        <use xlink:href="<?= $currentAsset->baseUrl . $mainMenu['icon'] ?>"></use>
                    </svg>
                    <div><?= $mainMenu['label'] ?></div>
                </div>
            </a>
        </div>
        <div class="collapse <?= ((in_array($currentAction, $activeTargetActionArray) || in_array($currentControllerAction, $activeTargetControllerArray))? 'show' : '') ?>" id="collapseSidebarMenu<?= $collapseClass ?>">
            <ul class="nav sub-nav flex-column justify-content-center text-center">
                <?php foreach ($dinamicSubMenu as $singleMenu) : ?>
                <?php
                    if(!empty($singleMenu['activeTargetController'])){
                        $isActive = ($currentControllerAction == $singleMenu['activeTargetController']."/".$singleMenu['activeTargetAction']);
                    }else {
                        $isActive = ($currentAction == $singleMenu['activeTargetAction']);
                    }
                    ?>

                    <li class="nav-item <?= $isActive ? 'active' : '' ?>">
                        <a href="<?= $singleMenu['url'] ?>" title="<?= $singleMenu['titleLink'] ?>"><?= $singleMenu['label'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </li>

<?php else : ?>
    <?php
    if(!empty($singleMenu['activeTargetController'])){
        $isActive = ($currentControllerAction == $mainMenu['activeTargetController']."/".$mainMenu['activeTargetAction']);
    }else {
        $isActive = ($currentAction == $mainMenu['activeTargetAction']);
    }
    ?>

    <li class="nav-item <?= ($isActive ? 'active' : '') ?>">
        <div class="plugin">
            <a class="nav-link align-items-center d-flex" href="<?= $mainMenu['url'] ?>" title="<?= $mainMenu['titleLink'] ?>">
                <div class="mx-auto text-center">
                    <svg class="icon icon-md icon-white">
                        <use xlink:href="<?=  $currentAsset->baseUrl .$mainMenu['icon'] ?>"></use>
                    </svg>
                    <div><?= $mainMenu['label'] ?></div>
                </div>
            </a>
        </div>
    </li>

<?php endif; ?>