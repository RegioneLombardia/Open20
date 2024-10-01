<?php

use open20\amos\events\components\PartsWizardEventCreation;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\Event $model
 */

$partsWizard = new PartsWizardEventCreation([
    'event' => $model,
]);

?>
<div class="progress-container progress-container-lg col-xs-12 nop">
    <ul class="progress-indicator">
        <?php foreach ($partsWizard->getParts() as $part): ?>
            <li class="<?= $part['status'] ?>" title="<?= $part['title'] ?>">
                <?php if ($part['url']): ?>
                    <a href="<?= $part['url'] ?>" title="<?= $partsWizard->active['label'] ?>">
                        <span class="bubble-indicator"></span>
                        <span class="key-indicator"><?= ($part['index']) ?></span>
                        <?= $part['label'] ?>
                    </a>
                <?php else : ?>
                    <span class="bubble-indicator"></span>
                    <span class="key-indicator"><?= ($part['index']) ?></span>
                    <?= $part['label'] ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2 class="part"><?= $partsWizard->active['index'] ?>. <?= $partsWizard->active['label'] ?></h2>

    <ul class="progress-indicator subpart">
        <?php foreach ($partsWizard->getSubParts() as $subpart): ?>
            <li class="<?= $subpart['status'] ?>" title="<?= $subpart['title'] ?>">

                <?php if ($subpart['url']): ?>
                    <a href="<?= $subpart['url'] ?>">
                        <span class="bubble-indicator"></span>
                        <span class="txt"> <span class="key-indicator">  <?= $partsWizard->active['index'] ?>.<?= ($subpart['index']) ?></span> <?= $subpart['label'] ?></span>
                    </a>
                <?php else : ?>
                    <span class="bubble-indicator"></span>
                    <span class="txt"> <span class="key-indicator">  <?= $partsWizard->active['index'] ?>.<?= ($subpart['index']) ?></span> <?= $subpart['label'] ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($partsWizard->activeChild['index']): ?>
        <h3 class="subpart"><?= $partsWizard->active['index'] ?>
            .<?= $partsWizard->activeChild['index'] ?> <?= ($partsWizard->activeChild) ? $partsWizard->activeChild['label'] : '' ?></h3>
    <?php endif; ?>

    <?php if ($partsWizard->active['description']): ?>
        <h3><?= $partsWizard->active['description'] ?> </h3>
    <?php endif; ?>
</div>


<?php
$partsArray = $partsWizard->getParts();
$partsKeys = array_keys($partsArray);
$lastPartsIndex = count($partsArray) - 1;
?>

<!-- MENU MOBILE VERSION  (500px) -->
<?php foreach ($partsKeys as $key => $partsKey): ?>
    <?php
    // Previous element
    $prevKeyIndex = $key - 1;
    if ($prevKeyIndex < 0) {
        $prevKeyIndex = $lastPartsIndex;
    }
    $prevElement = $partsArray[$partsKeys[$prevKeyIndex]];

    // Next element
    $nextKeyIndex = $key + 1;
    if ($nextKeyIndex > $lastPartsIndex) {
        $nextKeyIndex = 0;
    }
    $nextElement = $partsArray[$partsKeys[$nextKeyIndex]];
    ?>
    <?php if ($partsWizard->active['status'] == $partsArray[$partsKey]['status']): ?>
        <div class="progress-container progress-container-sm  col-xs-12">
            <?php if ($prevKeyIndex != $lastPartsIndex): ?>
                <a href="<?= $prevElement['url'] ?>" title="<?= $prevElement['label'] ?>">
                    <?= \open20\amos\core\icons\AmosIcons::show('chevron-left', ['class' => 'pull-left am-2']); ?>
                </a>
            <?php endif; ?>
            <p>
                <span class="key-indicator"><?= ($partsArray[$partsKey]['index']) ?></span>
                <?= $partsArray[$partsKey]['label'] ?>
            </p>
            <?php if ($nextKeyIndex != 0): ?>
                <a href="<?= $nextElement['url'] ?>" title="<?= $nextElement['label'] ?>">
                    <span><?= \open20\amos\core\icons\AmosIcons::show('chevron-right', ['class' => 'pull-right am-2']); ?></span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
