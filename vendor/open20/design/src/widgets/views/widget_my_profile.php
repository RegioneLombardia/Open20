<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    DesignPackage
 */

use open20\amos\admin\models\UserProfile;
use open20\design\widgets\WidgetMyProfile;

/**
 * @var UserProfile $model
 */

?>

<?= WidgetMyProfile::widget([
    'model' => $model
]); ?>
