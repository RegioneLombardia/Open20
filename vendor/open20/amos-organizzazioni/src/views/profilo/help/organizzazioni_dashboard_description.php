<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo\help
 * @category   CategoryName
 */

use open20\amos\organizzazioni\Module;

$label = Module::t('amosorganizzazioni', '#organizzazioni_dashoard_description');

if(!empty($label)) : ?>
    <div class="dashoard-description">
        <?= $label ?>
    </div>
<?php endif; ?>
