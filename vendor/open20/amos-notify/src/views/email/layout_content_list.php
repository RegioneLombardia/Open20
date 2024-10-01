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
use open20\amos\core\interfaces\ModelImageInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\builder\ContentMailBuilder;

if (!empty($profile)) {
    $this->params['profile'] = $profile;
}
?>

<tr>
    <td colspan="2" style="padding-bottom:10px;">
        <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%">
            {{content_list}}
        </table>
    </td>
</tr>