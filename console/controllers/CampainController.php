<?php

namespace console\controllers;

use amos\newsletter\utility\MailupUtility;
use backend\modules\campains\models\base\PreferenceCampainChannelMm as BasePreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\models\PreferenceCommunicationSent;
use backend\modules\campains\utility\CampainsQueryUtility;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\utility\MailUpEmailUtility;
use backend\modules\campains\utility\TableMessageViewsUtility;
use backend\modules\campains\utility\TableSentUtility;
use console\exceptions\MailupComunicationException;
use open20\amos\mobile\bridge\utility\NotificationPushUtility;
use Exception;
use InvalidArgumentException;
use preference\userprofile\models\PreferenceChannel;
use Yii;
use yii\base\BaseObject;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Console;
use yii\helpers\VarDumper;

class CampainController extends PreferenceCronController
{

    public $mailService;
    public $dynamics_fields = [
        1 => 'name',
        2 => 'surname',
        28 => 'user_id',
    ];

    private $maxImportNumber = 15000;
    private $maxImportNumberSMS = 15000;
    private $maxErrorsOnCampain = 5;

    /**
     *
     */
    public function init()
    {
        parent::init();

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $this->mailService = new $mailServiceClassname();
        } else {
            throw new InvalidConfigException('the module newsletter must be enabled in common');
        }
    }

    public function actionScanChannels()
    {
        $query =  PreferenceCampainChannelMm::find()
            ->andWhere(['status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS])
            ->andWhere(['OR',
                ['<','mailup_errors', $this->maxErrorsOnCampain],
                ['mailup_errors' => null]
            ]);

        $q1 = clone $query;
        // se è in stato SENDINGINPROGRESS ma mailup non è in sending allora lo tatto come caricamento e comando l'invio
        // se mailup è in stato sending lo tratto separatemente, in questa maniera può essere trattata un'altra campagna per il caricamento
        $q1->andWhere(['<>', 'mailup_status', PreferenceCampainChannelMm::STATUS_SENDING]);
        // Andiamo a trattare una campagna alla volta!
        $channel = $q1->one();

        /** @var PreferenceCampainChannelMm $channel */
        if (!empty($channel)) {
            // resetto il messaggio di errore in caso di corretto salvataggio dei dati sparisce il messaggio...
            $channel->mailup_error_message = null;

            if (!empty($channel) && ($channel instanceof PreferenceCampainChannelMm)) {
                try {
                    // SE il canale è NEWSLETTER/EMAIL
                    if ($channel->preference_channel_id == PreferenceChannel::NEWSLETTER_ID) {

                        // fase di mailup da importare e importazione in progress
                        if (in_array($channel->mailup_status, [PreferenceCampainChannelMm::STATUS_USERS_TO_IMPORT, PreferenceCampainChannelMm::STATUS_IMPORTING_USERS])) {
                            $this->addMailupListIdToChannel($channel);
                            $this->createMailupGroup($channel);
                            $this->getUserAndSave($channel, 'newsletter');
                            $this->getUserAndImport($channel, 'newsletter');
                        }

                        // ---- SEND COMUNICATION
                        if ($channel->mailup_status == PreferenceCampainChannelMm::STATUS_IMPORTED) {
                            $this->sendCommunication($channel);
                        }

                    }

                    // SE il canale è SMS
                    if ($channel->preference_channel_id == PreferenceChannel::SMS_ID) {

                        if (in_array($channel->mailup_status, [PreferenceCampainChannelMm::STATUS_USERS_TO_IMPORT, PreferenceCampainChannelMm::STATUS_IMPORTING_USERS])) {
                            $this->addMailupListIdToChannel($channel);
                            $this->createMailupGroup($channel);
                            $this->getUserAndSave($channel, 'sms');
                            $this->getUserAndImport($channel, 'sms');
                        }

                        // ---- SEND COMUNICATION
                        if ($channel->mailup_status == PreferenceCampainChannelMm::STATUS_IMPORTED) {
                            $this->sendCommunicationSMS($channel);
                        }

                    }

                    // SE il canale è APP
                    if ($channel->preference_channel_id == PreferenceChannel::APP_ID) {
                        $this->sendAppComunications($channel);
                    }

                } catch (MailupComunicationException $me) {
                    if (!is_null($me->getObjError()) && isset($me->getObjError()->ErrorCode)) {
                        $channel->mailup_error_message = 'Error code: ' . $me->getObjError()->ErrorCode . ': ' . $me->getObjError()->ErrorName;
                        $this->trace('scan-channel', 'ERROR MAILUP RESPONSE [response]: ' . VarDumper::dumpAsString($channel->toArray()));
                    } else {
                        $channel->mailup_error_message = 'Errore generico di comunicazione con mailup...';
                    }
                    $this->addErrorToChannel($channel);
                    // salvo il messaggio di errore!
                    $channel->save(false);

                    $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [file]: ' . $me->getLine() . ' - ' . $me->getFile());
                    $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [message]: ' . $me->getMessage());
                    $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [trace]: ' . VarDumper::dumpAsString($me->getTraceAsString(), 3, false));
                    $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [channel]: ' . VarDumper::dumpAsString($channel->toArray()));
                } catch (Exception $e) {
                    $channel->mailup_error_message = 'ERROR ' . $e->getCode() . ' [message]: ' . $e->getMessage();
                    $this->addErrorToChannel($channel);
                    // salvo il messaggio di errore!
                    $channel->save(false);

                    $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [file]: ' . $e->getLine() . ' - ' . $e->getFile());
                    $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [message]: ' . $e->getMessage());
                    $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [trace]: ' . VarDumper::dumpAsString($e->getTraceAsString(), 3, false));
                    $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [channel]: ' . VarDumper::dumpAsString($channel->toArray()));
                }
            }
        }

        // ------------------------------------------------------------------------------

        // il controllo sulle campagne che sono in stato di invio lo valuto separatamente
        // in questa maniera metre finisce una campagna un'altra può essere preparata e caricata
        $q2 = clone $query;
        $q2->andWhere(['mailup_status' => PreferenceCampainChannelMm::STATUS_SENDING]);
        $channels = $q2->all();
        if (!empty($channels)){
            $pendingSendingsIds = $this->pendingSendingsIds();
//            $this->trace('scan-channel', 'Pending sendings ' . VarDumper::dumpAsString($pendingSendingsIds,3,false));
            /** @var PreferenceCampainChannelMm $channel */
            foreach($channels as $channel) {

                // resetto il messaggio di errore in caso di corretto salvataggio dei dati sparisce il messaggio...
                $channel->mailup_error_message = null;

                if (!empty($channel) && ($channel instanceof PreferenceCampainChannelMm)) {
                    try {

                        if (
                                // SE il canale è NEWSLETTER/EMAIL
                                ($channel->preference_channel_id == PreferenceChannel::NEWSLETTER_ID)
                                // SE mailup in stato sending
                                && ($channel->mailup_status == PreferenceCampainChannelMm::STATUS_SENDING)
                        ) {
                            // se l'invio non fa parte dei pending ID...
                            if (!in_array($channel->mailup_sending_id, $pendingSendingsIds)) {
                                // allora intraprendi tutte le azione di invio completato!
                                $this->sendingCompleted($channel);
                            } else {
                                // altrimenti log a console che la campagna è in stato di invio... e attendiamo...
                                $this->trace('scan-channel', 'La campagna non può essere ancora chiusa, invio in corso - ID: ' . $channel->mailup_sending_id);
                            }
                        }

                        if (
                            // SE il canale è NEWSLETTER/EMAIL
                            ($channel->preference_channel_id == PreferenceChannel::SMS_ID)
                            // SE mailup in stato sending
                            && ($channel->mailup_status == PreferenceCampainChannelMm::STATUS_SENDING)
                        ) {
                            // se l'invio non fa parte dei pending ID...
                            if (!in_array($channel->mailup_sending_id, $pendingSendingsIds)) {
                                // allora intraprendi tutte le azione di invio completato!
                                $this->sendingCompletedSMS($channel);
                            } else {
                                // altrimenti log a console che la campagna è in stato di invio... e attendiamo...
                                $this->trace('scan-channel', 'La campagna non può essere ancora chiusa, invio in corso - ID: ' . $channel->mailup_sending_id);
                            }
                        }

                    } catch (MailupComunicationException $me) {
                        if (!is_null($me->getObjError()) && isset($me->getObjError()->ErrorCode)) {
                            $channel->mailup_error_message = 'Error code: ' . $me->getObjError()->ErrorCode . ': ' . $me->getObjError()->ErrorName;
                            $this->trace('scan-channel', 'ERROR MAILUP RESPONSE [response]: ' . VarDumper::dumpAsString($channel->toArray()));
                        } else {
                            $channel->mailup_error_message = 'Errore generico di comunicazione con mailup...';
                        }
                        $this->addErrorToChannel($channel);
                        // salvo il messaggio di errore!
                        $channel->save(false);

                        $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [file]: ' . $me->getLine() . ' - ' . $me->getFile());
                        $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [message]: ' . $me->getMessage());
                        $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [trace]: ' . VarDumper::dumpAsString($me->getTraceAsString(), 3, false));
                        $this->trace('scan-channel', 'ERROR ' . $me->getCode() . ' [channel]: ' . VarDumper::dumpAsString($channel->toArray()));
                    } catch (Exception $e) {
                        $channel->mailup_error_message = 'ERROR ' . $e->getCode() . ' [message]: ' . $e->getMessage();
                        $this->addErrorToChannel($channel);
                        // salvo il messaggio di errore!
                        $channel->save(false);

                        $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [file]: ' . $e->getLine() . ' - ' . $e->getFile());
                        $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [message]: ' . $e->getMessage());
                        $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [trace]: ' . VarDumper::dumpAsString($e->getTraceAsString(), 3, false));
                        $this->trace('scan-channel', 'ERROR ' . $e->getCode() . ' [channel]: ' . VarDumper::dumpAsString($channel->toArray()));
                    }
                }
            }
        }

    }

    /**
     *
     * @return array
     * @throws MailupComunicationException
     */
    private function pendingSendingsIds()
    {
        $toret = [];

        // controllo tra gli invii che stanno uscendo...
        // 1) li raccolgo e vediamo se tra gli invii in waiting oppure che sono in invio abbiamo il nostro invio...
        $reportSendingOngoing = $this->mailService->getOngoingSending();
        $this->checkMailupResponse($reportSendingOngoing, 'Impossibile recuperare le informazioni sugli invii attivi...');

        if (!empty($reportSendingOngoing)) {
            foreach ($reportSendingOngoing->Items as $item) {
                if (!empty($item->Id)) {
                    $toret[] = $item->Id;
                }
            }
        }

        // controllo tra gli invii che stanno aspettando di uscire...
        $reportSendingWaiting = $this->mailService->getWaitingSending();
        $this->checkMailupResponse($reportSendingWaiting, 'Impossibile recuperare le informazioni sugli invii in attesa...');

        if (!empty($reportSendingWaiting)) {
            foreach ($reportSendingWaiting->Items as $item) {
                if (!empty($item->Id)) {
                    $toret[] = $item->Id;
                }
            }
        }

        return $toret;
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     */
    private function addErrorToChannel($channel)
    {
        // incremento il numero di errori sulla campagna
        $number = intval( is_null($channel->mailup_errors)? 0: (int) $channel->mailup_errors);
        $channel->mailup_errors = ($number + 1);

        if ($channel->mailup_errors >= $this->maxErrorsOnCampain) {
            $channel->mailup_status = PreferenceCampainChannelMm::STATUS_ERROR;
        }

        $this->trace('scan-channel', 'errore aggiunto VAL ' . $channel->mailup_errors);

        $channel->save(false);
    }

    /**
     * Undocumented function
     *
     * @param PreferenceCampainChannelMm $channel
     * @return void
     */
    private function sendAppComunications($channel)
    {

        $this->trace('scan-channel', "Tatto il canale APP: " . $channel->id);
        // sono da implementare due passaggi in ordine,
        // 1 passaggio di stato del canale a SENT
        // 2 inviare notifica a tutti i destinatari della comunicazione app

        $query = CampainsQueryUtility::getQueryRecipientsByChannelModel($channel);
        $query2 = $query
            ->select(new Expression("user.*, user_profile.*
                                , attr.email as campain_email
                                , istat_comuni.nome as comune
                                , istat_province.nome as provincia
                                , attr.user_id as user_id
                                , attr.target_code as target_code
                                "))
            ->leftJoin('istat_comuni', 'istat_comuni.id = user_profile.comune_residenza_id')
            ->leftJoin('istat_province', 'istat_province.id = user_profile.provincia_residenza_id');
        $result = $query2->asArray()->all();
        $nUsers = count($result);

        $channel->mailup_status = BasePreferenceCampainChannelMm::STATUS_NO_USER_TO_IMPORT;
        $channel->mailup_n_sent = $nUsers;
        $channel->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT);
        $channel->start_date = date('Y-m-d H:i:s');
        $channel->save(false);
        if ($nUsers > 0) {
            $this->trace('scan-channel', "Inizio invio di $nUsers notifiche");
            $numOfContent = $this->getNumberOfContentByCampainChannel($channel);
            $notificationFault = 0;
            $notificationFaultUsers = [];
            foreach ($result as $row) {
                $userId = $row['user_id'];
                if ($numOfContent == 1) {
                    $message = 'E\' presente un nuovo contenuto. Clicca per visualizzarlo';
                } else {
                    $message = 'Sono presenti '.$numOfContent.' nuovi contenuti. Clicca per visualizzarli';
                }
                $ret = NotificationPushUtility::sendNotification($userId, $channel->title, $message, $channel->id);
                if (!$ret) {
                    $notificationFault++;
                    $notificationFaultUsers[] = $userId;
                    $this->trace('scan-channel', "Non è stato possibile inviare la notifica all'utente $userId ");
                }
            }
            $this->trace('scan-channel', "Fine invio notifiche. Non sono state notificate a $notificationFault utenti");
            if (count($notificationFault) > 0) {
                $this->trace('scan-channel', "Non sono state notificate a $notificationFault utenti");
                $data = json_encode($notificationFaultUsers);
                Yii::error("DATI: {$data}");
            }
        }
        $channel->end_date = date('Y-m-d H:i:s');
        $channel->save(false);
    }

    private function getNumberOfContentByCampainChannel($campainChannel)
    {
        $count = 0;
        foreach ($campainChannel->preferenceCampainSections as $section) {
            $count = $count + intval(PreferenceCampainContainer::find()->andWhere(['preference_campain_section_id' => $section->id])->count());
        }
        return $count;
    }

    /**
     * Undocumented function
     *
     * @param PreferenceCampainChannelMm $channel
     * @return void
     */
    private function createMailupGroup($channel)
    {
        if (empty($channel->mailup_group_id)) {

            $parsedTitle = str_replace('\'', '', $channel->preferenceCampain->titolo);
            $data = [
                'Name' => 'CH' . $channel->id . '_' . rand(1, 9999) . '_' . substr($parsedTitle, 0, 20),
                'Notes' => 'Channel: ' . $channel->id . ' type: ' . $channel->preferenceChannel->title,
            ];
            $groupDecoded = $this->mailService->createGroup($channel->mailup_list_id, $data);
            $this->checkMailupResponse($groupDecoded, 'Impossibile creare il gruppo...');

            $this->trace('scan-channel', 'GRUPPO CREATO: ' . $groupDecoded->idGroup . ' Creato per [PreferenceCampainChannelMm] id: ' . $channel->id);
            $channel->mailup_group_id = $groupDecoded->idGroup;
            $channel->start_date = date('Y-m-d H:i:s');
            $channel->save(false);
        }
    }

    private function addMailupListIdToChannel($channel)
    {
        if (empty($channel->mailup_list_id)) {
            if (isset(Yii::$app->params['mailup']['newsletter']['list-id'])) {
                $channel->mailup_list_id = Yii::$app->params['mailup']['newsletter']['list-id'];
                $channel->save(false);
            } else {
                throw new InvalidArgumentException('Define param for [mailup][newsletter][list-id]');
            }
        }
    }

    /**
     * @param $channelId
     * @param null $mailupImportId
     * @param null $limit
     * @return Query|null
     */
    private function queryUsersToSend($channelId, $mailupImportId = null, $limit = null)
    {
        $communicationUserTable = TableSentUtility::getTableName($channelId);
        if (Yii::$app->dbstats->schema->getTableSchema(TableSentUtility::getTableName($channelId), true) === null) {
            return null;
        }
        $query  = new Query();
        $query->select('*')
            ->from($communicationUserTable . ' user')
            ->andWhere(['mailup_import_id' => $mailupImportId])
            ->limit($limit);
        ;
        return $query;
    }

    /**
     * @param $channelId
     * @param null $mailupImportId
     * @param null $limit
     * @return Query|null
     */
    private function countAllUsersToSend($channelId)
    {
        $communicationUserTable = TableSentUtility::getTableName($channelId);
        if (Yii::$app->dbstats->schema->getTableSchema(TableSentUtility::getTableName($channelId), true) === null) {
            return null;
        }
        $query  = new Query();
        $query->select('*')
            ->from($communicationUserTable . ' user')
        ;
        return $query->count();
    }

    public function fakeSending() {
        $toret = [];
        for ($i = 0; $i < 50; $i++)
        {
            $toret[] =  [
                'nome' => 'Michele ' . $i,
                'cognome' => 'Zucchini',
                'sesso' => 'Maschio',
                'nascita_data' => '1976-02-23',
                'comune' => 'Milano',
                'comune_residenza_id' => 15146,
                'provincia' => 'Milano',
                'provincia_residenza_id' => 15,
                'user_id' => '31',
                'campain_email' => 'michele.zucchini+FAKE'.$i.'@open20.it',
            ];
        }
        return $toret;
    }


    public function fakeSMSSending() {
        $toret = [];
        $numbers = [
            '3496116710',
            '3471502137',
            '3336848581',
            '3392965438',
            '3476437159',
        ];
        for ($i = 0; $i < count($numbers); $i++)
        {
            $toret[] =  [
                'nome' => 'Michele ' . $i,
                'cognome' => 'Zucchini',
                'sesso' => 'Maschio',
                'nascita_data' => '1976-02-23',
                'comune' => 'Milano',
                'comune_residenza_id' => 15146,
                'provincia' => 'Milano',
                'provincia_residenza_id' => 15,
                'user_id' => '31',
                'campain_phone' => $numbers[$i],
                'campain_email' => 'michele.zucchini+FAKE'.$i.'@open20.it',
            ];
        }
        return $toret;
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     * @throws \Exception
     */
    public function getUserAndSave($channel, $type)
    {
        // se esiste il grupp e se la tabella di storicizzazione non è ancora stata creata.
        // deve essere creata una sola volta, poi interrogata per gli invii
        if ($channel->mailup_group_id && (empty(Yii::$app->dbstats->schema->getTableSchema(TableSentUtility::getTableName($channel->id), true))) ) {
            //--- GET USERS TO IMPORT TO THE MAILUP GROUP ---
            /** @var  $query ActiveQuery */
            $query = CampainsQueryUtility::getQueryRecipientsByChannelModel($channel);
            $query2 = $query
                ->select(new Expression("user.*, user_profile.*
                    , attr.email as campain_email
                    , attr.phone as campain_phone
                    , istat_comuni.nome as comune
                    , istat_province.nome as provincia
                    , attr.user_id as user_id
                    , attr.target_code as target_code
                    "))
                ->leftJoin('istat_comuni', 'istat_comuni.id = user_profile.comune_residenza_id')
                ->leftJoin('istat_province', 'istat_province.id = user_profile.provincia_residenza_id');
            $result = $query2->asArray()->all();
//            $result = $this->fakeSending();
//            $result = $this->fakeSMSSending();

            $nUsers = count($result);
            if ($nUsers > 0) {
                $this->trace('scan-channel', "Salvataggio dei $nUsers destinatari in preference_x_communication_sent di TIPO: " . $type);

                // se la tabella di appoggio per caricare i dati sui partecipanti del gruppo esiste, la svuoto!
                // la fase successiva aggiungerà i partecipanti.
                // (Per sicurezza e non avere doppioni, non dovrebbe verificarsi questa situazione!)
                if (Yii::$app->dbstats->schema->getTableSchema(TableSentUtility::getTableName($channel->id), true) !== null) {
                    $command = Yii::$app->dbstats->createCommand("TRUNCATE " . TableSentUtility::getTableName($channel->id) . "; ");
                    $command->execute();
                }

                foreach ($result as $row) {
                    //--- SAVE THE USERS WHICH YOU ARE SENDING --------
                    $model = TableSentUtility::buildModel($channel->id, 'dbstats');
                    $model->gender = $row['sesso'];
                    $model->birth_date = $row['nascita_data'];
                    $model->email = ($type == 'newsletter')? $row['campain_email']: null;
                    $model->phone = ($type == 'sms')?  $row['campain_phone']: null;;
                    $model->municipality = $row['comune'];
                    $model->province = $row['provincia'];
                    $model->user_id = $row['user_id'];
                    $model->preference_campain_channel_mm_id = $channel->id;
                    $model->user_profile_age_group_id = CampainsUtility::getAgeGroupByBirthDate($row['nascita_data']);
                    $model->municipality_residence_id = $row['comune_residenza_id'];
                    $model->province_residence_id = $row['provincia_residenza_id'];
                    $model->created_at = date('Y-m-d H:i:s');
                    $model->communication_id = $channel->id;
                    $model->mailup_group_id = $channel->mailup_group_id;
                    $model->mailup_import_id = null;
                    $model->name = $row['nome'];
                    $model->surname = $row['cognome'];

                    $model->save();
                }

            } else {
                // GESTIONE di una campagna su 0 utenti!!!
                $this->trace('scan-channel', "NON CI SONO DESTINATARI DA IMPORTARE");
                $channel->mailup_status = BasePreferenceCampainChannelMm::STATUS_NO_USER_TO_IMPORT;
                $channel->mailup_n_sent = 0;
                $channel->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT);
                $channel->end_date = date('Y-m-d H:i:s');
                $channel->save(false);
            }
        }

    }

    /**
     * @param PreferenceCampainChannelMm $channel
     * @throws \Exception
     */
    public function getUserAndImport($channel, $type)
    {
        // SE esiste un gruppo! e sono uin uno stato di mailup che prevede il caricamento di utenti
        if (!empty($channel->mailup_group_id)
            && (in_array($channel->mailup_status, [
                PreferenceCampainChannelMm::STATUS_IMPORTING_USERS,
                PreferenceCampainChannelMm::STATUS_USERS_TO_IMPORT
            ]))
        ) {
            // Se esiste un'importazione che mailup sta trattando! mailup_import_id deve contenere l'importazione attuale
            // ogni utente verrà marcato con l'id dell'importazione con cui è stato trasferito a mailup
            if ( !empty($channel->mailup_import_id) ) {
                $importDecoded = $this->mailService->getImport($channel->mailup_import_id);
//                $importsDecoded = $this->mailService->getImports($channel->mailup_import_id);
//                $this->trace('scan-channel', 'TRACE: ' . VarDumper::dumpAsString($importDecoded));
                $this->checkMailupResponse($importDecoded, 'Import error');

                if ($importDecoded->Completed == true) {
                    $this->trace('scan-channel', 'Gli utenti sono stati caricati sul mailup_group_id: ' . $channel->mailup_group_id . ' con importazione di ID: ' . $channel->mailup_import_id);
                    // butto via questo invio sulla tabella, è terminato...
                    // al prossimo giro del CRON dovrà essere caricata un'altra tranche di utenti
                    $channel->mailup_import_id = null;
                    $channel->save(false);

                    $this->newUsersImport($channel, $type);
                } else {
                    $this->trace('scan-channel', 'Importazione di ID: ' . $channel->mailup_import_id . ' non ancora terminata... il controllo è rimandato al prossimo giro di CRON.');
                }

            } else {
                $this->newUsersImport($channel, $type);
            }
        }
    }

    /**
     * @param $channel
     * @throws MailupComunicationException
     */
    private function newUsersImport($channel, $type)
    {
        $maxImportNumber = 0;
        switch ($type) {
            case 'newsletter':
                $maxImportNumber = $this->maxImportNumber;
                break;
            case 'sms':
                $maxImportNumber = $this->maxImportNumberSMS;
                break;
        }

        //--- GET USERS TO IMPORT TO THE MAILUP GROUP ---
        /** @var $query Query */
        $query = $this->queryUsersToSend($channel->id, null, $maxImportNumber);
        if (is_null($query)) {
            return;
        }

        $result = $query->all();
        $nUsers = count($result);

        if ($nUsers > 0) {
            $this->trace('scan-channel', "Inizio importazione di $nUsers utenti nel gruppo di mailup - tipo " . $type);
            $users = [];
            $usersIds = [];
            foreach ($result as $row) {
                $emailToSend = $row['email'];
                $phoneToSend = $row['phone'];
                $fields = [];
                foreach ($this->dynamics_fields as $id => $field) {
                    if (isset($row[$field])) {
                        $fields[] = ['Id' => $id, 'Value' => $row[$field]];
                    }
                }

                // preparazione del dato da spedire a mailup
                switch ($type) {
                    case 'newsletter':
                        $users[] = [
                            'Email' => $emailToSend,
                            'Fields' => $fields,
                            'Name' => '',
                        ];
                        break;

                    case 'sms':
                        $users[] = [
                            'MobileNumber' => $phoneToSend,
                            "MobilePrefix" => "0039",
                            'Fields' => $fields,
                            'Name' => '',
                        ];
                        break;
                }
                $usersIds[] =  $row['id'];
            }

//            $this->trace('scan-channel', VarDumper::dumpAsString($users,4,false)); die;

            // --- IMPORT USERS TO THE GROUP AND UPDATE DATAS
            $this->trace('scan-channel', "IMPORTAZIONE destinatari sul gruppo per la tipologia: " . $type);
            $importId = $this->mailService->importRecipientsToGroups($users, $channel->mailup_group_id, [], ($type == 'sms') );
            $this->checkMailupResponse($importId, 'Impossibile caricare i destinatari nel gruppo');
            $channel->mailup_import_id = $importId;
            $channel->mailup_status = BasePreferenceCampainChannelMm::STATUS_IMPORTING_USERS;
            $channel->save(false);

            // Bisogna marcare tutti gli user con l'id dell'importazione
            $communicationUserTable = TableSentUtility::getTableName($channel->id);
            /** @var $updateQuery Query */
            $updateQuery  = new Query();
            $updateQuery->createCommand()
                ->update($communicationUserTable, ['mailup_import_id' => $importId], ['id' => $usersIds])
                ->execute();

        } else {
            // ok! non rimangono più utenti da caricare... Stato di mailup da impostare com importati
            $channel->mailup_status = PreferenceCampainChannelMm::STATUS_IMPORTED;
            $channel->mailup_n_sent = $this->countAllUsersToSend($channel->id);
            $channel->mailup_import_id = null;
//            $channel->mailup_status = BasePreferenceCampainChannelMm::STATUS_IMPORTING_USERS;
            $channel->save(false);
        }
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     * @throws \Exception
     */
//    public function getUserAndImportSMS($channel)
//    {
//        if ($channel->mailup_group_id) {
//            //--- GET USERS TO IMPORT TO THE MAILUP GROUP ---
//            /** @var  $query ActiveQuery */
//            $query = CampainsQueryUtility::getQueryRecipientsByChannelModel($channel);
//            $query2 = $query
//                ->select(new Expression("user.*, user_profile.*, attr.phone as campain_phone, istat_comuni.nome as 'comune', istat_province.nome as 'provincia' "))
//                ->leftJoin('istat_comuni', 'istat_comuni.id = user_profile.nascita_comuni_id')
//                ->leftJoin('istat_province', 'istat_province.id = user_profile.nascita_province_id');
//            $result = $query2->asArray()->all();
//            $nUsers = count($result);
//
//            $users = [];
//            $i = 0;
//            if ($nUsers > 0) {
//                $this->trace('scan-channel', "Salvataggio dei $nUsers destinatari in preference_x_communication_sent");
//                foreach ($result as $row) {
//                    $phoneToSend = $row['campain_phone'];
//                    $fields = [];
//                    foreach ($this->dynamics_fields as $id => $field) {
//                        if (isset($row[$field])) {
//                            $fields[] = ['Id' => $id, 'Value' => $row[$field]];
//                        }
//                    }
//                    $users[] = [
//                        'MobileNumber' => $phoneToSend,
//                        "MobilePrefix" => "0039",
//                        'Fields' => $fields,
//                        'Name' => '',
//                    ];
//
//                    //--- SAVE THE INVITATION WHICH YOU ARE SENDING --------
//                    $i++;
//                    $communicationSent = new PreferenceCommunicationSent();
//                    $communicationSent->preference_campain_channel_mm_id = $channel->id;
//                    $communicationSent->user_id = $row['user_id'];
//                    $communicationSent->phone = $phoneToSend;
//                    $communicationSent->sent_at = date('Y-m-d H:i:s');
//                    $communicationSent->save();
//                    // if ($communicationSent) {
//                    //     \open20\amos\core\models\UserActivityLog::registerLog('Invio comunicazione', $communicationSent, 'communication_sent', null, $communicationSent->user_id);
//                    // }
//                }
//
//                // --- IMPORT USERS TO THE GROUP AND UPDATE DATAS
//                $this->trace('scan-channel', "IMPORTAZIONE destinatari SMS sul gruppo " . $channel->mailup_group_id);
//                $importId = $this->mailService->importRecipientsToGroups($users, $channel->mailup_group_id, [], true);
//                $this->checkMailupResponse($importId, 'Impossibile caricare i destinatari nel gruppo...');
//
//                $channel->mailup_import_id = $importId;
//                $channel->mailup_status = BasePreferenceCampainChannelMm::STATUS_IMPORTING_USERS;
//                $channel->save(false);
//            } else {
//                $this->trace('scan-channel', "NON CI SONO DESTINATARI DA IMPORTARE");
//                $channel->mailup_status = BasePreferenceCampainChannelMm::STATUS_NO_USER_TO_IMPORT;
//                $channel->mailup_n_sent = 0;
//                $channel->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT);
//                $channel->end_date = date('Y-m-d H:i:s');
//                $channel->save(false);
//            }
//        }
//    }

    /**
     * @param PreferenceCampainChannelMm $channel
     */
    public function sendCommunication($channel)
    {
        if (empty($channel->mailup_message_id)) {
            $newContent = MailUpEmailUtility::generateNewsletterContent($channel);

            if (empty($channel->template_id)) {
                throw new Exception('Template email inesistente!!! impossibile spedire la comunicazione');
            }

            $emailTemplate = $this->mailService->getEmail($channel->mailup_list_id, $channel->template_id);
            $this->checkMailupResponse($emailTemplate, 'Impossibile recuperare il template...');

            $htmlWithParams = MailUpEmailUtility::getDynamicContentMailup($emailTemplate, $newContent, $channel);

            // COPY THE MAILUP MESSAGE
            $this->trace('scan-channel', 'Copia del template come messaggio da inviare... ');
            $decodedCopiedMessage = $this->mailService->copyMessage($channel->mailup_list_id, $channel->template_id, [
                'Subject' => $channel->subject,
                'Notes' => "CH_ID: " . $channel->id . ' C: ' . $channel->preference_campain_id,
            ]);
            $this->checkMailupResponse($decodedCopiedMessage, 'Impossibile recuperare il messaggio copiato...');

            // UPDATE THE MESSAGE COPIED WITH THE MODIFIED TEMPLATE
            $idList = $decodedCopiedMessage->idList;
            $idMessage = $decodedCopiedMessage->idMessage;

            $decoded = $this->mailService->getEmail($idList, $idMessage);
            $this->checkMailupResponse($decoded, 'Impossibile recuperare la mail...');

            $body = [
                'Subject' => $decoded->Subject,
                'idList' => $decoded->idList,
                'Content' => $htmlWithParams,
                'Tags' => null,
                'TrackingInfo' => $decoded->TrackingInfo,
                'Embed' => $decoded->Embed,
                'IsConfirmation' => $decoded->IsConfirmation,
                'UseDynamicField' => $decoded->UseDynamicField,
                'Structure' => $decoded->Structure,
            ];

            $this->trace('scan-channel', 'Messaggio copiato, Mailup Message ID: ' . $idMessage);
            $decodedReponse = $this->mailService->updateEmail($idList, $idMessage, $body);
            $this->checkMailupResponse($decodedReponse, 'Impossibile aggiornare la mail...');

            $this->mailService->enableDisableDynamicFieldsEmail($idList, $idMessage, 'true');

            // Salvo le informazioni, il messaggio copiato e aggiornato con i nuovi contenuti
            // al prossimo giro se ho un messaggio copiato e aggiornato non debbo rifare l'operazione
            $channel->mailup_message_id = $idMessage;
            $channel->mailup_list_id = $idList;
            $channel->save(false);
        } else {
            $idMessage = $channel->mailup_message_id;
        }

        // SEND MESSAGE TO THE GROUP CRATED FOR THE INVITATION
        $this->trace('scan-channel', "Invio del messaggio (MailUpID: $channel->template_id) al gruppo (G: $channel->mailup_group_id)");
        $sent = $this->mailService->sendEmailToGroup($channel->mailup_group_id, $idMessage);
        $this->checkMailupResponse($sent, 'Impossibile inviare la mail al gruppo...');

        $channel->mailup_sending_id = $sent->Id;
        $channel->mailup_status = PreferenceCampainChannelMm::STATUS_SENDING;
        $channel->save(false);
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     */
    public function sendCommunicationSMS($channel)
    {
        $idMessage = null;
        if (empty($channel->mailup_message_id)) {
            $data = [
                'Subject' => "Message for group: " . $channel->mailup_group_id,
                'idList' => $channel->mailup_list_id,
                'Content' => empty($channel->sms_text) ? '' : $channel->sms_text,
                'Fields' => [],
                'Notes' => "Message for group: " . $channel->mailup_group_id,
                'IsUnicode' => "false",
                "Sender" => "",
            ];

            $this->trace('scan-channel', 'Create SMS data: ' . VarDumper::dumpAsString($data, 3, false));
            $ret = $this->mailService->createSMS($channel->mailup_list_id, $data);
            $this->checkMailupResponse($ret, 'Impossibile creare l\'SMS...');
//            $this->trace('scan-channel', 'Create SMS response: ' . VarDumper::dumpAsString($ret, 3, false));
            $idMessage = $ret->idMessage;
            $channel->mailup_message_id = $idMessage;
            $channel->mailup_list_id = $channel->mailup_list_id;
            $channel->save(false);
            $this->trace('scan-channel', 'Messaggio copiato, Mailup Message ID: ' . $idMessage);
        } else {
            $idMessage = $channel->mailup_message_id;
        }

        // SEND MESSAGE TO THE GROUP CRATED FOR THE INVITATION
        $this->trace('scan-channel', "Invio del SMS (MailUpID: $idMessage) al gruppo (G: $channel->mailup_group_id)");
        $sent = $this->mailService->sendSMSToGroup($channel->mailup_group_id, $idMessage);
        $this->checkMailupResponse($sent, 'Impossibile inviare l\'SMS al gruppo...');

        $this->trace('scan-channel', 'Valore ritornato al comando di invio: ' . VarDumper::dumpAsString($sent, 3, false));

        $channel->mailup_sending_id = $sent->idMessage;
        $channel->mailup_status = PreferenceCampainChannelMm::STATUS_SENDING;
        $channel->save(false);
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     */
    public function sendingCompleted($channel)
    {
        $this->trace('scan-channel', "INVIO COMPLETO");
        // recupero due informazioni... quanti mail ha inviato mailup e quante sono state rimbalzate
        // TODO verifica se è più veloce reperire le info con getEmailSendHistory e se incaso di bounches fornisce il dato con questa funzione (nei pochi casi testati non risultano bounces)
        $numberOfcomunicationSent = count(MailupUtility::getAllDataFromFunction('getEmailRecipients', [$channel->mailup_message_id]));
        $numberOfcomunicationBounces = count(MailupUtility::getAllDataFromFunction('getStatisticMessageListBounces', [$channel->mailup_message_id]));

        $channel->mailup_status = PreferenceCampainChannelMm::STATUS_SENT;
        $channel->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT);
        $channel->end_date = date('Y-m-d H:i:s');
        $channel->mailup_stats_number_comunication_sent = $numberOfcomunicationSent;
        $channel->mailup_stats_number_comunication_bounces = $numberOfcomunicationBounces;
        $channel->save(false);
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     */
    public function sendingCompletedSMS($channel)
    {
        $report = $this->mailService->reportSMS($channel->mailup_message_id);
        $this->checkMailupResponse($report, 'inmpossibile recuperare il report SMS...');

//        $this->trace('scan-channel', 'Controllo sull\'invio del messaggio ' . $channel->mailup_message_id . ': ' . VarDumper::dumpAsString($report, 3, false));
        if (((isset($report->IsCompleted)) && $report->IsCompleted) || (isset($report->EndDate) && !empty($report->EndDate))) {
            $this->trace('scan-channel', 'INVIO COMPLETO SMS CH: ' . $channel->id);
            $channel->mailup_n_sent = $report->TotalSmsSent;
            $channel->mailup_status = PreferenceCampainChannelMm::STATUS_SENT;
            $channel->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT);
            $channel->end_date = date('Y-m-d H:i:s');
            $channel->save(false);
        } else {
            $this->trace('scan-channel', 'INVIO non completato per gli SMS - CH: '. $channel->id . ' sarà ricontrollato alprossimo giro di CRON ');
        }
    }

    /**
     *
     */
    private function checkMailupResponse($response, $message)
    {
        if (isset($response->ErrorCode)) {
            $exception = new MailupComunicationException($message);
            $exception->setObjError($response);
            throw $exception;
        }
    }

    //     public function actionTestMessage()
    //     {

    //         $channel = PreferenceCampainChannelMm::findOne(27);

    //         $newContent = MailUpEmailUtility::generateNewsletterContent($channel);
    //         $this->trace('scan-channel', 'New Content: ' . $newContent);
    // return 0;
    //         if (empty($channel->template_id)) {
    //             throw new Exception('Template email inesistente!!! impossibile spedire la comunicazione');
    //         }

    //         $emailTemplate = $this->mailService->getEmail(1, 9);
    //         $this->trace('scan-channel', 'TRACCIA: ' . $emailTemplate->Content);

    //         $htmlWithParams = MailUpEmailUtility::getDynamicContentMailup($emailTemplate, $newContent);

    //         // COPY THE MAILUP MESSAGE
    //         //
    //         $this->trace('scan-channel', 'EMAIL: ' . $htmlWithParams);

    //         return 0 ;

    //         $decodedCopiedMessage = $this->mailService->copyMessage(1, 9, [
    //             'Subject' => 'COPY TEMPLATE',
    //             'Notes' => 'una nota',
    //         ]);

    //         // UPDATE THE MESSAGE COPIED WITH THE MODIFIED TEMPLATE
    //         $idList = $decodedCopiedMessage->idList;
    //         $idMessage = $decodedCopiedMessage->idMessage;
    //         $decoded = $this->mailService->getEmail($idList, $idMessage);

    //         $body = [
    //             'Subject' => $decoded->Subject,
    //             'idList' => $decoded->idList,
    //             'Content' => $htmlWithParams,
    //             'Tags' => $decoded->Tags,
    //             'TrackingInfo' => $decoded->TrackingInfo,
    //             'Embed' => $decoded->Embed,
    //             'IsConfirmation' => $decoded->IsConfirmation,
    //             'UseDynamicField' => $decoded->UseDynamicField,
    //             'Structure' => $decoded->Structure,
    //         ];

    //         $this->trace('scan-channel', 'Messaggio copiato, Mailup Message ID: ' . $idMessage);
    //         $decodedReponse = $this->mailService->updateEmail($idList, $idMessage, $body);
    //     }

}
