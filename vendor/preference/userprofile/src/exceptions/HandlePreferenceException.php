<?php

namespace preference\userprofile\exceptions;

use Exception;
use Yii;

/**
 * HandlePreferenceException class
 */
class HandlePreferenceException extends Exception
{

    public function __construct($message = null)
    {
        $this->message = is_null($message)? Yii::t('preferenceuser', 'Errore nella gestione delle preferenze'): $message;
    }

}