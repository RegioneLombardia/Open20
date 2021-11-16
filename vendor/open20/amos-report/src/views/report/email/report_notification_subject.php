<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\views\report\email
 * @category   CategoryName
 */

use open20\amos\report\AmosReport;

/**
 * @var string $reportCreatorName
 * @var \open20\amos\core\record\Record $contentModel
 */

?>

<?= $reportCreatorName . " " . AmosReport::t('amosreport', "sent a report for the") . " " . AmosReport::t('amosreport', 'content') . " '" . $contentModel . "'"; ?>
