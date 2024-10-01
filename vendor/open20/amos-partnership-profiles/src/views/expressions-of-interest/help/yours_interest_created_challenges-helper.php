<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\bestpratices\views\best-pratice\help
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;

$label = Module::t('amospartnershipprofiles', '#yours_interest_create_challenges');

if(!empty($label)) : ?>
    <div class="yours-interest-create-challenges">
        <?= $label ?>
    </div>
<?php endif; ?>
