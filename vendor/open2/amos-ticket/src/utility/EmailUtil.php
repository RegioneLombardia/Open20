<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\utility
 * @category   CategoryName
 */

namespace open2\amos\ticket\utility;

use open20\amos\attachments\models\File;
use open20\amos\comments\models\Comment;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\Email;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use yii\helpers\VarDumper;

/**
 * Class EmailUtil
 * @package open2\amos\ticket\utility
 */
class EmailUtil
{
    /**
     * manda la mail di creazione del ticket ai referenti perché è un ticket nuovo
     *
     * @param Ticket $ticket
     * @param TicketCategorie $model_ticket_categoria
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailNewTicketReferenti($ticket, $model_ticket_categoria, $partnershipMsg)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket, $model_ticket_categoria, $partnershipMsg);
        }

        $from = \Yii::$app->params['supportEmail'];
        //$to = [$user->email];

        $noCommunity = true;
        $ticketCategoria = $ticket->ticketCategoria;
        if (!empty($ticketCategoria) && !empty($ticketCategoria->community_id)) {
            $noCommunity = false;
        }
        $emailReferenti = TicketUtility::getEmailReferentiCategoria($ticket->ticket_categoria_id, $noCommunity, true);
        $to = $emailReferenti;

        $subject = AmosTicket::t("amosticket", 'Nuovo ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'E\' stato aperto un nuovo ticket nel plugin assistenza');
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $model_ticket_categoria->nomeCompleto . '</strong>';
        $body[] = "<strong>" . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        if ($ticket->isGuestTicket()) {
            $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $ticket->guest_name . ' ' . $ticket->guest_surname;
        } else {
            $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $ticket->createdUserProfile->nomeCognome;
        }
        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;
        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        $body[] = '<strong>' . Html::a(
            AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'),
                \Yii::$app->params['platform'] ['backendUrl'] . '/ticket/ticket/view?id=' . $ticket->id
                // \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])
            ) . '</strong><br />';
        $body[] = AmosTicket::t('amosticket', 'Ricordati di controllarlo.');

        $params = [
            //'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di inoltro del ticket ai referenti perché è un ticket inoltrato alla loro categoria
     *
     * @param Ticket $ticket
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailForwardReferenti($ticket, $partnershipMsg)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket, $partnershipMsg);
        }

        $from = \Yii::$app->params['supportEmail'];

        $ticketPrecedente = Ticket::findOne($ticket->forwarded_from_id);

        $emailReferenti = TicketUtility::getEmailReferentiCategoria($ticket->ticket_categoria_id, true, true);
        $to = $emailReferenti;

        $creatorUserProfile = $ticket->createdUserProfile;
        $nameSurname = (!is_null($creatorUserProfile) ? $creatorUserProfile->getNomeCognome() : '-');

        $subject = AmosTicket::t("amosticket", 'Inoltro ticket id. ' . $ticket->id);

        $body[] = '<br>';
        $body[] = AmosTicket::t('amosticket', "E' stato inoltrato un ticket che era stato aperto in un'altra categoria");
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $ticket->ticketCategoria->nomeCompleto . '</strong>';
        $body[] = '<strong>' . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $nameSurname;
        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;
        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        $body[] = $ticket->getAttributeLabel('forward_message') . ': ' . $ticket->forward_message;
        $body[] = AmosTicket::t('amosticket', 'Ticket ID da cui è stato fatto l\'inoltro') . ': ' . $ticketPrecedente->id;
        $body[] = AmosTicket::t('amosticket', 'Categoria da cui è stato fatto l\'inoltro') . ': ' . $ticketPrecedente->ticketCategoria->nomeCompleto;
        $body[] = '<strong>' . Html::a(AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'), \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])) . '</strong><br />';
        $body[] = AmosTicket::t('amosticket', 'Ricordati di controllarlo.');

        $params = [
            //'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di inoltro del ticket all'operatore per informarlo che è stato
     * aperto un nuovo ticket in un'altra categoria
     *
     * @param Ticket $ticket
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailForwardOperatore($ticket, $partnershipMsg)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket, $partnershipMsg);
        }

        $from = \Yii::$app->params['supportEmail'];

        $ticketPrecedente = Ticket::findOne($ticket->forwarded_from_id);

        $creatorUserProfile = $ticket->getCreatedUserProfile()->one();
        $to = $creatorUserProfile->user->email;

        $subject = AmosTicket::t('amosticket', 'Inoltro ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Il ticket id.<strong> ' . $ticketPrecedente->id . '</strong>, aperto nella categoria ' . $ticketPrecedente->ticketCategoria->nomeCompleto . ", è stato inoltrato ad un'altra categoria e perciò chiuso.");
        $body[] = AmosTicket::t('amosticket', "E' stato aperto un nuovo ticket id.<strong>" . $ticket->id . '</strong> nella categoria ' . $ticket->ticketCategoria->nomeCompleto . ' che è in attesa di essere preso in carico.');
        $body[] = AmosTicket::t('amosticket', 'Di seguito i dettagli del nuovo ticket:');
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $ticket->ticketCategoria->nomeCompleto . '</strong>';
        $body[] = '<strong>' . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;
        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        if ($ticket->forward_message_to_operator) {
            $body[] = $ticket->getAttributeLabel('forward_message') . ': ' . $ticket->forward_message;
        }

        $body[] = '<strong>' . Html::a(AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'), \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])) . '</strong>';

        $params = [
            'userProfile' => $creatorUserProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-user-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di inoltro del ticket all'operatore per informarlo che è stato
     * aperto un nuovo ticket in un'altra categoria
     *
     * @param Ticket $ticket
     * @param Comment $answare
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendCloseTicketGuestAnsware($ticket, $answare)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket, $answare);
        }

        $from = \Yii::$app->params['supportEmail'];

        $userProfile['nome'] = $ticket->guest_name;
        $userProfile['cognome'] = $ticket->guest_surname;
        $to =  $ticket->guest_email;

        $subject = AmosTicket::t('amosticket', 'Richiesta informazioni ' . $ticket->id . ' - ' .$ticket->ticketCategoria->titolo);

        $body[] = AmosTicket::t('amosticket', 'in riferimento alla vostra richiesta informazioni nr. 
        {ticketId} sulla tematica {catName}, vi riportiamo la vostra domanda e la nostra risposta.', [
            'ticketId' => $ticket->id,
            'catName' => $ticket->ticketCategoria->titolo,
        ]);
        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Domanda:');
        $description = \yii\helpers\HtmlPurifier::process($ticket->descrizione);
        echo nl2br($description);
        $body[] = AmosTicket::t('amosticket', '<strong>'.$description.'</strong>');

        $body[] = AmosTicket::t('amosticket', 'Risposta:');
        $body[] = AmosTicket::t('amosticket', '<strong>'.$answare->comment_text.'</strong>');

        $body[] = AmosTicket::t('amosticket', '');
        $body[] = AmosTicket::t('amosticket', 'Cordiali saluti');

        $params = [
            'userProfile' => $userProfile,
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-user-html',
        ];

        $files = [];
        /** @var File $file */
        foreach ($answare->hasMultipleFiles('commentAttachments')->all() as $file) {
            $files[$file->name .'.'.$file->type] = $file->getPath();
        }
        return self::sendEmail($to, $from, $subject, $params, $layout, null,[],[],$files);
    }

    /**
     * manda la mail di chiusura del ticket all'operatore
     *
     * @param Ticket $ticket
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailChiusuraOperatore($ticket, $partnershipMsg)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket, $partnershipMsg);
        }

        $from = \Yii::$app->params['supportEmail'];

        if($ticket->isGuestTicket()) {
            $to = $ticket->guest_email;
            $userProfile['name'] = $ticket->guest_name;
            $userProfile['cognome'] = $ticket->guest_surname;
        } else {
            $creatorUserProfile = $ticket->getCreatedUserProfile()->one();
            $to = $creatorUserProfile->user->email;
            $userProfile = $creatorUserProfile->toArray();
        }

        $subject = AmosTicket::t("amosticket", 'Chiusura ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Il ticket id.<strong>' . $ticket->id . "</strong> \"$ticket->titolo\" è stato chiuso");
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $params = [
            'userProfile' => $userProfile,
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-user-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail all'operatore che la sua richiesta è stata girata a una gestione esterna
     *
     * @param Ticket $ticket
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailTecnicaOperatore($ticket)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket);
        }

        $from = \Yii::$app->params['supportEmail'];

        $creatorUserProfile = $ticket->getCreatedUserProfile()->one();
        $to = $creatorUserProfile->user->email;

        $subject = AmosTicket::t("amosticket", 'Gestione esterna ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Il ticket id.<strong>' . $ticket->id . "</strong> \"$ticket->titolo\" è stato affidato a una gestione esterna");

        $params = [
            'userProfile' => $creatorUserProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-user-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di creazione di un ticket tecnico (sia nuovo che inoltrato)
     *
     * @param Ticket $ticket
     * @param TicketCategorie $model_ticket_categoria
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailCategoriaTecnica($ticket, $model_ticket_categoria, $partnershipMsg)
    {
        $className = AmosTicket::instance()->emailUtilClass;
        if (class_exists($className) && method_exists($className, __FUNCTION__)){
            $functionName = __FUNCTION__;
            return $className::$functionName($ticket, $model_ticket_categoria, $partnershipMsg);
        }

        $from = \Yii::$app->params['supportEmail'];

        $emailReferenti = $model_ticket_categoria->email_tecnica;
        $to = $emailReferenti;

        $creatorUserProfile = $ticket->createdUserProfile;
        $nameSurname = (!is_null($creatorUserProfile) ? $creatorUserProfile->getNomeCognome() : '-');
        $creatorUser = $creatorUserProfile->user;
        $creatorEmail = $creatorUser->email;

        $subject = AmosTicket::t("amosticket", 'Nuovo ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'E\' stato aperto un nuovo ticket nel plugin assistenza');
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $model_ticket_categoria->nomeCompleto . '</strong>';
        $body[] = '<strong>' . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $nameSurname;
        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;

        $body[] = $creatorUser->getAttributeLabel('email') . ': ' . $creatorEmail;

        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        $body[] = $model_ticket_categoria->getAttributeLabel('technical_assistance_description') . ': ' . ($model_ticket_categoria->technical_assistance_description ? $model_ticket_categoria->technical_assistance_description : '-');
        if ($model_ticket_categoria->isAdministrative()) {
            $administrativeMsg = AmosTicket::t('amosticket', '#administrative_technical_mail_text');
            $emailReferenti = TicketUtility::getEmailReferentiCategoria($ticket->ticket_categoria_id, true, true);
            if (!empty($emailReferenti)) {
                $administrativeMsg .= '<ul>';
                foreach ($emailReferenti as $email) {
                    $administrativeMsg .= '<li>' . $email . '</li>';
                }
                $administrativeMsg .= '</ul>';
            }
            $body[] = $administrativeMsg;
        }
        $body[] = AmosTicket::t('amosticket', 'Questo ticket ci risulta di vostra competenza.');
        $body[] = '<strong>' . Html::a(
                AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'),
                \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])
            ) . '</strong>';

        $params = [
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open2/amos-ticket/src/mail/generic/generic-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout, null, $creatorEmail, $creatorEmail);
    }

    /**
     * @param array|string $to
     * @param string $from
     * @param string $subject
     * @param array $params
     * @param array|null|string $template
     * @param null $layoutHtml
     * @param array|string $replyTo
     * @param array|string $cc
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmail($to, $from, $subject, $params, $template, $layoutHtml = null, $replyTo = [], $cc = [], $files = [])
    {
//        $className = AmosTicket::instance()->emailUtilClass;
//        if (class_exists($className) && method_exists($className, __FUNCTION__)){
//            $functionName = __FUNCTION__;
//            return $className::$functionName($to, $from, $subject, $params, $template, $layoutHtml = null, $replyTo = [], $cc = [], $files = []);
//        }

        if ($layoutHtml) {
            \Yii::$app->mailer->htmlLayout = $layoutHtml;
        }

        $from = isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : 'assistenza@open20.it';

        if (is_string($to)) {
            $to = [$to];
        }

        $controller = \Yii::$app->controller;
        $userDefault = null;
        $message = $controller->renderPartial($template['html'], $params);

        /** @var \open20\amos\core\utilities\Email $email */
        $email = new Email();
        return $email->sendMail($from, $to, $subject, $message, $files, [], [], 0, false, $cc, $replyTo);


//        $mail = \Yii::$app->mailer->compose(
//            $template,
//            $params
//        )
//            ->setFrom($from)
//            ->setTo($to)
//            ->setSubject($subject);
//        if (!empty($replyTo)) {
//            $mail->setReplyTo($replyTo);
//        }
//        if (!empty($cc)) {
//            $mail->setCc($cc);
//        }
//
//        if ($mail instanceof \yii\swiftmailer\Message && isset($_GET['preview'])) {
//            $swiftMessage = $mail->getSwiftMessage();
//            $r = new \ReflectionObject($swiftMessage);
//            $parentClassThatHasBody = $r->getParentClass()
//                ->getParentClass()
//                ->getParentClass(); //\Swift_Mime_SimpleMimeEntity
//            $body = $parentClassThatHasBody->getProperty('immediateChildren');
//            $body->setAccessible(true);
//            $children = $body->getValue($swiftMessage);
//            foreach ($children as $child) {
//                if ($child instanceof \Swift_MimePart &&
//                    $child->getContentType() == 'text/html'
//                ) {
//                    $html = $child->getBody();
//                    break;
//                }
//            }
//
//            echo $html;
//            die;
//        }

//        return $mail->send();
    }

    protected static function splitMailFrom($from)
    {
        $fromName = \Yii::$app->name;
        $fromEmail = \Yii::$app->params['supportEmail'];

        $posInFrom = strpos($from, ' ');
        $posInSupp = strpos($fromEmail, ' ');

        if ($posInFrom > 0) {
            $elements = str_split($from, $posInFrom);
        } else if ($posInSupp > 0) {
            $elements = str_split($from, $posInSupp);
        }

        if (!empty($elements[0]) && !empty($elements[1])) {
            return $elements[0] . ' '. trim($elements[1]);
        } else {
            return $from . ' ' . $fromName;
        }
    }
}
