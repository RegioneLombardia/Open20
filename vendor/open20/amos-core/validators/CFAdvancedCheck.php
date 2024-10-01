<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\validators
 * @category   CategoryName
 */

namespace backend\validators;

use open20\amos\comuni\models\IstatComuni;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\validators\Validator;
use open20\amos\core\module\BaseAmosModule;

/**
 * Class CFAdvancedCheck
 * @package backend\validators
 * Permette di verificare la validita' del codice fiscale inserito nel form
 * sulla base dei dati identificativi inseriti.
 */
class CFAdvancedCheck extends Validator
{

    /**
     * @var $nomeField, $cognomeField, $dataNascitaField, $luogoNascitaField
     * I nomi dei campi da richiamare nel model per effettuare il controllo di validita'
     * effettiva tra i dati da inserire nel db e il cf inserito
     */
    public $nomeField, $cognomeField, $sessoField, $dataNascitaField, $comuniTable, $luogoNascitaField, $dataAttribute, $dateFormat;
    public $sessoM, $sessoF;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        /**
         * Se in fase di richiamo del validatore non vengono definiti esplicitamente
         * i campi del model da utilizzare, vengono settati dei campi predefiniti
         */
        if(!isset($this->nomeField)){
            $this->nomeField = 'nome';
        }
        if(!isset($this->cognomeField)){
            $this->cognomeField = 'cognome';
        }
        if(!isset($this->sessoField)){
            $this->sessoField = 'sesso';
        }
        if(!isset($this->dataNascitaField)){
            $this->dataNascitaField = 'data_nascita';
        }
        if(!isset($this->luogoNascitaField)){
            $this->luogoNascitaField = 'istat_comune_nascita';
        }
        if(!isset($this->dateFormat)){
            $this->dateFormat = 'Y-m-d';
        }
        if(!isset($this->sessoM)){
            $this->sessoM = 'M';
        }
        if(!isset($this->sessoF)){
            $this->sessoF = 'F';
        }
        if(!isset($this->comuniTable)) {
            $this->comuniTable = 'istat_comuni';
        }

    }


    public function validateAttribute($model, $attribute)
    {
        $theVar = strtoupper($model->$attribute);

        $nome = $this->nomeField;
        $cognome = $this->cognomeField;
        $luogoNascita = $this->luogoNascitaField;
        $sesso = $this->sessoField;
        $dataNascita = $this->dataNascitaField;

        $errorMessage = "";

        /**
         * Lista per sostituzione caratteri accentati
         */
        $unwanted_array = array(    ' ' => '', '\'' => '', 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Ǎ'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

        if(isset($this->dataAttribute)) {
            $dataAttribute = $this->dataAttribute;
            $nomeValue = trim(strtr($model->$dataAttribute->$nome, $unwanted_array));
            $cognomeValue = trim(strtr($model->$dataAttribute->$cognome, $unwanted_array));
            $dataNascitaValue = trim(strtotime($model->$dataAttribute->$dataNascita));
            $luogoNascitaValue = trim($model->$dataAttribute->$luogoNascita);
            $sessoValue = trim($model->$dataAttribute->$sesso);
        } else {
            $nomeValue = trim(strtr($model->$nome, $unwanted_array));
            $cognomeValue = trim(strtr($model->$cognome, $unwanted_array));
            $dataNascitaValue = trim(strtotime($model->$dataNascita));
            $luogoNascitaValue = trim($model->$luogoNascita);
            $sessoValue = trim($model->$sesso);
        }

        /**
         * Controllo della lunghezza del cf che si vuole inserire.
         * Se la lunghezza non e' conforme, viene mostrato un messaggio di errore.
         */
        $isLengthOk = false;
        if (strlen($theVar) != 16) {
            $isLengthOk = true;
        }
        if (strlen($theVar) != 11) {
            $isLengthOk = true;
        }
        if (!$isLengthOk) {
            $this->addError($model, $attribute, 'Codice Fiscale non valido - lunghezza non conforme');
            return false;
        }

        /**
         * Se il cf inserito e' lungo 16 caratteri, e' un cf definitivo quindi controllo validita'
         * del carattere di controllo e degli altri sulla base dei dati presenti nel model
         */
        if (strlen($theVar) == 16) {

            /**
             * Controllo se sono presenti caratteri non alfanumerici nel cf inserito
             */
            if (!preg_match("/^[A-Z0-9]+$/i", $theVar)) {
                $this->addError($model, $attribute, 'Codice Fiscale non valido');
                return false;
            }

            /**
             * Controllo validita' del codice di controllo
             */
            $s = 0;
            for ($i = 1; $i <= 13; $i += 2) {
                $c = $theVar[$i];
                if ('0' <= $c && $c <= '9') {
                    $s += ord($c) - ord('0');
                } else {
                    $s += ord($c) - ord('A');
                }
            }
            for ($i = 0; $i <= 14; $i += 2) {
                $c = $theVar[$i];
                switch ($c) {
                    case '0':
                        $s += 1;
                        break;
                    case '1':
                        $s += 0;
                        break;
                    case '2':
                        $s += 5;
                        break;
                    case '3':
                        $s += 7;
                        break;
                    case '4':
                        $s += 9;
                        break;
                    case '5':
                        $s += 13;
                        break;
                    case '6':
                        $s += 15;
                        break;
                    case '7':
                        $s += 17;
                        break;
                    case '8':
                        $s += 19;
                        break;
                    case '9':
                        $s += 21;
                        break;
                    case 'A':
                        $s += 1;
                        break;
                    case 'B':
                        $s += 0;
                        break;
                    case 'C':
                        $s += 5;
                        break;
                    case 'D':
                        $s += 7;
                        break;
                    case 'E':
                        $s += 9;
                        break;
                    case 'F':
                        $s += 13;
                        break;
                    case 'G':
                        $s += 15;
                        break;
                    case 'H':
                        $s += 17;
                        break;
                    case 'I':
                        $s += 19;
                        break;
                    case 'J':
                        $s += 21;
                        break;
                    case 'K':
                        $s += 2;
                        break;
                    case 'L':
                        $s += 4;
                        break;
                    case 'M':
                        $s += 18;
                        break;
                    case 'N':
                        $s += 20;
                        break;
                    case 'O':
                        $s += 11;
                        break;
                    case 'P':
                        $s += 3;
                        break;
                    case 'Q':
                        $s += 6;
                        break;
                    case 'R':
                        $s += 8;
                        break;
                    case 'S':
                        $s += 12;
                        break;
                    case 'T':
                        $s += 14;
                        break;
                    case 'U':
                        $s += 16;
                        break;
                    case 'V':
                        $s += 10;
                        break;
                    case 'W':
                        $s += 22;
                        break;
                    case 'X':
                        $s += 25;
                        break;
                    case 'Y':
                        $s += 24;
                        break;
                    case 'Z':
                        $s += 23;
                        break;
                }
            }
            if (chr($s % 26 + ord('A')) != $theVar[15]) {
                $this->addError($model, $attribute, BaseAmosModule::t('amosapp', 'Codice Fiscale non valido'));
                return false;
            }

            $isCfOk = true;

            $vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
            $consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "y", "z", "B", "C", "D", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "V", "W", "X", "Y", "Z");

            /**
             * Controllo corrispondenza con cognome inserito
             */
            $surname = str_replace($vowels, "", $cognomeValue);
            if(strlen($surname) < 3) {
                $surname .= str_replace($consonants, "", $cognomeValue);
            }

            if(strlen($surname) >= 3) {
                $surname = substr($surname, 0, 3);
            }
            else {
                while(strlen($surname) < 3) {
                    $surname .= "X";
                }
            }

            if(strtoupper($surname) != substr($theVar,0, 3)) {
                $isCfOk = false;
                $errorMessage .= BaseAmosModule::t('amosapp', "cognome").', ';
            }

            /**
             * Controllo corrispondenza con nome inserito
             */
            $name = str_replace($vowels, "", $nomeValue);
            if(strlen($name) >= 4) {
                $name = $name[0].substr($name, 2, 2);
            }
            else {
                if(strlen($name) < 3) {
                    $name .= str_replace($consonants, "", $nomeValue);
                }

                if(strlen($name) == 3) {
                    $name = substr($name, 0, 3);
                }
                else {
                    while(strlen($name) < 3) {
                        $name .= "X";
                    }
                }
            }

            if(strtoupper($name) != substr($theVar,3, 3)) {
                $isCfOk = false;
                $errorMessage .= BaseAmosModule::t('amosapp', "nome").', ';
            }

            /**
             * Controllo corrispondenza con data di nascita inserita
             */

            // Anno di nascita per cf
            $birthDate = date("y", $dataNascitaValue);

            // Mese di nascita per cf
            $mesi = array("01" => "A", "02" => "B", "03" => "C", "04" => "D", "05" => "E", "06" => "H", "07" => "L", "08" => "M", "09" => "P", "10" => "R", "11" => "S", "12" => "T");
            $birthDate .= $mesi[date("m", $dataNascitaValue)];

            // Giorno di nascita per cf
            $day = date("d", $dataNascitaValue) + (($sessoValue == $this->sessoM) ? 0 : 40);
            $dayInverse = date("d", $dataNascitaValue) + (($sessoValue == $this->sessoF) ? 0 : 40);
            $birthDateInverse = $birthDate . (($dayInverse <= 9) ? "0".$dayInverse : $dayInverse);
            $birthDate .= ($day <= 9) ? "0".$day : $day;

            if($birthDateInverse == substr($theVar,6, 5)) {
                $isCfOk = false;
                $errorMessage .= BaseAmosModule::t('amosapp', "sesso").', ';
            } else {
                if ($birthDate != substr($theVar, 6, 5)) {
                    $isCfOk = false;
                    $errorMessage .= BaseAmosModule::t('amosapp', "data di nascita") . ', ';
                }
            }

            /**
             * Controllo corrispondenza con codice catastale inserito
             */

            $dbConn = \Yii::$app->getDb();
            $codice_catastale = $dbConn->createCommand(<<<SQL
              SELECT codice_catastale
              FROM {$this->comuniTable}
              WHERE id = {$luogoNascitaValue}
SQL
            )->queryOne()['codice_catastale'];

            if($codice_catastale != substr($theVar,11, 4)) {
                $isCfOk = false;
                $errorMessage .= BaseAmosModule::t('amosapp', "luogo di nascita").', ';
            }

            if(!$isCfOk){
                $errorMsg = ucfirst($errorMessage);
                if(substr_count($errorMsg, ",") > 1) {
                    $errorMsg = substr_replace($errorMsg, " ", strrpos($errorMsg, ", "), 1);
                    $errorMsg .= BaseAmosModule::t('amosapp', "non corrispondono con il codice fiscale inserito");
                } else {
                    $errorMsg = substr_replace($errorMsg, " ", strrpos($errorMsg, ", "), 1);
                    $errorMsg .= BaseAmosModule::t('amosapp', "non corrisponde con il codice fiscale inserito");
                }
                $this->addError($model, $attribute, $errorMsg);
            }

            return $isCfOk;
        }

        /**
         * Se il cf inserito e' lungo 11 caratteri, e' un cf provvisorio quindi viene controllata solo
         * la validita' del carattere di controllo
         */
        if (strlen($theVar) == 11) {
            if (!preg_match("/^[0-9]+$/i", $theVar)) {
                $this->addError($model, $attribute, 'Codice Fiscale non valido');
                return false;
            }
        }

    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param \yii\web\View $view
     * @return string
     * Validator CF client side
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $error_msg = BaseAmosModule::t('amosapp', 'Codice Fiscale non valido');
        $error_format_msg = BaseAmosModule::t('amosapp', 'Il codice fiscale deve contenere 16 tra lettere e cifre o 11 cifre');
        return <<<JS
        
        var cf = value.toUpperCase();
        
        if( cf == '' ){
            return '';
        } 
        
        var isLengthOk = false;
        if(/^[0-9A-Z]{16}$/.test(cf)) {
            isLengthOk = true;
        }
        if(/^[0-9]{11}$/.test(cf)) {
            isLengthOk = true;
        }
        
        if(!isLengthOk){
            messages.push( "$error_format_msg");
        } else {
            if(cf.length === 16) {
                var map = [1, 0, 5, 7, 9, 13, 15, 17, 19, 21, 1, 0, 5, 7, 9, 13, 15, 17, 19, 21, 2, 4, 18, 20, 11, 3, 6, 8, 12, 14, 16, 10, 22, 25, 24, 23];
                
                var s = 0;
                for(var i = 0; i < 15; i++){
                    var c = cf.charCodeAt(i);
                    if( c < 65 ){
                        c = c - 48;
                    }
                    else{
                        c = c - 55;
                    }
                    if( i % 2 == 0 ){
                        s += map[c];
                    }
                    else{
                        s += c < 10? c : c - 10;
                    }
                }
                var atteso = String.fromCharCode(65 + s % 26);
                if( atteso != cf.charAt(15) ){
                     messages.push( "$error_msg");
                }
                return true;
            }
            if(cf.length === 11) {
                var s = 0;
                for( i = 0; i <= 9; i += 2 ){
                    s += cf.charCodeAt(i) - '0'.charCodeAt(0);
                }
                for(var i = 1; i <= 9; i += 2 ){
                    var c = 2*( cf.charCodeAt(i) - '0'.charCodeAt(0) );
                    if( c > 9 )  c = c - 9;
                    s += c;
                }
                var atteso = ( 10 - s%10 )%10;
                if( atteso != cf.charCodeAt(10) - '0'.charCodeAt(0) ){
                    messages.push("$error_msg");
                }
                return true;
            }
        }
JS;


    }

}