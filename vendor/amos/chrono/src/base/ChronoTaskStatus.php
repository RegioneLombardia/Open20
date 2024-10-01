<?php

namespace amos\chrono\base;

use amos\chrono\Module;

class ChronoTaskStatus
{
    const PENDING = 0;
    const WORKING = 1;
    const ERROR   = 99;
    const END     = 10;

    const ACTIVE = 1;
    const DISABLE = 0;

    public static function getStateLabel($status)
    {
        $ret = "";

        switch ($status) {
            case static::PENDING;
                $ret = Module::t('amoschrono', "In attesa");
                break;
            case static::WORKING;
                $ret = Module::t('amoschrono', "In lavorazione");
                break;
            case static::END;
                $ret = Module::t('amoschrono', "Processata");
                break;
            case static::PENDING;
                $ret = Module::t('amoschrono', "In errore");
                break;
        }

        return $ret;
    }
}