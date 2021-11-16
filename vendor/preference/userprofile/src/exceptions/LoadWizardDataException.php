<?php

namespace preference\userprofile\exceptions;

use Exception;
use Yii;

/**
 * Undocumented class
 */
class LoadWizardDataException extends Exception
{

    public function __construct($message = null)
    {
        $this->message = is_null($message)? Yii::t('preferenceuser', 'Impossibile recuperare i dati inseriti'): $message;
    }

}