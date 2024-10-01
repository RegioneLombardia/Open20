<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\commands
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\commands;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\utilities\Email;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\controllers\ConsoleController;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiInvitationMm;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\models\SondaggiUsersInvitationMm;
use open20\amos\sondaggi\utility\SondaggiUtility;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidValueException;
use yii\console\Controller;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\log\Logger;

/**
 * Class NotifierController
 * @package open20\amos\sondaggi\commands
 */
class NotifierController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->module = AmosSondaggi::instance();
        parent::init();
    }

    /**
     * @param string $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return [];
    }

    public function actionSendClosed() {
        try {
            Console::stdout('Start sending closing e-mails ' . PHP_EOL);
            $sondaggi = $this->listSondaggiClosed();
            foreach ($sondaggi as $sondaggio) {
                $this->sendEmailPollClosed($sondaggio);
            }
            Console::stdout('All e-mails sent correctly.' . PHP_EOL);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * This action sends nightly mails.
     */
    public function actionSendInvitations()
    {
        try {
            Console::stdout('Start sending invitation e-mails ' . PHP_EOL);
            $sondaggi = $this->listSondaggi();

            foreach ($sondaggi as $sondaggio) {
                $queryInv = $sondaggio->getInvitations();
                $queryInv->andWhere(['invited' => false])->andWhere(['active' => true]);
                $sent_to = [];

                $invitations = $queryInv->all();
                foreach ($invitations as $invitation) {
                    if ($invitation->target == SondaggiInvitations::TARGET_ORGANIZATIONS) {
                        $this->notifyOrganization($sondaggio, $invitation, $sent_to);
                    }
                    else if ($invitation->target == SondaggiInvitations::TARGET_USERS) {
                        $this->notifyUser($sondaggio, $invitation, $sent_to);
                    }
                }
            }

            Console::stdout('End send invitations ' . PHP_EOL);

        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param $id
     * @param $task_id
     * @return void
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionExtract($id, $task_id = null)
    {
        ConsoleController::actionExtract($id, $task_id);
    }

    /**
     * @return mixed
     */
    protected function listSondaggi()
    {
        $search = $this->module->createModel('SondaggiSearch');
        $query = $search->find();
        $query->andWhere(['sondaggi.status' => Sondaggi::WORKFLOW_STATUS_VALIDATO]);
        $query->andWhere(['<=', 'publish_date', new Expression('CURDATE()')]);
        $query->andWhere(['or', ['>=', 'close_date', new Expression('CURDATE()')], ['close_date' => null]]);

        return $query->all();
    }

    /**
     * @return mixed
     */
    protected function listSondaggiClosed()
    {
        $query = Sondaggi::find();
        $query->andWhere(['sondaggi.status' => Sondaggi::WORKFLOW_STATUS_VALIDATO]);
        $query->andWhere(['close_date' => new Expression('DATE_ADD(CURDATE(), INTERVAL -1 DAY)')]);

        return $query->all();
    }

    /**
     * Memorizza i dati del destinatario dell'invitation che e' passata al
     * metodo
     * @param Sondaggi $sondaggio Sondaggio di riferimento
     * @param SondaggiInvitations $invitation Invitation
     * @param integer $toId Id del destinatario della invitation
     * @return boolean Restituisce true in caso di successo o false altrimenti
     * @throws InvalidArgumentException Se l'attributo 'target' dell'invitation
     * non e' valorizzato
     * @throws InvalidValueException Se il valore dell'attributo 'target'
     * dell'invitation non e' fra quelli previsti
     */
    protected function createInvitationMm($sondaggio, $invitation, $toId)
    {
        if ((empty($sondaggio)) || (empty($invitation)) || (empty($toId))) {
            Console::stdout('Parametri mancanti per la memorizzazione dei dati'
                . ' dell\'invitation');
            return false;
        }
        if (is_null($invitation->target)) {
            throw new InvalidArgumentException('Target dell\'invitation non'
                . ' definito');
        }
        switch ($invitation->target) {
            case SondaggiInvitations::TARGET_USERS:
                //verifico se l'utente ha un profilo di organizzazione
                $profiloId = ArrayHelper::getValue(ProfiloUserMm::find()
                        ->andWhere(['user_id' => $toId])
                        ->andWhere(['status' => ProfiloUserMm::STATUS_ACTIVE])
                        ->one(),
                    'id', 0);
                $invMm = new SondaggiUsersInvitationMm();
                $invMm->sondaggi_id = $sondaggio->id;
                $invMm->user_id = $toId;
                $invMm->to_id = $profiloId;
                break;
            case SondaggiInvitations::TARGET_ORGANIZATIONS:
                $invMm = new SondaggiInvitationMm();
                $invMm->sondaggi_id = $sondaggio->id;
                $invMm->invitation_id = $invitation->id;
                $invMm->invitation_class = $invitation->target;
                $invMm->to_id = $toId;
                break;
            default:
                throw new InvalidValueException('Valore di target'
                    . ' dell\'invitation non riconosciunto ('
                    . $invitation->target . ')');
        }
        try {
//            $invMm->save(false);
            if (!$invMm->save()) {
                Console::stdout('Errore nella memorizzazione dei dati del'
                    . ' destinatario della invitation');
                return false;
            }
        } catch (\Exception $exc) {
            Console::stdout('Eccezione nella memorizzazione dei dati del'
                . ' destinatario della invitation: ' . $exc->getMessage());
            return false;
        }
        return true;
    }
    
    /**
     * @param Sondaggi $sondaggio
     * @param UserProfile $referenteOperativo
     * @param Profilo $organization
     * @param SondaggiInvitations $invitation
     * @return boolean Restituisce true in caso di successo o false altrimenti
     */
    protected function sendMail($sondaggio, $referenteOperativo, $organization, $invitation)
    {
        if ((empty($sondaggio)) || (empty($referenteOperativo)) || (empty($organization))
            || (empty($invitation))) {
            Console::stdout('Parametri mancanti per l\'invio dell\'email');
            return false;
        }
        Console::stdout('Send Mail to ' . $referenteOperativo->user->email . PHP_EOL);
        if (!$this->sendEmailReferenteOperativo($sondaggio, $referenteOperativo, $organization)) {
            Console::stdout('Sending failed' . PHP_EOL);
            return false;
        }
        if (!$this->createInvitationMm($sondaggio, $invitation, $organization->id)) {
            return false;
        }
        return true;
    }

    /**
     * @param Sondaggi $sondaggio
     * @param Profilo $organization
     * @param SondaggiInvitations $invitation
     * @return boolean Restituisce true in caso di successo o false altrimenti
     */
    protected function sendMailOrganization($sondaggio, $organization, $invitation)
    {
        if ((empty($sondaggio)) || (empty($organization))
            || (empty($invitation))) {
            Console::stdout('Parametri mancanti per l\'invio dell\'email'
                . ' all\'organizzazione');
            return false;
        }
        Console::stdout('Send Mail to ' . $organization->operativeHeadquarter->email . PHP_EOL);
        if (!$this->sendEmailOrganization($sondaggio, $organization)) {
            Console::stdout('Sending failed' . PHP_EOL);
            return false;
        }
        if (!$this->createInvitationMm($sondaggio, $invitation, $organization->id)) {
            return false;
        }
        return true;
    }

    /**
     * @param Sondaggi $sondaggio
     * @param User $user 
     * @param SondaggiInvitations $invitation 
     * @return boolean Restituisce true in caso di successo o false altrimenti
     */
    protected function sendMailUser($sondaggio, $user, $invitation)
    {
        if ((empty($sondaggio)) || (empty($user)) || (empty($invitation))) {
            Console::stdout('Parametri mancanti per l\'invio dell\'email'
                . ' all\'utente');
            return false;
        }
        $to = [$user->email];
        $subject = AmosSondaggi::t('amossondaggi', 'Invito sondaggio');
        $message = SondaggiUtility::getInvitationEmailContent($sondaggio,
            $user->userProfile);
        Console::stdout('Send Mail to ' . $user->email . PHP_EOL);
        if (!$this->sendEmailGeneral($to, $subject, $message, null)) {
            Console::stdout('Sending failed' . PHP_EOL);
            return false;
        }
        if (!$this->createInvitationMm($sondaggio, $invitation, $user->id)) {
            return false;
        }
        return true;
    }

    /**
     * Invia un'email a uno o piu' destinatari
     * @param array|string $to Destinatari
     * @param string $subject Oggetto dell'email
     * @param string $message Corpo dell'email
     * @param User $profile Dati dell'utente destinatario
     * @param array $files File da allegare
     * @return boolean Restituisce true in caso di successo o false altrimenti
     */
    protected function sendEmailGeneral($to, $subject, $message, $profile = null, $files = [])
    {
        if ((empty($to)) || (empty($subject)) || (empty($message))) {
            Console::stdout('Parametri mancanti per l\'invio dell\'email'
                . ' (general)');
            return false;
        }
        try {
            $from = '';
            if (isset(\Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            }

            /** @var Email $email */
            $email = new Email();
            return $email->sendMail($from, $to, $subject, $message, $files, [],
                ['profile' => $profile]);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log('Eccezione nell\'invio dell\'email'
                . ' all\'indirizzo ' . $to . ':' .  $ex->getMessage(),
                Logger::LEVEL_ERROR);
            return false;
        }
    }

    /**
     * @param Sondaggi $sondaggio
     * @param UserProfile $referenteOperativo
     * @param type $organization
     * @return boolean Restituisce true in caso di successo o false altrimenti
     */
    protected function sendEmailReferenteOperativo($sondaggio, $referenteOperativo, $organization)
    {
        if ((empty($sondaggio)) || (empty($referenteOperativo))
            || (empty($organization))) {
            Console::stdout('Parametri mancanti per l\'invio dell\'email'
                . ' al referente operativo');
            return false;
        }
        $emailsTo = [$referenteOperativo->user->email,
            $organization->operativeHeadquarter->email];
        $subject = AmosSondaggi::t('amossondaggi', '#invito_referenteOperativo_subject');
        $message = SondaggiUtility::getInvitationEmailContent($sondaggio, $referenteOperativo);

        return $this->sendEmailGeneral($emailsTo, $subject, $message,
            $referenteOperativo->user);
    }

    /**
     * @param Sondaggi $sondaggio
     * @param Profilo $organization
     * @return boolean Restituisce true in caso di successo o false altrimenti
     */
    protected function sendEmailOrganization($sondaggio, $organization)
    {
        if ((empty($sondaggio)) || (empty($organization))) {
            Console::stdout('Parametri mancanti per l\'invio dell\'email'
                . ' all\'organizzazione');
            return false;
        }
        $emailsTo = [$organization->operativeHeadquarter->email];
        $subject = AmosSondaggi::t('amossondaggi', '#invito_organization_subject');
        $message = SondaggiUtility::getInvitationOrganizationEmailContent($sondaggio, $organization);

        return $this->sendEmailGeneral($emailsTo, $subject, $message);
    }

    protected function sendEmailPollClosed($sondaggio) {
        // TO REFACTOR: se il cron gira una volta al giorno, partirà una sola mail per sondaggio.
        // Pensare ad un metodo più elegante...
        Console::stdout('Sending e-mail for ' . $sondaggio->titolo . PHP_EOL);

        $close_date = AmosSondaggi::t('amossondaggi', '#email_closed_poll_close_date', [
            'closeDate' => Yii::$app->formatter->asDate($sondaggio->close_date)
        ]);

        $imageUrl = Yii::$app->urlManager->createAbsoluteUrl([
            '/' . $sondaggio->getModelImageUrl()
        ]);

        $manageLink = Yii::$app->urlManager->createAbsoluteUrl([
            '/' . AmosSondaggi::getModuleName() . '/dashboard/dashboard',
            'id' => $sondaggio->id
        ]);

        $participations = AmosSondaggi::t('amossondaggi', '#email_closed_poll_participations', [
            'invited' => $sondaggio->getEntiInvitati()->count(),
            'compiled' => \Yii::$app->getModule('sondaggi')->forceOnlyFrontend ? $sondaggio->getNumeroPartecipazioni() : $sondaggio->getCompilazioniStatus(null, [0, 1])
        ]);

        $subject = AmosSondaggi::t('amossondaggi', '#email_closed_poll_subject', ['title' => $sondaggio->titolo]);
        $message = AmosSondaggi::t('amossondaggi', '#email_closed_poll', [
            'imageUrl' => $imageUrl,
            'title' => $sondaggio->titolo,
            'description' => $sondaggio->descrizione,
            'closeDateMessage' => $close_date,
            'manageLink' => $manageLink,
            'participations' => $participations
        ]);
        $xlsResults = SondaggiUtility::generateXlsResults($sondaggio->id);
        $files = [];
        if (!empty($xlsResults)) {
            $files = [$xlsResults];
        }

        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();

        // Send mail to users with permission AMMINISTRAZIONE_SONDAGGI
        foreach($users as $user) {
            if (!empty($user->email) && Yii::$app->authManager->checkAccess($user->id, 'AMMINISTRAZIONE_SONDAGGI')) {
                Console::stdout('Sending e-mail to ' . $user->email . PHP_EOL);
                if (!$this->sendEmailGeneral([trim($user->email)], $subject, $message, $user, $files)) {
                    Console::stdout('Sending failed' . PHP_EOL);
                }
            }
        }

        $additionalEmails = [];
        if (!empty($sondaggio->additional_emails) && $sondaggio->send_pdf_via_email_closed) {
            $additionalEmails = explode(';', $sondaggio->additional_emails);
        }

        // Send mail to additional emails
        foreach($additionalEmails as $email) {
            $user = User::find()->andWhere(['email' => $email])->one();
            Console::stdout('Sending e-mail to ' . $email . PHP_EOL);
            if (!$this->sendEmailGeneral([trim($email)], $subject, $message, $user, $files)) {
                Console::stdout('Sending failed' . PHP_EOL);
            }
        }

        // Delete xls file
        unlink($xlsResults);
    }

    /**
     * @param $sondaggio Sondaggi
     * @param $invitation SondaggiInvitations
     * @param $sent_to array
     * @return void
     */
    protected function notifyOrganization($sondaggio, $invitation, $sent_to)
    {
        Console::stdout('Sending mails to organizations' . PHP_EOL);
        $organizationsQuery = SondaggiInvitationsSearch::searchOrganizations($invitation->toArray())->query;
        $invitedOrgs = ArrayHelper::getColumn(
            SondaggiInvitationMm::find()
                ->select('to_id')
                ->andWhere(['sondaggi_id' => $sondaggio->id])
                ->asArray()
                ->all(),
            'to_id');
        $sent_to = ArrayHelper::merge($sent_to, $invitedOrgs);
        $invitation->count = $organizationsQuery->count();
        Console::stdout('Already invited: ' . implode(', ', $sent_to) . PHP_EOL);
        $invitation->invited = 1;
        $invitation->save(false);
        // $organizationsQuery->leftJoin(SondaggiInvitationMm::tableName(), [SondaggiInvitationMm::tableName() . '.sondaggi_id' => $sondaggio->id,
        //     SondaggiInvitationMm::tableName() . '.to_id' => new Expression('profilo.id'), SondaggiInvitationMm::tableName() . '.deleted_at' => null]);
        // $organizationsQuery->andWhere([SondaggiInvitationMm::tableName() . '.sondaggi_id' => null,
        //     SondaggiInvitationMm::tableName() . '.to_id' => null]);
        foreach ($organizationsQuery->each() as $organization) {
            // Checks if invite has been sent already to this organization...
            if (!in_array($organization->id, $sent_to)) {
                Console::stdout('Organization ' . $organization->name . PHP_EOL);
                $email = '';
                $referente = $organization->referenteOperativo;
                $sent_to[] = $organization->id;
                if (!is_null($referente)) {
                    $this->sendMail($sondaggio, $referente, $organization, $invitation);
                } else {
                    $this->sendMailOrganization($sondaggio, $organization, $invitation);
                }
            }
        }
    }

    /**
     * @param $sondaggio Sondaggi
     * @param $invitation SondaggiInvitations
     * @param $sent_to array
     * @return void
     */
    protected function notifyUser($sondaggio, $invitation, $sent_to)
    {
        Console::stdout('Sending mails to users' . PHP_EOL);
        $params = $invitation->toArray();
        $params['users'] = $invitation->search_users;
        $params['tagValues'] = $invitation->search_tags;
        if ($sondaggio->isCommunitySurvey()) {
            $params['community_id'] = $sondaggio->community_id;
        }
        $usersQuery = SondaggiInvitationsSearch::searchInvitedUsers($params);

        $invitedUsers = ArrayHelper::getColumn(
            SondaggiUsersInvitationMm::find()
                ->andWhere(['sondaggi_id' => $sondaggio->id])
                ->all(),
            'user_id');
        $sent_to = ArrayHelper::merge($sent_to, $invitedUsers);
        $invitation->count = $usersQuery->count();
        Console::stdout('Already invited: ' . implode(', ', $sent_to) . PHP_EOL);
        $invitation->invited = 1;
        $invitation->save(false);
        foreach ($usersQuery->each() as $user) {
            // Checks if invite has been sent already to this user...
            if (!in_array($user->id, $sent_to)) {
                Console::stdout('User ' . $user->id . ': ' . $user->userProfile->nomeCognome . PHP_EOL);
                $sent_to[] = $user->id;
                $this->sendMailUser($sondaggio, $user, $invitation);
            }
        }
    }

}
