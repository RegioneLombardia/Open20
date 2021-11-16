<?php
namespace console\controllers;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use console\exceptions\MailupComunicationException;
use Exception;
use preference\userprofile\models\PreferenceChannel;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;
use amos\newsletter\utility\MailupUtility;
use backend\modules\campains\utility\TableMessageBouncesUtility;
use backend\modules\campains\utility\TableMessageClicksUtility;
use backend\modules\campains\utility\TableMessageViewsUtility;
use DateInterval;
use DateTime;
use Yii;
use yii\helpers\Console;
use yii\helpers\Inflector;

/**
 * Undocumented class
 */
class StatisticsController extends PreferenceCronController
{

    public $mailService;
    private $dbstats = 'dbstats';
    private $dayToStats = 7;

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
        $channels = PreferenceCampainChannelMm::findAll([
            'status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT,
            'preference_channel_id' => PreferenceChannel::NEWSLETTER_ID,
            ]);

        /** @var PreferenceCampainChannelMm $channel */
        foreach ($channels as $channel) {

            if (!empty($channel) && ($channel instanceof PreferenceCampainChannelMm)) {
                try {

                    $dateTimeToStats = $this->getTimeToStats($channel);
                    $dateTimeNow = new DateTime();
                    // Console::stdout($channel->id . ' - ' .VarDumper::dumpAsString($dateTimeToStats, 3, false) . "\n");

                    // SE il canale è NEWSLETTER/EMAIL 
                    if ($channel->preference_channel_id == PreferenceChannel::NEWSLETTER_ID) {

                        // se sono passati n gg da quando è stata inviata la campagna
                        if ($dateTimeNow >= $dateTimeToStats) {

                            // Se non essite la tabella allora raccolgo i dati e li inserisco
                            if (Yii::$app->dbstats->schema->getTableSchema(TableMessageViewsUtility::getTableName($channel->id), true) === null) {
                                $this->createTableMessageViews($channel);
                            }

                            // Se non essite la tabella allora raccolgo i dati e li inserisco
                            if (Yii::$app->dbstats->schema->getTableSchema(TableMessageClicksUtility::getTableName($channel->id), true) === null) {
                                $this->createTableMessageClicks($channel);
                            }

                            // Se non essite la tabella allora raccolgo i dati e li inserisco
                            if (Yii::$app->dbstats->schema->getTableSchema(TableMessageBouncesUtility::getTableName($channel->id), true) === null) {
                                $this->createTableMessageBounces($channel);
                            }

                            

                        }

// Console::stdout(VarDumper::dumpAsString($this->mailService->getStatisticMessageCountClicks($channel->mailup_message_id) , 3, false) . "\n");
// Console::stdout(VarDumper::dumpAsString($this->mailService->getEmailSendHistory($channel->mailup_list_id, $channel->mailup_message_id, []) , 3, false) . "\n\n\n");


// Console::stdout(VarDumper::dumpAsString(MailupUtility::getAllDataFromFunction('getEmailRecipients', [$channel->mailup_message_id])) . "\n");
// Console::stdout(VarDumper::dumpAsString(MailupUtility::getAllDataFromFunction('getStatisticBounces', [$channel->mailup_message_id])) . "\n\n\n");
                        // getSubscribtionsToGroup
// mailup_stats_number_comunication_sent
// mailup_stats_number_comunication_bounces

                    }

                } catch (MailupComunicationException $me) {
                    if (!is_null($me->getObjError()) && isset($me->getObjError()->ErrorCode)) {
                        $channel->mailup_error_message = 'Error code: ' . $me->getObjError()->ErrorCode . ': ' . $me->getObjError()->ErrorName;
                        $this->trace('scan-channel', 'ERROR MAILUP RESPONSE [response]: ' . VarDumper::dumpAsString($channel->toArray()));
                    } else {
                        $channel->mailup_error_message = 'Errore generico di comunicazione con mailup...';
                    }
                    // salvo il messaggio di errore!
                    $channel->save(false);

                    $this->trace('scan-channel', 'ERROR '.$me->getCode().' [file]: ' . $me->getLine() . ' - ' . $me->getFile());
                    $this->trace('scan-channel', 'ERROR '.$me->getCode().' [message]: ' . $me->getMessage());
                    $this->trace('scan-channel', 'ERROR '.$me->getCode().' [trace]: ' . VarDumper::dumpAsString($me->getTraceAsString(),3,false));
                    $this->trace('scan-channel', 'ERROR '.$me->getCode().' [channel]: ' . VarDumper::dumpAsString($channel->toArray()));
                   

                } catch (Exception $e) {
                    $this->trace('scan-channel', 'ERROR '.$e->getCode().' [file]: ' . $e->getLine() . ' - ' . $e->getFile());
                    $this->trace('scan-channel', 'ERROR '.$e->getCode().' [message]: ' . $e->getMessage());
                    $this->trace('scan-channel', 'ERROR '.$e->getCode().' [trace]: ' . VarDumper::dumpAsString($e->getTraceAsString(),3,false));
                    $this->trace('scan-channel', 'ERROR '.$e->getCode().' [channel]: ' . VarDumper::dumpAsString($channel->toArray()));

                    // LOG ERRORE, proseguo sul resto
                }
            }

        }
    }

    /**
     * @param PreferenceCampainChannelMm $channel
     * @return DSateTime
     */
    private function getTimeToStats($channel)
    {
        $dateTimeSentString = $channel->getStatusLastUpdateTime(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT);
        $dateTimeSent = new DateTime($dateTimeSentString);
        $dateTimeToStats = clone $dateTimeSent;
        $dateTimeToStats = $dateTimeToStats->add(new DateInterval('P'.$this->dayToStats.'D'));

        return $dateTimeToStats;
    }

    /**
     * Undocumented function
     *
     * @param PreferenceCampainChannelMm $channel
     * @return void
     */
    private function createTableMessageViews($channel)
    {
        $allData = MailupUtility::getAllDataFromFunction('getStatisticMessageListViews', [$channel->mailup_message_id]);

        if (!empty($allData)) {
            $this->trace('scan-channel', "PreferenceCampainChannelMm di ID " .$channel->id." riempie tabella " . TableMessageViewsUtility::getTableName($channel->id));
            foreach ($allData as $item) {
                $model = TableMessageViewsUtility::buildModel($channel->id, $this->dbstats);
                foreach ($item as $field => $value) {
                    // Console::stdout(Inflector::camel2id($field, '_') . "\n");
                    $fieldForDb = Inflector::camel2id($field, '_');
                    $model->$fieldForDb = $value;
                }
                $model->created_at = date('Y-m-d H:i:s');
                $model->communication_id = $channel->id;
                $model->mailup_group_id = $channel->mailup_group_id;

                $model->save();
            }

            // salvo il numero di views sul canale mm
            $channel->mailup_stats_number_comunication_views = count($allData);
            $channel->save(false);

            $this->trace('scan-channel', "Fine riempimento tabella " . TableMessageViewsUtility::getTableName($channel->id) . " con " .count($allData). " elementi");
        }

    }

    /**
     * Undocumented function
     *
     * @param PreferenceCampainChannelMm $channel
     * @return void
     */
    private function createTableMessageClicks($channel)
    {
        $allData = MailupUtility::getAllDataFromFunction('getStatisticMessageListClicks', [$channel->mailup_message_id]);

        if (!empty($allData)) {
            $this->trace('scan-channel', "PreferenceCampainChannelMm di ID " .$channel->id." riempie tabella " . TableMessageClicksUtility::getTableName($channel->id));
            foreach ($allData as $item) {
                $model = TableMessageClicksUtility::buildModel($channel->id, $this->dbstats);
                foreach ($item as $field => $value) {
                    $fieldForDb = Inflector::camel2id($field, '_');
                    $model->$fieldForDb = $value;
                }
                $model->created_at = date('Y-m-d H:i:s');
                $model->communication_id = $channel->id;
                $model->mailup_group_id = $channel->mailup_group_id;

                $model->save();
            }

            // salvo il numero di clicks sul canale mm
            $channel->mailup_stats_number_comunication_clicks = count($allData);
            $channel->save(false);

            $this->trace('scan-channel', "Fine riempimento tabella " . TableMessageClicksUtility::getTableName($channel->id) . " con " .count($allData). " elementi");
        }

    }

        /**
     * Undocumented function
     *
     * @param PreferenceCampainChannelMm $channel
     * @return void
     */
    private function createTableMessageBounces($channel)
    {
        $allData = MailupUtility::getAllDataFromFunction('getStatisticMessageListBounces', [$channel->mailup_message_id]);

        if (!empty($allData)) {
            $this->trace('scan-channel', "PreferenceCampainChannelMm di ID " .$channel->id." riempie tabella " . TableMessageBouncesUtility::getTableName($channel->id));
            foreach ($allData as $item) {
                $model = TableMessageBouncesUtility::buildModel($channel->id, $this->dbstats);
                foreach ($item as $field => $value) {
                    $fieldForDb = Inflector::camel2id($field, '_');
                    $model->$fieldForDb = $value;
                }
                $model->created_at = date('Y-m-d H:i:s');
                $model->communication_id = $channel->id;
                $model->mailup_group_id = $channel->mailup_group_id;

                $model->save();
            }

            // salvo il numero di clicks sul canale mm
            $channel->mailup_stats_number_comunication_bounces = count($allData);
            $channel->save(false);

            $this->trace('scan-channel', "Fine riempimento tabella " . TableMessageBouncesUtility::getTableName($channel->id) . " con " .count($allData). " elementi");
        }

    }



}
