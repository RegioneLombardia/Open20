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

/** @var \open20\amos\community\utilities\EmailUtil $util */

?>

<?= $util->userName. " ".  AmosCommunity::t('amoscommunity', "asked to register to"). " ". $util->community->name;?>