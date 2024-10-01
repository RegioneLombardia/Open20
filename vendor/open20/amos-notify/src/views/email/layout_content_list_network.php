<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\builder\ContentMailBuilder;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var $modelNetwork
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var Record[] $arrayModels
 * @var Record[] $arrayModelsComments
 * @var Record[] $arrayModelsComments
 */


$notifyModule = AmosNotify::instance();

?>

<tr>
    <td colspan='2' style='color:#4B4B4B; font-size:22px; font-weight:bold; font-family:sans-serif; padding:20px 0 0 0;'><?= AmosNotify::t('amosnotify', 'Dalle tue community') ?></td>
</tr>
{{content_list}}



