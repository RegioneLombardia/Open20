<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base;

use open20\amos\notificationmanager\base\builder\ContactAcceptedMailBuilder;
use open20\amos\notificationmanager\base\builder\ContentImmediateMailBuilder;
use open20\amos\notificationmanager\base\builder\ContentMailBuilder;
use open20\amos\notificationmanager\base\builder\CustomMailBuilder;
use open20\amos\notificationmanager\base\builder\NewsletterBuilder;
use open20\amos\notificationmanager\base\builder\SleepingUserMailBuilder;
use open20\amos\notificationmanager\base\builder\SuccessfulContentMailBuilder;
use open20\amos\notificationmanager\base\builder\SuccessfulUserMailBuilder;
use open20\amos\notificationmanager\base\builder\SuggestedLinkMailBuilder;
use open20\amos\notificationmanager\base\builder\ValidatedMailBuilder;
use open20\amos\notificationmanager\base\builder\ValidatorsMailBuilder;
use open20\amos\notificationmanager\models\ChangeStatusEmail;
use yii\base\BaseObject;

/**
 * Class BuilderFactory
 * @package open20\amos\notificationmanager\base
 */
class BuilderFactory extends BaseObject
{
    const CONTENT_MAIL_BUILDER = 1;
    const VALIDATORS_MAIL_BUILDER = 2;
    const VALIDATED_MAIL_BUILDER = 3;
    const CUSTOM_MAIL_BUILDER = 4;
    const CONTENT_IMMEDIATE_MAIL_BUILDER = 5;
    const CONTENT_SLEEPING_USER_BUILDER = 6;
    const CONTENT_SUCCESSFUL_CONTENT_BUILDER = 7;
    const CONTENT_SUCCESSFUL_USER_BUILDER = 8;
    const CONTENT_SUGGESTED_LINK_BUILDER = 9;
    const CONTENT_CONTACT_ACCEPTED_BUILDER = 10;
    const NEWSLETTER_BUILDER = 11;
    
    /**
     * @param int $type
     * @param ChangeStatusEmail|null $email
     * @param string|null $endStatus
     * @return ContentMailBuilder|CustomMailBuilder|ValidatedMailBuilder|ValidatorsMailBuilder|null
     */
    public function create($type, $email = null, $endStatus = null)
    {
        $obj = null;
        
        switch ($type) {
            case self::CONTENT_MAIL_BUILDER:
                $obj = new ContentMailBuilder();
                break;
            case self::CONTENT_IMMEDIATE_MAIL_BUILDER:
                $obj = new ContentImmediateMailBuilder();
                break;
            case self::VALIDATORS_MAIL_BUILDER:
                $obj = new ValidatorsMailBuilder();
                break;
            case self::VALIDATED_MAIL_BUILDER:
                $obj = new ValidatedMailBuilder();
                break;
            case self::CUSTOM_MAIL_BUILDER:
                $obj = new CustomMailBuilder(['emailConf' => $email, 'endStatus' => $endStatus]);
                break;
            case self::CONTENT_SLEEPING_USER_BUILDER:
                $obj = new SleepingUserMailBuilder();
                break;
            case self::CONTENT_SUCCESSFUL_CONTENT_BUILDER:
                $obj = new SuccessfulContentMailBuilder();
                break;
            case self::CONTENT_SUCCESSFUL_USER_BUILDER:
                $obj = new SuccessfulUserMailBuilder();
                break;
            case self::CONTENT_SUGGESTED_LINK_BUILDER:
                $obj = new SuggestedLinkMailBuilder();
                break;
            case self::CONTENT_CONTACT_ACCEPTED_BUILDER:
                $obj = new ContactAcceptedMailBuilder();
                break;
            case self::NEWSLETTER_BUILDER:
                $obj = new NewsletterBuilder();
                break;
        }
        
        return $obj;
    }
}
