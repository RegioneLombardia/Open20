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

use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use yii\db\ActiveQuery;

/**
 * Class NewsletterContents
 * This is the model class for table "notification_newsletter_contents".
 * @package open20\amos\notificationmanager\models
 */
class NewsletterContents extends \open20\amos\notificationmanager\models\base\NewsletterContents
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'classname'
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
     * Returns the content model instance.
     * @return Record|NewsletterInterface|null
     */
    public function getContentModel()
    {
        $newsletterConf = $this->newsletterContentsConf;
        /** @var Record $className */
        $className = $newsletterConf->classname;
        /** @var ActiveQuery $query */
        $contentModel = $className::findOne(['id' => $this->content_id]);
        return $contentModel;
    }
}
