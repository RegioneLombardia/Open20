<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community\email
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;

/**
 * @var \open20\amos\community\utilities\EmailUtil $util
 */

?>
    <?= AmosCommunity::t('amoscommunity', "Invitation to"). " ".$util->community->name . " ".  AmosCommunity::t('amoscommunity', "has been rejected by"). " ". $util->userName ;?>