<?php

use open20\amos\core\icons\AmosIcons;

$currentControllerAction     = $currentController."/".$currentAction;
$activeTargetActionArray     = [];
$activeTargetControllerArray = [];


if (isset($dinamicSubMenu) && !empty($dinamicSubMenu)) {
    foreach ($dinamicSubMenu as $k => $singleMenu) { 
        if (!empty($singleMenu['activeTargetController'])) {
            $activeTargetControllerArray[$k] = $singleMenu['activeTargetController']."/".$singleMenu['activeTargetAction'];
        } else {
            $activeTargetActionArray[$k] = $singleMenu['activeTargetAction'];
        }
    }
}

$collapseClass = str_replace(' ', '', $mainMenu['label']);
//pr($currentAsset);
//pr($currentAsset->baseUrl,'base');
?>

<?php if ($undo) : ?>
    <li class="nav-item undo-item ">
        <a class="nav-link align-items-center d-flex" href="<?= $undo['url'] ?>" title="<?= $undo['titleLink'] ?>">
            <div class="mx-auto text-center">
                <?= AmosIcons::show((!empty($undo['icon']) ? $undo['icon'] : ''), ['class' => 'icon icon-white'], 'am') ?>
                <div><?= $undo['label'] ?></div>
            </div>
        </a>

    
    </li>



<?php else: ?>
    
        <?php if (isset($dinamicSubMenu) && !empty($dinamicSubMenu) ) : ?>

            <li class="nav-item <?=
            ((in_array($currentAction, $activeTargetActionArray) || in_array($currentControllerAction,
                $activeTargetControllerArray) ) ? 'active' : '')
            ?>">
                <div class="step-sidebar">
                    <a class="nav-link align-items-center d-flex" data-toggle="collapse" href="#collapseSidebarMenu<?= $collapseClass ?>" title="<?= $mainMenu['titleLink'] ?>" role="button" aria-expanded="false" aria-controls="collapseSidebarMenu<?= $collapseClass ?>">
                        <div class="mx-auto text-center">
                            <?= AmosIcons::show((!empty($mainMenu['icon']) ? $mainMenu['icon'] : ''), ['class' => 'icon icon-white'], 'am') ?>
                            <div><?= $mainMenu['label'] ?></div>
                        </div>
                    </a>
                </div>
                <div class="collapse <?=
                    ((in_array($currentAction, $activeTargetActionArray) || in_array($currentControllerAction,
                        $activeTargetControllerArray)) ? 'show' : '')
                    ?>" id="collapseSidebarMenu<?= $collapseClass ?>">
                    <ul class="nav sub-nav flex-column justify-content-center text-center">
                        <?php foreach ($dinamicSubMenu as $singleMenu) : ?>
                            <?php
                            if (!empty($singleMenu['activeTargetController'])) {
                                $isActive = ($currentControllerAction == $singleMenu['activeTargetController']."/".$singleMenu['activeTargetAction']);
                            } else {
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
            if (!empty($mainMenu['activeTargetController'])) {
                $isActive = ($currentControllerAction == $mainMenu['activeTargetController']."/".$mainMenu['activeTargetAction']);
            } else {
                $isActive = ($currentAction == $mainMenu['activeTargetAction']);
            }
            ?>

            <li class="nav-item <?= ($isActive ? 'active' : '') ?>">
                <div class="step-sidebar">
                    <a class="nav-link align-items-center d-flex" href="<?= $mainMenu['url'] ?>" title="<?= $mainMenu['titleLink'] ?>">
                        <div class="mx-auto text-center">
                            <?= AmosIcons::show((!empty($mainMenu['icon']) ? $mainMenu['icon'] : ''), ['class' => 'icon icon-white'], 'am') ?>
                            <div><?= $mainMenu['label'] ?></div>
                        </div>
                    </a>
                </div>
            </li>

        <?php endif; ?>
    
<?php endif; ?>
