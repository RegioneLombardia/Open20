<?php
namespace console\exceptions; 

use Exception;
use Yii;

/**
 * Undocumented class
 */
class MailupComunicationException extends Exception
{

    /**
     * Example: 
     * (
     *       [ErrorCode] => '500'
     *       [ErrorDescription] => null
     *       [ErrorName] => 'InternalServerError'
     *       [ErrorStack] => null
     *  )
     *  @var stdClass $objError
     */
    private $objError = null;

    public function __construct($message = null)
    {
        $this->message = is_null($message)? Yii::t('campains', 'Errore di comunicazione con MailUp'): $message;
    }

    /**
     * Get the value of objError
     */ 
    public function getObjError()
    {
        return $this->objError;
    }

    /**
     * Set the value of objError
     *
     * @return  self
     */ 
    public function setObjError($objError)
    {
        $this->objError = $objError;

        return $this;
    }
}