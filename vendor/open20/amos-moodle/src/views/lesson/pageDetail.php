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
<p><?= AmosMoodle::_t('#attemps_number', ['attemps' => count($pageDetails['attemps'])]) ?></p>

<?= Html::a(AmosMoodle::_t('#resource_info', 
    [
        'modelClass' => 'Moodle Page',
    ]),
    MoodleHelper::getMoodleOAuthLink(AmosMoodle::instance()->moodleUrl . $pageDetails['fileUrl']),
    [
        'id' => 'btn-get-resource',
        'class' => 'btn btn-primary',
        'target' => '_blank'
    ]
);
