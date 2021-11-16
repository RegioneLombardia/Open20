<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\views\invitation
 * @category   CategoryName
 */

use open20\amos\invitations\models\Invitation;
use open20\amos\invitations\models\InvitationUser;
use open20\amos\invitations\Module;
use yii\web\View;

/**
 * @var View $this
 * @var Invitation $invitation
 * @var InvitationUser $invitationUser
 */

$this->title = Module::t('amosinvitations', '#send-invitation-title');

?>
<div class="invitation-create">
    <?= $this->render('_form', [
        'invitation' => $invitation,
        'invitationUser' => $invitationUser,
    ]) ?>
</div>
