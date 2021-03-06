<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\community
 * @category   CategoryName
 */

/**
 * @var boolean $atLeastOnePermission
 * @var array $buttons
 * @var string $mainDivClasses
 */

use lispa\amos\core\icons\AmosIcons;

?>
<?php if ($atLeastOnePermission): ?>
    <div class="manage-network-scope <?= $mainDivClasses ?>">
        <div class="dropdown">
            <a class="manage-menu" data-toggle="dropdown" href="" aria-expanded="true"
               title="<?= Yii::t('amoscore', 'Menu contestuale') ?>">
                <?= AmosIcons::show('settings', [], AmosIcons::IC) ?>
                <span class="sr-only"><?= Yii::t('amoscore', 'Menu contestuale') ?></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <?php foreach ($buttons as $button): ?>
                    <li><?= $button ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
