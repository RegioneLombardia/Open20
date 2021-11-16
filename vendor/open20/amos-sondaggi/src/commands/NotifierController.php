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
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiInvitationMm;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use Yii;
use yii\console\Controller;
use yii\db\Expression;
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
            Console::stdout('Start sending closing e-mails ' . $type . PHP_EOL);
            $sondaggi = $this->listSondaggiClosed();
            foreach ($sondaggi as $sondaggio) {
                $this->sendEmailPollClosed($sondaggio);
            }
            Console::stdout('All e-mails sent correctly.' . $type . PHP_EOL);
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
            Console::stdout('Start sending invitation e-mails ' . $type . PHP_EOL);
            $sondaggi = $this->listSondaggi();

            foreach ($sondaggi as $sondaggio) {
                $queryInv = $sondaggio->getInvitations();
                $queryInv->andWhere(['invited' => false]);

                $invitations = $queryInv->all();
                foreach ($invitations as $invitation) {
                    $organizationsQuery = SondaggiInvitationsSearch::searchOrganizations($invitation->toArray())->query;
                    $invitation->count = $organizationsQuery->count();
                    $invitation->invited = 1;
                    $invitation->save(false);
                    // $organizationsQuery->leftJoin(SondaggiInvitationMm::tableName(), [SondaggiInvitationMm::tableName() . '.sondaggi_id' => $sondaggio->id,
                    //     SondaggiInvitationMm::tableName() . '.to_id' => new Expression('profilo.id'), SondaggiInvitationMm::tableName() . '.deleted_at' => null]);
                    // $organizationsQuery->andWhere([SondaggiInvitationMm::tableName() . '.sondaggi_id' => null,
                    //     SondaggiInvitationMm::tableName() . '.to_id' => null]);
                    Console::stdout('Query:' . $organizationsQuery->createCommand()->rawSql . PHP_EOL);
                    foreach ($organizationsQuery->each() as $organization) {
                        Console::stdout('Organization ' . $organization->name . PHP_EOL);
                        $email = '';
                        $referente = $organization->referenteOperativo;
                        if (!is_null($referente)) {
                            $this->sendMail($sondaggio, $referente, $organization);
                        } else {
                            $this->sendMailOrganization($sondaggio, $organization);
                        }

                    }
                }
            }

            Console::stdout('End send invitations ' . $type . PHP_EOL);

        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
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
     * @param Sondaggi $sondaggio
     * @param UserProfile $referenteOperativo
     * @param Profilo $organization
     */
    protected function sendMail($sondaggio, $referenteOperativo, $organization)
    {
        $this->sendEmailReferenteOperativo($sondaggio, $referenteOperativo);

        Console::stdout('Send Mail to ' . $referenteOperativo->user->email . PHP_EOL);
        $inv_mm = new SondaggiInvitationMm();
        $inv_mm->sondaggi_id = $sondaggio->id;
        $inv_mm->to_id = $organization->id;
        $inv_mm->save(false);
    }

    /**
     * @param Sondaggi $sondaggio
     * @param Profilo $organization
     */
    protected function sendMailOrganization($sondaggio, $organization)
    {
        $this->sendEmailOrganization($sondaggio, $organization);

        Console::stdout('Send Mail to ' . $organization->operativeHeadquarter->email);
        $inv_mm = new SondaggiInvitationMm();
        $inv_mm->sondaggi_id = $sondaggio->id;
        $inv_mm->to_id = $organization->id;
        $inv_mm->save(false);
    }

    /**
     * @param array|string $to
     * @param $profile
     * @param $subject
     * @param $message
     * @param array $files
     * @return bool
     */
    protected function sendEmailGeneral($to, $profile, $subject, $message, $files = [])
    {
        try {
            $from = '';
            if (isset(\Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            }

            /** @var Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return true;
    }

    /**
     * @param Sondaggi $sondaggio
     * @param UserProfile $referenteOperativo
     */
    protected function sendEmailReferenteOperativo($sondaggio, $referenteOperativo)
    {
        $emailsTo = [$referenteOperativo->user->email];

        $subject = AmosSondaggi::t('amossondaggi', '#invito_referenteOperativo_subject');
        $message = AmosSondaggi::t('amossondaggi', '#invito_referenteOperativo_message', [
            'titolo' => $sondaggio->titolo,
            'urlPollCompilation' => Yii::$app->urlManager->createAbsoluteUrl([
                '/' . AmosSondaggi::getModuleName() . '/pubblicazione/compila',
                'id' => $sondaggio->id
            ]),
            'urlPlatform' => Yii::$app->urlManager->createAbsoluteUrl('/'),
            'nomeCognome' => $referenteOperativo->nomeCognome,
            'data' => Yii::$app->formatter->asDate($sondaggio->close_date),
        ]);
        $this->sendEmailGeneral($emailsTo, null, $subject, $message);
    }

    /**
     * @param Sondaggi $sondaggio
     * @param Profilo $organization
     */
    protected function sendEmailOrganization($sondaggio, $organization)
    {
        $emailsTo = [$organization->operativeHeadquarter->email];

        $subject = AmosSondaggi::t('amossondaggi', '#invito_organization_subject');
        $message = AmosSondaggi::t('amossondaggi', '#invito_organization_message', [
            'titolo' => $sondaggio->titolo,
            'urlPollCompilation' => Yii::$app->urlManager->createAbsoluteUrl([
                '/' . AmosSondaggi::getModuleName() . '/pubblicazione/compila',
                'id' => $sondaggio->id
            ]),
            'urlPlatform' => Yii::$app->urlManager->createAbsoluteUrl('/'),
            'ente' => $organization->name,
            'data' => Yii::$app->formatter->asDate($sondaggio->close_date),
        ]);

        $this->sendEmailGeneral($emailsTo, null, $subject, $message);
    }

    protected function sendEmailPollClosed($sondaggio) {
        // TO REFACTOR: se il cron gira una volta al giorno, partirà una sola mail per sondaggio.
        // Pensare ad un metodo più elegante...
        Console::stdout('Sending e-mail for '.$sondaggio->titolo . ':' . $type . PHP_EOL);

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
            'compiled' => $sondaggio->getCompilazioniStatus(SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO)
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

        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        foreach($users as $user) {
            if (!empty($user->email) && Yii::$app->authManager->checkAccess($user->id, 'AMMINISTRAZIONE_SONDAGGI')) {
                Console::stdout('   Sending e-mail to '.$user->email . $type . PHP_EOL);
                $this->sendEmailGeneral([trim($user->email)], null, $subject, $message);
            }
        }
    }
}
