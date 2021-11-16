<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiInvitations;

?>

<div id="form-results" class="mb-5 p-4 neutral-1-bg-a1">
    <div class="row">
        <div class="col-md-6">
            <div class="my-4 d-flex">

                <h5 class="font-weight-bold ">
                <?= AmosSondaggi::t('AmosSondaggi', '#search_results') ?>
                </h5>
            </div>

            <?php if ($model->type == SondaggiInvitations::SEARCH_ALL) {
                //$typeSearchText = AmosSondaggi::t('AmosSondaggi', 'Hai cercato tutti gli utenti');
            } else {
                //$typeSearchText = AmosSondaggi::t('AmosSondaggi', 'Hai cercato utenti appartenenti alla lista');
            } ?>
            <p><?= $typeSearchText ?></p><br>
            <!--        <p>--><?php //AmosSondaggi::t('AmosSondaggi', 'Con le seguenti preferenze anagrafiche:');
                                ?>
            <!--</p>-->

        </div>
        <div id="save-inputs" class="col-md-6">
            <p class="text-danger m-t-20">
            <strong><?= AmosSondaggi::t('AmosSondaggi', "#organizations_found", ['n' => $count]) ?></strong></p>
        </div>
    </div>
</div>