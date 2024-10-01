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

$description = Module::t('amospartnershipprofiles', '#partnership_profiles_dashboard_description');
?>

<?php if(!empty($description)): ?>
    <div class="dashboard-description">
        <?= $description ?>
    </div>
<?php endif; ?>

