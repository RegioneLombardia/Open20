<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles\help
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;

$challenge_description = Module::t('amospartnershipprofiles', '#expression_of_interest_challenge');
$facilitator_description = Module::t('amospartnershipprofiles', '#expression_of_interest_facilitator');
?>

<?php if(!empty($challenge_description)): ?>
    <div class="description-challenge">
        <?= $challenge_description ?>
    </div>
<?php endif; ?>
<?php if(!empty($facilitator_description)): ?>
    <div class="description-facilitator">
        <?= $facilitator_description ?>
    </div>
<?php endif; ?>

