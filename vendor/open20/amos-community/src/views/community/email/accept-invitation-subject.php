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

<?= AmosCommunity::t('amoscommunity', "Invitation to"). " ". $util->userName  . " ".  AmosCommunity::t('amoscommunity', "confirmed the invitation to the"). " ". $util->community->name;?>
