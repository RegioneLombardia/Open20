<?php

use luya\admin\Module as Admin;
use luya\helpers\Url;
?>

<!-- TEMPORANEO DA VALUTARE -->
<style>
    .debug {
        display: none !important;
    }

    .luyasearch {
        display: none !important;
    }
</style>

<?= $this->render("angular-cms-directives"); ?>
<?= $this->render("angular-cms-loader"); ?>

<div class="luya">
    
    <div ui-view class="luya-main-wrapper"></div>

    <?= $this->render("angular-cms-search"); ?>
    <?= $this->render("angular-cms-debug"); ?>
    <?= $this->render("angular-cms-messages"); ?>

</div>