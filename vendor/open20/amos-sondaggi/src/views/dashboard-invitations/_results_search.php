<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\controllers\DashboardInvitationsController;
use open20\amos\sondaggi\models\SondaggiInvitations;

/** @var $target DashboardInvitationsController */
if ($target == SondaggiInvitations::TARGET_USERS) {
    $message = AmosSondaggi::t('amossondaggi', 'Sono stati trovati {n} utenti', ['n' => $count]);
}
else if ($target == SondaggiInvitations::TARGET_ORGANIZATIONS) {
    $message = AmosSondaggi::t('amossondaggi', '#organizations_found', ['n' => $count]);
}
?>

<div id="form-results" class="mb-5 p-4 neutral-1-bg-a1">
    <div class="row">
        <div class="col-xs-12">
            <div class="my-4 d-flex">
                <h5 class="font-weight-bold ">
                    <?= AmosSondaggi::t('amossondaggi', '#search_results') ?>
                </h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="save-inputs" class="col-xs-12">
            <p class="text-danger m-b-20">
                <strong>
                    <?= $message ?>
                </strong>
            </p>
        </div>
    </div>
</div>