<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use app\modules\uikit\Module;

/**
 * @var $this
 * @var $data
 */
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$id = $data['id'];
$class = $data['class'][0];

?>
<div class='<?=$class?>'>
    <?php
        echo "<p class='last-update-label'>Aggiornamento</p>";
        echo "<p class='font-weight-bold'> $date_update $time_update</p>"  ;
    ?>
</div>
