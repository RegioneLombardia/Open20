<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\events
 * @category   CategoryName
 */

namespace open20\amos\admin\events;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileMailUtility;
use Yii;
use yii\base\Event;

/**
 * Class AdminWorkflowEvent
 * @package open20\amos\admin\events
 */
class AdminWorkflowEvent implements AdminWorkflowEventInterface
{
    private $roles = [
        'CREATORE_NEWS',
        'CREATORE_DISCUSSIONI'
    ];

    /**
     * @inheritdoc
     */
    public function assignCreatorRoles(Event $event)
    {
        /** @var UserProfile $userProfile */
        $userProfile = $event->data;
        $userProfile->validato_almeno_una_volta = 1;
        $userProfile->update(false);
        $userId = $userProfile->user_id;
        $inUpdateUserRoles = Yii::$app->authManager->getRolesByUser($userId);

        foreach ($this->roles as $roleStr) {
            if (!isset($inUpdateUserRoles[$roleStr])) {
                $auth = Yii::$app->authManager;
                $roleObj = $auth->getRole($roleStr);
                $auth->assign($roleObj, $userId);
            }
        }
    }

    /**
     * @param Event $event
     */
    public function afterEnterStatusNotValidated(Event $event){
        $userProfile = $event->data;
        return UserProfileMailUtility::sendEmailValidationRejected($userProfile);
    }
    /**
     * @param Event $event
     */
    public function afterEnterStatusToValidate(Event $event){
        $userProfile = $event->data;
        $nomeCognome = '';
        $facilitatore = $userProfile->facilitatore;
        if($facilitatore){
            $nomeCognome = $facilitatore->nomeCognome;
        }
        \Yii::$app->session->addFlash('success',AmosAdmin::t('amosadmin', "La tua richiesta è stata inviata al Facilitatore {nomeCognome}.<br> Riceverai un riscontro sulla validazione del tuo profilo.",[
            'nomeCognome' => $nomeCognome
        ]));
    }
}
