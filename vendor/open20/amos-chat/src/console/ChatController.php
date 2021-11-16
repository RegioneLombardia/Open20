<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\console;

use open20\amos\chat\AmosChat;
use open20\amos\chat\models\Message;
use open20\amos\chat\models\User;
use open20\amos\admin\models\UserProfile;
use Yii;

/**
 * Class ChatController
 * @package open20\amos\chat\console
 */
class ChatController extends \yii\console\Controller
{
    public function actionSendMails()
    {
        $data = Message::find()
            ->addSelect([
                'receiver_id',
                'sender_id',
                "CONCAT(senderUserProfile.nome, ' ', senderUserProfile.cognome) AS senderCompleteName",
                'receiverUser.email AS receiverEmail',
                'COUNT(*) AS msgCount'
            ])
            ->leftJoin(User::tableName() . ' AS receiverUser', 'receiver_id = receiverUser.id')
            ->leftJoin(UserProfile::tableName() . ' AS senderUserProfile', 'sender_id = senderUserProfile.id')
            ->andWhere([
                'is_new' => true,
                'is_deleted_by_receiver' => false
            ])
            ->groupBy(['receiver_id', 'sender_id'])
            ->asArray()->all();

        foreach ($data as $userData) {
            $subject = AmosChat::t('amoschat','Nuovo messaggio su ') . Yii::$app->name;
            Yii::$app->getMailer()
                ->compose(
                    [
                        'html' => '@vendor/open20/amos-chat/src/mail/new-message/html',
                        'text' => '@vendor/open20/amos-chat/src/mail/new-message/text'
                    ], [
                    'userData' => $userData,
                    'subject' => $subject,
                ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setReplyTo([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($userData['receiverEmail'])
                ->setSubject($subject)
                ->send();
        }

        Yii::$app->end();
    }


}
