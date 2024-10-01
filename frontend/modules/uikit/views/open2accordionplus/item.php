<?php

use trk\uikit\Uikit;

/**
 * @var $this object
 * @var $data array
 * @var $item array
 */

$accordionKey=rand(1,1000);

?>


<div class="collapse-header" id="heading<?= $accordionKey ?>">
    <button data-toggle="collapse" data-target="#collapse<?= $accordionKey ?>" <?= ($item['open_on_load']=='open')? 'aria-expanded="true"' : 'aria-expanded="false"' ?> aria-controls="collapse<?= $accordionKey ?>">
        <?= $item['title'] ?>
    </button>
</div>
<div id="collapse<?= $accordionKey ?>" class="collapse <?= ($item['open_on_load']=='open')? 'show' : '' ?> " role="region" aria-labelledby="heading<?= $accordionKey ?>" >
    <div class="collapse-body">
        <?= $this->render('content', compact('item', 'data')) ?>
    </div>
</div>
