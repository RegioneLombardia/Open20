<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models\base
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models\base;

use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;

/**
 * Class Newsletter
 *
 * This is the base-model class for table "notification_newsletter".
 *
 * @property integer $id
 * @property string $status
 * @property string $subject
 * @property string $send_date_begin
 * @property string $send_date_end
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\notificationmanager\models\NewsletterContents[] $newsletterContents
 *
 * @package open20\amos\notificationmanager\models\base
 */
abstract class Newsletter extends Record
{
    /**
     * @var AmosNotify $notifyModule
     */
    protected $notifyModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->notifyModule = AmosNotify::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'subject'], 'required'],
            [['send_date_begin', 'send_date_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['status', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosNotify::t('amosnotify', 'ID'),
            'status' => AmosNotify::t('amosnotify', '#newsletter_status'),
            'subject' => AmosNotify::t('amosnotify', '#newsletter_subject'),
            'send_date_begin' => AmosNotify::t('amosnotify', '#newsletter_send_date_begin'),
            'send_date_end' => AmosNotify::t('amosnotify', '#newsletter_send_date_end'),
            'created_at' => AmosNotify::t('amosnotify', '#creation_date'),
            'updated_at' => AmosNotify::t('amosnotify', 'Updated at'),
            'deleted_at' => AmosNotify::t('amosnotify', 'Deleted at'),
            'created_by' => AmosNotify::t('amosnotify', 'Created by'),
            'updated_by' => AmosNotify::t('amosnotify', 'Updated by'),
            'deleted_by' => AmosNotify::t('amosnotify', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterContents()
    {
        return $this->hasMany($this->notifyModule->model('NewsletterContents'), ['newsletter_id' => 'id']);
    }
}
