<?php

use open20\amos\documenti\assets\ModuleDocumentiWidgetAsset;

$documentiAsset = ModuleDocumentiWidgetAsset::register($this);
?>
<?php if (!empty($buttons)) { ?>
    <div class="dropdown dropdown-manage dropleft">
        <a class="dropdown-toggle btn btn-xs btn-outline-tertiary btn-icon" href="javascript:void(0)" role="button" id="dropdownManageMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon">
            <use xlink:href="<?= $documentiAsset->baseUrl ?>/sprite/material-sprite.svg#cog"></use>
            </svg>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownManageMenu">
            <div class="link-list-wrapper">
                <ul class="link-list">
                    <?php foreach ($buttons as $btn) { ?>
                        <li>
                            <a class="list-item" href="<?= $btn['url'] ?>" <?= $btn['options'] ?>>
                                <span><?= $btn['label'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>