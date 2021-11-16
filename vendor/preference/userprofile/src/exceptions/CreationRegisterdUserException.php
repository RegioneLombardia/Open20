<?php

namespace preference\userprofile\exceptions;

use Exception;
use Yii;

/**
 * CreationRegisterdUserException class
 */
class CreationRegisterdUserException extends Exception
{

    public function __construct($message = null)
    {
        $this->message = is_null($message)? Yii::t('preferenceuser', 'Impossibile creare il profilo utente'): $message;
    }

}