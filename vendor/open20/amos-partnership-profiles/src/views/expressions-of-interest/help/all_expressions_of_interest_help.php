<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\expressions-of-interest\help
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;

$label = Module::t('amospartnershipprofiles', '#all_expressions_of_interest_help');

if(!empty($label)) : ?>
    <div class="m-t-10 italic">
        <?= $label ?>
    </div>
<?php endif; ?>
