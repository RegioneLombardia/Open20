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

use open20\amos\notificationmanager\AmosNotify;
use yii\base\BaseObject;

/**
 * Class NotificationsConfOpt
 * @package open20\amos\notificationmanager\models
 */
class NotificationsConfOpt extends BaseObject
{
    const EMAIL_OFF = 0xFFFF;
    const EMAIL_IMMEDIATE = 0x0001;
    const EMAIL_DAY = 0x0002;
    const EMAIL_WEEK = 0x0004;
    const EMAIL_MONTH = 0x0008;
    
    const SMS_IMMEDIATE = NotificationsConfOpt::EMAIL_IMMEDIATE;
    const SMS_DAY = NotificationsConfOpt::EMAIL_DAY;
    const SMS_WEEK = NotificationsConfOpt::EMAIL_WEEK;
    const SMS_MONTH = NotificationsConfOpt::EMAIL_MONTH;
    
    const NEWSLETTER = 0x0010;
    
    /**
     * This method return the email frequencies.
     * @return int[]
     */
    public static function emailFrequencyValues()
    {
        return [
            NotificationsConfOpt::EMAIL_OFF,
            NotificationsConfOpt::EMAIL_IMMEDIATE,
            NotificationsConfOpt::EMAIL_DAY,
            NotificationsConfOpt::EMAIL_WEEK,
            NotificationsConfOpt::EMAIL_MONTH
        ];
    }
    
    /**
     * This method return the email frequency values and labels.
     * @return array
     */
    public static function emailFrequencyValueAndLabels($alsoImmediate = true)
    {
        if ($alsoImmediate){
            return [
                NotificationsConfOpt::EMAIL_OFF => AmosNotify::t('amosnotify', '#never'),
                NotificationsConfOpt::EMAIL_IMMEDIATE => AmosNotify::t('amosnotify', 'Immediate emails'),
                NotificationsConfOpt::EMAIL_DAY => AmosNotify::t('amosnotify', 'Daily emails'),
                NotificationsConfOpt::EMAIL_WEEK => AmosNotify::t('amosnotify', 'Weekly emails'),
                NotificationsConfOpt::EMAIL_MONTH => AmosNotify::t('amosnotify', 'Monthly emails')
            ];        
        } else {
            return [
                NotificationsConfOpt::EMAIL_OFF => AmosNotify::t('amosnotify', '#never'),
                NotificationsConfOpt::EMAIL_DAY => AmosNotify::t('amosnotify', 'Daily emails'),
                NotificationsConfOpt::EMAIL_WEEK => AmosNotify::t('amosnotify', 'Weekly emails'),
                NotificationsConfOpt::EMAIL_MONTH => AmosNotify::t('amosnotify', 'Monthly emails')
            ];        
        }
    }
    
    /**
     * This method return the email frequency label.
     * @param int $frequency
     * @return string
     */
    public static function emailFrequencyLabel($frequency)
    {
        $labelFrequency = [
            NotificationsConfOpt::EMAIL_OFF => AmosNotify::t('amosnotify', 'Never'),
            NotificationsConfOpt::EMAIL_IMMEDIATE => AmosNotify::t('amosnotify', 'Immediate emails'),
            NotificationsConfOpt::EMAIL_DAY => AmosNotify::t('amosnotify', 'Daily emails'),
            NotificationsConfOpt::EMAIL_WEEK => AmosNotify::t('amosnotify', 'Weekly emails'),
            NotificationsConfOpt::EMAIL_MONTH => AmosNotify::t('amosnotify', 'Monthly emails')
        ];
        return $labelFrequency[$frequency];
    }
    
    /**
     * This method return the email frequency values and labels.
     * @return array
     */
    public static function smsFrequencyValueAndLabels()
    {
        return [
            NotificationsConfOpt::SMS_IMMEDIATE => AmosNotify::t('amosnotify', 'Immediate sms'),
            NotificationsConfOpt::SMS_DAY => AmosNotify::t('amosnotify', 'Daily sms'),
            NotificationsConfOpt::SMS_WEEK => AmosNotify::t('amosnotify', 'Weekly sms'),
            NotificationsConfOpt::SMS_MONTH => AmosNotify::t('amosnotify', 'Monthly sms')
        ];
    }
    
    /**
     * This method return the sms frequencies.
     * @return int[]
     */
    public static function smsFrequencyValues()
    {
        return [
            NotificationsConfOpt::SMS_IMMEDIATE,
            NotificationsConfOpt::SMS_DAY,
            NotificationsConfOpt::SMS_WEEK,
            NotificationsConfOpt::SMS_MONTH
        ];
    }
    
    /**
     * This method return the sms frequency label.
     * @param int $frequency
     * @return string
     */
    public static function smsFrequencyLabel($frequency)
    {
        $labelFrequency = [
            NotificationsConfOpt::SMS_IMMEDIATE => AmosNotify::t('amosnotify', 'Immediate sms'),
            NotificationsConfOpt::SMS_DAY => AmosNotify::t('amosnotify', 'Daily sms'),
            NotificationsConfOpt::SMS_WEEK => AmosNotify::t('amosnotify', 'Weekly sms'),
            NotificationsConfOpt::SMS_MONTH => AmosNotify::t('amosnotify', 'Monthly sms')
        ];
        return $labelFrequency[$frequency];
    }
}
