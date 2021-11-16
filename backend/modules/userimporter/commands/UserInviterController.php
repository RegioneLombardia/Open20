<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 29/04/2020
 * Time: 11:52
 */

namespace backend\modules\userimporter\commands;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\userimporter\models\UserImportTask;
use backend\modules\userimporter\models\UserImportTaskUser;
use backend\modules\userimporter\base\UserImportTaskStatus;
use console\exceptions\MailupComunicationException;
use open20\amos\admin\controllers\UserDropController;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\StringHelper;
use open20\amos\core\user\User;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventEmailTemplates;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\utility\EventMailUtility;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\Console;
use yii\helpers\Json;
use Yii;
use yii\helpers\VarDumper;
use yii\validators\EmailValidator;
use yii\validators\Validator;

class UserInviterController extends Controller
{

    const NAME_POS = 0;
    const SURNAME_POS = 1;
    const EMAIL_POS = 2;
    const CF_POS = 3;

    const MIN_COLUMNS = 3;

    private $task;
    private $tempPath = '';
    private $storage;
    private $Okspreadsheet;
    private $Errspreadsheet;
    private $Okworksheet;
    private $Errworksheet;
    private $Okrow;
    private $Errrow;
    public $Warning;


    public $tasks;
    public $mailService;
    public $mailupListId = 1;
    public $maxErrorsOnTask = 5;
    public $mailupImportLimit = 10000; // limite di mailup per l'importazione
    public $originalMessageId = null;
    public $dynamics_fields = [
        1 => 'nome',
        2 => 'cognome',
        30 => 'token'
    ];

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->mailupListId = Yii::$app->params['mailup']['newsletter']['list-id'];
        $this->originalMessageId = 1148;

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $this->mailService = new $mailServiceClassname();
        } else {
            throw new InvalidConfigException('the module newsletter must be enabled in common');
        }


        $this->storage = Yii::$app->getModule('attachments');
        $this->tempPath = Yii::getAlias($this->storage->tempPath);

        $this->Okspreadsheet = new Spreadsheet();
        $this->Errspreadsheet = new Spreadsheet();
        $this->Okworksheet = $this->Okspreadsheet->getActiveSheet();
        $this->Errworksheet = $this->Errspreadsheet->getActiveSheet();

        $this->Okrow = 1;
        $this->Errrow = 1;

    }


    /**
     * @throws InvalidConfigException
     */
    public function actionCleanUsers()
    {
        Console::stdout('##################################################' . PHP_EOL);
        Console::stdout('##### START CRON clean-users-imported        #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##################################################' . PHP_EOL);

        $imports = UserImportTaskUser::find()
            ->andWhere(['<', new Expression('DATE_ADD( created_at, INTERVAL 5 DAY)'), new Expression('NOW()')])
            ->andWhere(['user_id' => null])->all();
        $i = 0;
        foreach ($imports as $import) {
            $import->delete();
            $i++;
        }
        Console::stdout("- $i user removed" . PHP_EOL);

        Console::stdout('----------- END CRON clean-users-imported ----------- ' . PHP_EOL);

    }


    /**
     * @throws InvalidConfigException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\db\Exception
     */
    public function actionImportAndInvite()
    {
        Console::stdout('##################################################' . PHP_EOL);
        Console::stdout('##### START CRON import-user-excels          #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##################################################' . PHP_EOL);

        /** @var ActiveQuery $taskQuery */
        $taskQuery = UserImportTask::find()
            ->andWhere(['not in', 'status', [
                UserImportTaskStatus::STATUS_SENT,
                UserImportTaskStatus::END,
                UserImportTaskStatus::STATUS_NO_USER_TO_IMPORT,
                UserImportTaskStatus::STATUS_SENDING
            ]])
            ->andWhere(['<', 'mailup_errors', 5]);
//        Console::stdout($taskQuery->createCommand()->rawSql . PHP_EOL);

        /** @var  $task UserImportTask */
        $task = $taskQuery->one();
        if (!empty($task)) {
            try {
                Console::stdout('----------- START TASK ' . $task->id . ' - ' . $task->name . '----------- ' . PHP_EOL);

                // --- IMPORT FROM EXCEL
                if ($task->status === (string)UserImportTaskStatus::PENDING) {
                    $this->importFromExcel($task);
                }

                // --- CREATE MALUP GROUP AND  GET USER AND IMPORT TO GROUP
                if (in_array($task->status, [
                    UserImportTaskStatus::STATUS_IMPORTING_USERS,
                    UserImportTaskStatus::STATUS_USERS_TO_IMPORT
                ])
                ) {
                    $this->createMailGroup($task);
                    $this->getUserAndImport($task);
                }

                // ---- SEND INVITATION
                if ($task->status == UserImportTaskStatus::STATUS_IMPORTED) {
                    $this->sendInvitation($task);
                }

            } catch (\Exception $e) {
                $this->addErrorToTask($task, $e);
            }

            Console::stdout('----------- END TAKS ' . $task->id . ' - ' . $task->name . '----------- ' . PHP_EOL);
        }


        // tratto separatamente le conclusioni degli invii
        $taskQuery = UserImportTask::find()
            ->andWhere(['status' => UserImportTaskStatus::STATUS_SENDING])
            ->andWhere(['<=', 'mailup_errors', 5]);
        $tasks = $taskQuery->all();
        /** @var UserImportTask $task */
        foreach ($tasks as $task) {
            try {
                Console::stdout('- START Check completed TAKS ' . $task->id . ' - ' . $task->name . '----------- ' . PHP_EOL);

                $this->sendingCompleted($task);

            } catch (\Exception $e) {
                $this->addErrorToTask($task, $e);
            }
            Console::stdout('- END Check completed TAKS ' . $task->id . ' - ' . $task->name . '----------- ' . PHP_EOL);
        }

    }

    /**
     * @param UserImportTask $task
     */
    private function addErrorToTask($task, $e)
    {
        // incremento il numero di errori sulla campagna
        $number = intval(is_null($task->mailup_errors) ? 0 : (int)$task->mailup_errors);
        $task->mailup_errors = ($number + 1);

        if ($task->mailup_errors >= $this->maxErrorsOnTask) {
            $task->status = UserImportTaskStatus::STATUS_ERROR;
        }

        $task->mailup_error_message = StringHelper::truncate($e->getMessage(), 255);
        $task->save(false);

        Console::stdout("- EXCEPTION ........ : " . $e->getMessage() . PHP_EOL);
        Console::stdout("- data exception ........ : " . $e->getTraceAsString() . PHP_EOL);
    }


    /**
     * @param $task UserImportTask
     */
    public function sendInvitation($task)
    {

        $appName = \Yii::$app->name;
        // http://[track]/http://lombardiainforma.regione.lombardia.it/it/privacy-policy
        $urlRegister = 'http://[track]/' . \Yii::$app->params['platform']['frontendUrl'] . '/?token_invitation=[token]';
        $subject = 'Sei invitato alla piattaforma ' . $appName;
        $linkQui = '<a href="'.$urlRegister.'" target="_blank" id="">qui</a>';

//        $moduleEvents = \Yii::$app->getModule('events');
//        if($moduleEvents){
//            if($moduleEvents->disableSocial){
//                $urlRegister = \Yii::$app->params['platform']['backendUrl'] . '/admin/security/login?token_invitation={TOKEN}';
//            }
//        }
        //----- GET TEMPLATE TO SEND
//        $text = "Gentile {NOME} {COGNOME}<br>
//        Sei stato invitato a registrarti alla piattaforma <strong>$appName</strong>
//        Clicca <a href='$urlRegister'>qui</a> per registrarti alla piattaforma.";
//        $subject = 'Sei invitato alla piattaforma ' . $appName;
//        $urlImage = \Yii::$app->params['platform']['backendUrl'] . "/img/img_default.jpg";
//
//        $subjectWithParams = EventMailUtility::parseEmailWithParams(null, null, $subject, false);
//        $textWithParms = EventMailUtility::parseEmailWithParams(null, null, $text);

        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            // GET MAILUP TEMPLATE AND SUBTITUTION OF [CONTENT] WITH PLATFORM PERSONALIZED EMAIL TEXT
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $emailDecoded = $mailService->getEmail($this->mailupListId, $this->originalMessageId);
            $htmlWithParams = $this->parseDynamicContentMailup($linkQui, $appName, $emailDecoded);

            // COPY THE MAILUP MESSAGE
            Console::stdout('--- COPY MESSAGE ' . $this->originalMessageId . PHP_EOL);
            $decodedCopiedMessage = $mailService->copyMessage($this->mailupListId, $this->originalMessageId, [
                'Subject' => $subject,
                'Notes' => "I_ID: " . $task->id . ' E_ID: ' . $task->id
            ]);

            // UPDATE THE MESSAGE COPIED WITH THE MODIFIED TEMPLATE
            $idList = $decodedCopiedMessage->idList;
            $idMessage = $decodedCopiedMessage->idMessage;
            $decoded = $mailService->getEmail($idList, $idMessage);

            $body = [
                'Subject' => $decoded->Subject,
                'idList' => $decoded->idList,
                'Content' => $htmlWithParams,
                'Tags' => $decoded->Tags,
                'TrackingInfo' => $decoded->TrackingInfo,
                'Embed' => $decoded->Embed,
                'IsConfirmation' => $decoded->IsConfirmation,
                'UseDynamicField' => $decoded->UseDynamicField,
                'Structure' => $decoded->Structure
            ];

            Console::stdout('--- UPDATE COPIED MESSAGE ' . $idMessage . PHP_EOL);
            $decodedReponse = $mailService->updateEmail($idList, $idMessage, $body);
            $mailService->enableDisableDynamicFieldsEmail($idList, $idMessage, 'true');

            // SEND MESSAGE TO THE GROUP CRATED FOR THE INVITATION
            Console::stdout('--- SEND EMAIL IMPORT' . ' TO GROUP:  ' . $task->mailup_group_id . PHP_EOL);
            $sent = $mailService->sendEmailToGroup($task->mailup_group_id, $idMessage);
            $task->mailup_message_id = $idMessage;
            $task->mailup_list_id = $idList;
            $task->mailup_sending_id = $sent->Id;
            $task->status = UserImportTaskStatus::STATUS_SENDING;
            $task->save(false);

        }
    }

    private function parseDynamicContentMailup($linkQui, $appName, $emailDecoded)
    {
        $htmlWithParams = str_replace('{link_qui}',  $linkQui, $emailDecoded->Content);
        $htmlWithParams = str_replace('{piattaforma}', $appName, $htmlWithParams);

        return $htmlWithParams;
    }

    /**
     * @param $task UserImportTask
     */
    public function createMailGroup($task)
    {

        //-- CREATE THE MAILUP GROUP IF NOT EXISTS
        if (empty($task->mailup_group_id)) {
            $data = [
                'Name' => $task->id . '_' . rand(1, 9999) . '_' . substr(str_replace('\'', '', $task->name), 0, 20),
                'Notes' => 'Id import task: ' . $task->id

            ];
            $groupDecoded = $this->mailService->createGroup($this->mailupListId, $data);

            if ($groupDecoded) {
                if (!empty($groupDecoded->ErrorCode)) {
                    Console::stdout(json_encode($groupDecoded) . PHP_EOL);
                } else {
                    Console::stdout('--- CREATE GROUP ' . $groupDecoded->idGroup . PHP_EOL);
                    $task->mailup_group_id = $groupDecoded->idGroup;
                    $task->mailup_list_id = $this->mailupListId;
                    $task->save(false);
                }
            }
        }
    }

    /**
     * @param $task
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function importFromExcel($task)
    {
        $filePath = $task->file_input->getPath();
        $reader = $this->buildReader($task);
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $isHeader = true;

        $this->Warning = 0;
        $nFound = 0;
        $i = 0;
        Console::stdout("- IMPORT FROM EXCEL: " . $filePath . PHP_EOL);

//        try {
            foreach ($worksheet->getRowIterator() as $row) {

                if (!$isHeader) {
                    $i++;
                    $cellIterator = $row->getCellIterator();
                    $values = [];
                    foreach ($cellIterator as $cell) {
                        $values[] = $cell->getValue();
                    }
                    // skip rows without values
                    $isRowEmpty = true;
                    foreach ($values as $val) {
                        $isRowEmpty = $isRowEmpty && empty($val);
                    }

                    if (!$isRowEmpty && count($values) >= self::MIN_COLUMNS) {
                        $user = $this->isUserPresent($values);
//                        $isImported = $this->isImported($values, $task);
                        if (is_null($user)) {
                            /** @var EmailValidator $emailValidator */
                            $emailValidator = new EmailValidator();
                            if (!empty($values[self::EMAIL_POS])) {
                                if ($emailValidator->validate($values[self::EMAIL_POS])) {
                                    $userImported = $this->isEmailImportedInOtherList($values, $task);
                                    $userImport = new UserImportTaskUser();
                                    $userImport->user_import_task_id = $task->id;
                                    $userImport->email = $values[self::EMAIL_POS];
                                    $userImport->name = $values[self::NAME_POS];
                                    $userImport->surname = $values[self::SURNAME_POS];
                                    $userImport->to_send = true;
                                    if ($userImported) {
                                        $userImport->token = $userImported->token;
                                        $userImport->expire_date = $userImported->expire_date;
                                        $userImport->to_send = false;
                                        $this->fillErrWorkSheet($values, ["Utente già importato"]);
                                        $this->Warning = $this->Warning + 1;

                                    } else {
                                        $expireDate = date('Y-m-d H:i:s', strtotime("+5 days"));
                                        $userImport->expire_date = $expireDate;
                                        $userImport->token = hash('md5', $task->id) . hash('md5', $i);
                                    }

                                    if ($userImport->save(false)) {

                                        if ($userImport->to_send) {
                                            $this->fillOkWorkSheet($values);
                                            $this->Okrow++;
                                        }
                                    } else {
                                        $err = '';
                                        foreach ($userImport->getErrors() as $error => $message) {
                                            $err .= " - " . $error . ': ' . implode(' ', $message);
                                        }
                                        $this->fillErrWorkSheet($values, ['Non importato: ', $err]);
                                    }
                                } else {
                                    $this->fillErrWorkSheet($values, ['Non importato: email malformata']);
                                }
                            } else {
                                $this->fillErrWorkSheet($values, ['Non importato: email mancante']);
                            }

                        } else {
                            if ($user->userProfile->attivo == 0) {
                                $this->fillErrWorkSheet($values, ['Non importato: presente a sistema ma disattivato']);
                            } else {
                                $this->fillErrWorkSheet($values, ['Non importato: Utente già presente a sistema']);;
                            }
                        }
                    }
                } else {
                    $isHeader = false;
                }
            }
            $ok_file_name = $this->tempPath . DIRECTORY_SEPARATOR . $this->generateFileName();
            $writer = new Xlsx($this->Okspreadsheet);
            $writer->save($ok_file_name);
            $err_file_name = $this->tempPath . DIRECTORY_SEPARATOR . $this->generateFileName();
            $writer = new Xlsx($this->Errspreadsheet);
            $writer->save($err_file_name);

            $this->storage->attachFile($ok_file_name, $task, 'file_success_input', true);
            $this->storage->attachFile($err_file_name, $task, 'file_errors_input', true);
            $task->status = UserImportTaskStatus::STATUS_USERS_TO_IMPORT;
            $task->tot_input_processed = $this->Okrow + $this->Errrow - 2;
            $task->tot_input_imported = $this->Okrow - 1;
            if (!$task->save()) {
                Console::stdout("- Errors save task: " . VarDumper::dumpAsString($task->errors, 3, false) . PHP_EOL);
            }

            Console::stdout("- processed: " . $task->tot_input_processed . PHP_EOL);
            Console::stdout("- processed: " . $task->tot_input_processed . PHP_EOL);
            Console::stdout("- imported: " . $task->tot_input_imported . PHP_EOL);

            // gestisco il caso di nessun utente da importare
            $query = $task->getUserImportTaskUsers()->andWhere(['to_send' => true]);
            $n = $query->count();
            if ($n <= 0) {
                $task->status = UserImportTaskStatus::STATUS_NO_USER_TO_IMPORT;
                $task->save(false);
                Console::stdout('- no user to import' . PHP_EOL);
            } else {
                $task->n_user_found = $n;
                $task->save(false);
            }

//        } catch (Exception $e) {
//            Console::stdout("- EXCEPTION ........ : " . $e->getMessage() . PHP_EOL);
//        }

    }


    /**
     * @param UserImportTask $task
     * @throws \Exception
     */
    private function getUserAndImport($task, $type)
    {
        // SE esiste un gruppo
        if (!empty($task->mailup_group_id)
            && (in_array($task->status, [
                UserImportTaskStatus::STATUS_IMPORTING_USERS,
                UserImportTaskStatus::STATUS_USERS_TO_IMPORT
            ]))
        ) {

            if (!empty($task->mailup_import_id)) {
                $importDecoded = $this->mailService->getImport($task->mailup_import_id);
                if (isset($importDecoded->ErrorCode)) {
                    $exception = new MailupComunicationException('Mailup getImport - asking if completed import');
                    $exception->setObjError($importDecoded);
                    throw $exception;
                }

                if ($importDecoded->Completed == true) {
                    Console::stdout("--- wip: Gli utenti sono stati caricati sul mailup_group_id: " . $task->mailup_group_id . " con importazione di ID: " . $task->mailup_import_id . PHP_EOL);
                    // butto via questo invio sulla tabella, è terminato...
                    // al prossimo giro del CRON dovrà essere caricata un'altra tranche di utenti
                    $task->mailup_import_id = null;
                    $task->save(false);

                    $this->newUsersImport($task);
                } else {
                    Console::stdout("--- wip: Importazione di ID: " . $task->mailup_import_id . " non ancora terminata... il controllo è rimandato al prossimo giro di CRON." . PHP_EOL);
                    Console::stdout("--- wip: Importazione: " . VarDumper::dumpAsString($importDecoded, 3,false) . PHP_EOL);
                }

            } else {
                $this->newUsersImport($task);
            }
        }
    }

    /**
     * @param $task UserImportTask
     * @throws \yii\db\Exception
     *
     *  */
    public function newUsersImport($task)
    {
        if ($task->mailup_group_id) {
            //--- GET USERS TO IMPORT TO THE MAILUP GROUP ---
            $query = $task->getUserImportTaskUsers()
                ->andWhere(['to_send' => true])
                ->andWhere(['mailup_import_id' => null])
                ->limit($this->mailupImportLimit);
            $result = $query->asArray()->all();
            $nUsers = count($result);
            $task->save(false);

            $users = [];
            $usersIds = [];
            if ($nUsers > 0) {
                Console::stdout('--- Prepare data for mailup ' . PHP_EOL);
                foreach ($result as $row) {
                    $fields = [];
                    foreach ($this->dynamics_fields as $id => $field) {
                        if ($field == 'nome') {
                            $fields[] = ['Id' => $id, 'Value' => $row['name']];
                        }
                        if ($field == 'cognome') {
                            $fields[] = ['Id' => $id, 'Value' => $row['surname']];
                        }
                        if ($field == 'token') {
                            $fields[] = ['Id' => $id, 'Value' => $row['token']];
                        }
                    }
                    $usersIds[] = $row['id'];
                    $users [] = [
                        'Email' => $row['email'],
                        'Fields' => $fields,
                        'Name' => ''
                    ];

                }

                // --- IMPORT USERS TO THE GROUP AND UPDATE DATAS
                Console::stdout('--- IMPORT ' . $nUsers . ' USERS to group ' . $task->mailup_group_id . PHP_EOL);
                $importId = $this->mailService->importRecipientsToGroups($users, $task->mailup_group_id);
                if (isset($importId->ErrorCode)) {
                    $exception = new MailupComunicationException('Mailup importRecipientsToGroups - importing user');
                    $exception->setObjError($importId);
                    Console::stdout(VarDumper::dumpAsString($importId,3, false) . PHP_EOL);
                    throw $exception;
                }

                // una volta controllato l'errore rimane solo il caso che importId sia intero...
                if (is_integer($importId)) {
                    $task->mailup_import_id = $importId;
                    $task->status = UserImportTaskStatus::STATUS_IMPORTING_USERS;
                    $task->save(false);

                    // Bisogna marcare tutti gli user con l'id dell'importazione
                    UserImportTaskUser::updateAll(['mailup_import_id' => $importId], ['id' => $usersIds]);
                }

            } else {
                Console::stdout('--- USERS IMPORTED' . PHP_EOL);
                $task->status = UserImportTaskStatus::STATUS_IMPORTED;
                $task->save(false);
            }
        }
    }


    /**
     * @param $internalList
     */
    public function sendingCompleted($internalList)
    {
        $isComplete = true;
        $reportSendingWiting = $this->mailService->getWaitingSending();
        if (!empty($reportSendingWiting)) {
            foreach ($reportSendingWiting->Items as $item) {
                if (!empty($item->Id)) {
                    $isComplete = false;
                }

            }
        }
        $reportSendingOngoing = $this->mailService->getOngoingSending();
        if (!empty($reportSendingOngoing)) {
            foreach ($reportSendingOngoing->Items as $item) {
                if (!empty($item->Id)) {
                    $isComplete = false;
                }

            }
        }
        if ($isComplete) {
            Console::stdout('--- SENDING COMPLETE' . PHP_EOL);
            $internalList->status = UserImportTaskStatus::STATUS_SENT;
            $history = $this->mailService->getEmailSendHistory($internalList->mailup_list_id, $internalList->mailup_message_id, []);
            $n = 0;
            if (!empty($history->Items)) {
                $elem = $history->Items[0];
                $n = $elem->Recipients;
            }
            $internalList->n_sent = $n;
            $internalList->save(false);
        }
    }

    /**
     * @throws \Exception
     */
    public function actionDeleteDeactivatedUsers()
    {
        Console::stdout('##################################################' . PHP_EOL);
        Console::stdout('##### START CRON delete users                #####' . PHP_EOL);
        Console::stdout('##### ' . date('d-m-Y h:i:s') . '                    #####' . PHP_EOL);
        Console::stdout('##################################################' . PHP_EOL);

        $users = UserProfile::find()->andWhere(['attivo' => false])
            ->andWhere(['<', new Expression('DATE_ADD( disactivate_at, INTERVAL 13 MONTH)'), new Expression('NOW()')])->all();
//        $query = UserProfile::find()->andWhere(['attivo' => false])
//            ->andWhere(['<', new Expression('DATE_ADD( disactivate_at, INTERVAL 13 MONTH)'), new Expression('NOW()')])->createCommand()->rawSql;
//        Console::stdout($query . PHP_EOL);

        foreach ($users as $profile) {
            Console::stdout('--- WORKING ON USER ' . $profile->user_id . PHP_EOL);

            $email = $profile->user->email;
            $moduleAdmin = \Yii::$app->getModule('admin');
            $dropController = new UserDropController('user_drop', $moduleAdmin);
//
//            //Irreversible action of user drop
            if (!empty($moduleAdmin) && $moduleAdmin->hardDelete) {
                $dropController->dropEverything($profile->user_id);
            } else {
                $dropController->softDropEverything($profile->user_id);
            }
            Console::stdout('--- USER ' . $profile->id . ' DELETED - ' . $email . PHP_EOL);

        }

    }


    /**
     *
     * @param array $values
     */
    protected function fillOkWorkSheet(array $values)
    {
        $this->Okworksheet->setCellValue('A' . $this->Okrow, $values[0]);
        $this->Okworksheet->setCellValue('B' . $this->Okrow, $values[1]);
        $this->Okworksheet->setCellValue('C' . $this->Okrow, $values[2]);
    }

    /**
     *
     * @param array $values
     */
    protected function fillErrWorkSheet(array $values, $messages = null)
    {
        $this->Errworksheet->setCellValue('A' . $this->Errrow, $values[0]);
        $this->Errworksheet->setCellValue('B' . $this->Errrow, $values[1]);
        $this->Errworksheet->setCellValue('C' . $this->Errrow, $values[2]);
        if (!is_null($messages)) {
            $this->Errworksheet->setCellValue('D' . $this->Errrow,
                Json::encode($messages));
        }
        $this->Errrow++;
    }

    /**
     *
     * @param array $values
     * @return User
     */
    protected function isUserPresent(array $values)
    {
        $usr = User::findOne(['email' => $values[static::EMAIL_POS]]);
        return $usr;
    }

    /**
     * @param array $values
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function isEmailImportedInOtherList(array $values, $task)
    {
        $taskuser = UserImportTaskUser::find()
            ->andWhere(['email' => $values[static::EMAIL_POS]])
            ->andWhere(['!=', 'user_import_task_id', $task->id])
//            ->andWhere(['>=', 'expire_date', date('Y-m-d H:i:s')])
            ->one();
        return $taskuser;
    }

    /**
     * @param array $values
     * @param $task
     * @param $dg
     * @return bool
     * @throws InvalidConfigException
     */
    protected function isImported(array $values, $task)
    {
        $taskuser = UserImportTaskUser::find()
            ->innerJoinWith('userImportTask')
            ->andWhere(['user_import_task.deleted_at' => null])
            ->andWhere(['email' => $values[static::EMAIL_POS]])
//            ->andWhere([ 'event_group_referent_id' => $task->event_group_referent_id])
            ->count();
        return $taskuser > 0;
    }


    /**
     * @return \PhpOffice\PhpSpreadsheet\Reader\IReader
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function buildReader($task)
    {
        $filePath = $task->file_input->getPath();

        switch ($task->file_input->type) {
            case 'zip':

                break;
        }
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        return $reader;
    }


    /**
     *
     * @param User $user
     */
//    protected function connectDG(User $user, $task)
//    {
//        $eventgroup = new EventGroupReferentMm();
//        $eventgroup->user_id = $user->id;
//        $eventgroup->event_group_referent_id = $task->event_group_referent_id;
//        $eventgroup->exclude_from_query = 0;
//        $eventgroup->save();
//    }


    /**
     *
     * @param string $urlFile
     * @return string
     */
    protected function generateFileName()
    {
        return \Yii::$app->security->generateRandomString() . ".xlsx";
    }


}