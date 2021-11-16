<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;

/** @var \open20\amos\community\utilities\EmailUtil $util
 * @var $utilAppName
 */

?>

<?= $utilAppName . " : " . $util->userName. " ". AmosCommunity::t('amoscommunity', "registered to"). " ". $util->community->name;?>