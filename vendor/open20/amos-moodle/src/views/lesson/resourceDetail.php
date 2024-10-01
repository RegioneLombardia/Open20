<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle
 * @category   CategoryName
 */

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\helpers\MoodleHelper;

use open20\amos\core\helpers\Html;
?>
<p><?= AmosMoodle::_t('#resource_info') ?></p>

<?= Html::a(
    AmosMoodle::_t('#get_your_resource', [
        'modelClass' => 'Moodle Resource',
    ]),
    MoodleHelper::getMoodleOAuthLink(AmosMoodle::instance()->moodleUrl . $resourceDetails['fileUrl']),
    [
        'id' => 'btn-get-resource',
        'class' => 'btn btn-primary js-btn-entra',
        'target' => '_blank'
    ]
);
