<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\bestpratices\views\best-pratice\help
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;

$label = AmosAdmin::t('amosadmin', '#yours_validated_users');

if(!empty($label)) : ?>
    <div class="yours-validated-users-description">
        <?= $label ?>
    </div>
<?php endif; ?>
