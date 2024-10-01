<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\mail\user
 * @category   CategoryName
 */

use open20\amos\admin\utility\UserProfileUtility;

/**
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var bool $socialAccount
 */

?>

<?= UserProfileUtility::generateSubject($profile) ?>
