<?php

namespace backend\modules\userimporter\models\base;

use backend\modules\userimporter\models\base\UserImportTaskStatus;
use backend\modules\userimporter\Module;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the base-model class for table "user_import_task".
 *
 * @property integer $id
 * @property string $name
 * @property string $task_date
 * @property integer $user_id
 * @property integer $tot_input_processed
 * @property integer $tot_input_imported
 * @property integer $mailup_sending_id
 * @property integer $mailup_message_id
 * @property integer $mailup_import_id
 * @property integer $mailup_group_id
 * @property integer $mailup_list_id
 * @property integer $n_user_found
 * @property integer $status
 * @property integer $accept
 * @property int $event_group_referent_id [int(11)]  Event Group Referent
 * @property int $n_sent [int(11)]
 * @property int $mailup_errors [int(11)]
 * @property string $created_at [datetime]  Created at
 * @property string $mailup_error_message Created at
 * @property string $updated_at [datetime]  Updated at
 * @property string $deleted_at [datetime]  Deleted at
 * @property int $created_by [int(11)]  Created by
 * @property int $updated_by [int(11)]  Updated at
 * @property int $deleted_by [int(11)]  Deleted at
 *
 */
class UserImportTask extends Record
{
    /**
     * @var Module $module
     */
    protected $module = null;
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module = \Yii::$app->getModule(Module::getModuleName());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_import_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'task_date', 'user_id', 'accept'], 'required'],
            [['accept'], 'required', 'requiredValue' => 1, 'message' => Yii::t('amosuserimporter', '#required_accept')],
            [['task_date', 'created_at', 'updated_at', 'deleted_at', 'mailup_sending_id',
                'mailup_message_id', 'mailup_import_id', 'mailup_group_id', 'mailup_list_id', 'status'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['user_id', 'tot_input_processed', 'tot_input_imported', 'n_user_found', 'n_sent', 'accept', 'mailup_errors'], 'integer'],
            [['name', 'mailup_error_message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosuserimporter', 'ID'),
            'name' => Yii::t('amosuserimporter', '#name'),
            'task_date' => Yii::t('amosuserimporter', '#task_date'),
            'user_id' => Yii::t('amosuserimporter', '#user_id'),
            'tot_input_processed' => Yii::t('amosuserimporter', '#totale_processati'),
            'tot_input_imported' => Yii::t('amosuserimporter', '#totale_importi'),
            'file_input' => Yii::t('amosuserimporter', 'File Input'),
            'file_success_input' => Yii::t('amosuserimporter', 'File Success Input'),
            'file_errors_input' => Yii::t('amosuserimporter', 'File Errors Input'),
            'accept' => Yii::t('amosuserimporter', '#accept'),
        ];
    }

    /**
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserImportTaskUsers()
    {
        return $this->hasMany(\backend\modules\userimporter\models\UserImportTaskUser::className(), ['user_import_task_id' => 'id']);
    }

    /**
     * @param $statuses
     * @return string
     */
    public function getSingleStatusSending($statuses)
    {
        if ($this->status == \backend\modules\userimporter\base\UserImportTaskStatus::STATUS_SENDING) {
            if (!empty($statuses[$this->id])) {
                if ($statuses[$this->id]['status'] == 'waiting') {
                    return \Yii::t('amosuserimporter', 'In coda');
                } else {
                    $percent = round(((int)$statuses[$this->id]['n_sent'] / $this->n_user_found) * 100);
                    return \Yii::t('amosuserimporter', "Invio in corso") . ': ' . $percent . '%';
                }
            } else {
                return \Yii::t('amosuserimporter', 'Invio completato');
            }
        } else {
            if ($this->status) {
                return \backend\modules\userimporter\base\UserImportTaskStatus::getStateLabel($this->status);
            }
        }
        return \Yii::t('amosuserimporter', "Bozza");
    }

    /**
     * @param $userImportTaskIds
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getStatusSending($userImportTaskIds)
    {
        $statuses = [];
        $newsletterModule = \Yii::$app->getModule('newsletter');
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $internalLists = \backend\modules\userimporter\models\UserImportTask::find()->andWhere(['id' => $userImportTaskIds])->all();
            $sendingIdsLists = [];
            $sendingIds = [];
            foreach ($internalLists as $list) {
                $sendingIdsLists[$list->mailup_sending_id] = $list->id;
                $sendingIds[] = $list->mailup_sending_id;
            }

            $reportSendingOngoing = $mailService->getOngoingSending();
//            pr($reportSendingOngoing, 'ongoing');
            if (!empty($reportSendingOngoing)) {
                foreach ($reportSendingOngoing->Items as $item) {
                    if (!empty($item->Id)) {
                        if (in_array($item->Id, $sendingIds)) {
                            $decodedHistory = $mailService->getEmailSendHistory($item->IdList, $item->IdMessage, []);
//pr($decodedHistory,'history');
                            $n = 0;
                            if (!empty($decodedHistory->Items)) {
                                $elem = $decodedHistory->Items[0];
                                $n = $elem->Recipients;
                            }
                            $statuses[$sendingIdsLists[$item->Id]] = ['status' => 'running', 'n_sent' => $n];
                        }
                    }

                }
            }

            $reportSendingWiting = $mailService->getWaitingSending();//
//                                    pr($reportSendingWiting, 'waiting');
            ;
            if (!empty($reportSendingWiting)) {
                foreach ($reportSendingWiting->Items as $item) {
                    if (!empty($item->Id)) {
                        if (in_array($item->Id, $sendingIds)) {
                            $statuses[$sendingIdsLists[$item->Id]] = ['status' => 'waiting'];
                        }
                    }

                }
            }
        }

        return $statuses;
    }
}