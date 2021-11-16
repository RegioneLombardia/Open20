<?php

namespace backend\modules\userimporter\base;

use backend\modules\userimporter\Module;

class UserImportTaskStatus
{
    const PENDING = 0;
    const WORKING = 1;
    const ERROR   = 99;
    const END     = 10;

    const STATUS_USERS_TO_IMPORT = 'user_to_import';
    const STATUS_IMPORTING_USERS = 'importing_users';
    const STATUS_IMPORTED = 'imported';
    const STATUS_TO_SEND = 'to_send';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_NO_USER_TO_IMPORT = 'no_user_to_import';
    const STATUS_ERROR = 'error';

    public static function getStateLabel($status)
    {
        $ret = "";

        switch ($status) {
            case static::STATUS_USERS_TO_IMPORT;
                $ret = Module::t('amosuserimporter', "Da processare");
                break;
            case static::STATUS_IMPORTING_USERS;
                $ret = Module::t('amosuserimporter', "In preparazione");
                break;
            case static::STATUS_IMPORTED;
                $ret = Module::t('amosuserimporter', "In Coda");
                break;
            case static::STATUS_TO_SEND;
                $ret = Module::t('amosuserimporter', "Da inviare");
                break;
            case static::STATUS_SENDING;
                $ret = Module::t('amosuserimporter', "In invio");
                break;
            case static::STATUS_SENT;
                $ret = Module::t('amosuserimporter', "Invio completato");
                break;
            case static::STATUS_NO_USER_TO_IMPORT;
                $ret = Module::t('amosuserimporter', "Nessun utente da invitare");
                break;
            case static::STATUS_ERROR;
                $ret = Module::t('amosuserimporter', "In errore");
                break;
//---------
            case static::PENDING;
                $ret = Module::t('amosuserimporter', "#waiting");
                break;
            case static::WORKING;
                $ret = Module::t('amosuserimporter', "#working");
                break;
            case static::END;
                $ret = Module::t('amosuserimporter', "#processed");
                break;
            case static::ERROR;
                $ret = Module::t('amosuserimporter', "#error_import");
                break;


        }

        return $ret;
    }
}