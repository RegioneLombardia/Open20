<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models;

use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;

/**
 * Class NewsletterContentsConf
 * This is the model class for table "notification_newsletter_contents_conf".
 * @package open20\amos\notificationmanager\models
 */
class NewsletterContentsConf extends \open20\amos\notificationmanager\models\base\NewsletterContentsConf
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'newsletter_id',
            'newsletter_contents_conf_id',
            'content_id'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return AmosNotify::getModuleName();
    }
    
    /**
     * This method returns a new instance of the model classname configured.
     * @return Record
     * @throws \yii\base\InvalidConfigException
     */
    public function getContentConfModel()
    {
        /** @var Record $contentConfModel */
        $contentConfModel = \Yii::createObject($this->classname);
        return $contentConfModel;
    }
}
