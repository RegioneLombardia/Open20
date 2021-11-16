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
 * Class NewsletterContentsConf
 *
 * This is the base-model class for table "notification_newsletter_contents_conf".
 *
 * @property integer $id
 * @property string $tablename
 * @property string $classname
 * @property string $label
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
abstract class NewsletterContentsConf extends Record
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
        return 'notification_newsletter_contents_conf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tablename', 'classname', 'label'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['tablename', 'classname', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosNotify::t('amosnotify', 'ID'),
            'tablename' => AmosNotify::t('amosnotify', 'Tablename'),
            'classname' => AmosNotify::t('amosnotify', 'Classname'),
            'label' => AmosNotify::t('amosnotify', 'Label'),
            'created_at' => AmosNotify::t('amosnotify', 'Created at'),
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
        return $this->hasMany($this->notifyModule->model('NewsletterContents'), ['newsletter_contents_conf_id' => 'id']);
    }
}
