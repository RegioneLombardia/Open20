<?php

/*
Consente di attivare direttamente l'autenticazione OAuth su Open 2.0 senza passare per la pagina di login intermedia.
*/

require('../../config.php');
require_once('../../login/lib.php');

// L'ID dell'OAuth provider configurato su Moodle che si desidera utilizzare
$issuerId = get_config('local_open20integration')->oauth_issuerid;

header ('location: ' . $CFG->wwwroot . "/auth/oauth2/login.php?id=$issuerId&sesskey=". sesskey().'&wantsurl=' . rawurlencode($_GET['wantsurl']));