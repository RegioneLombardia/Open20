<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo\email
 * @category   CategoryName
 */

use open20\amos\organizzazioni\Module;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\organizzazioni\utility\EmailUtility $util
 */

?>

<?= Module::t('amosorganizzazioni', "Registration to") . " " . $util->model->name . " " . Module::t('amosorganizzazioni', "has been rejected by") . " " . $util->refereeName; ?>
