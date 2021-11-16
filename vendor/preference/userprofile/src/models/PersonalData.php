<?php
namespace preference\userprofile\models;

use open20\amos\comuni\models\IstatComuni;
use open20\amos\comuni\models\IstatProvince;
use preference\userprofile\utility\TopicTagUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Model without table
 * 
 */
class PersonalData extends Model
{
    public $name;
    public $surname;
    public $gender;
    public $birth_date;
    public $residence_city;
    public $residence_province;
    public $email;
    public $fiscal_code;

    private $genderChoices = [
        'm' => 'M',
        'f' => 'F',
    ];

    public function rules()
    {
        return [
            [['gender'], 'checkGender'],
            [['name', 'surname'], 'string', 'max' => 255],
            [['birth_date'], 'date', 'format' => 'd/m/Y'],
            [['email'], 'email'],
            [['residence_province', 'residence_city'], 'integer'],
            [['residence_province'], 'checkProvince'],
            [['residence_city'], 'checkCity'],
            // ['fiscal_code', 'required'],
            ['fiscal_code', 'checkCodiceFiscale'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('preferenceuser', 'Nome'),
            'surname' => Yii::t('preferenceuser', 'Cognome'),
            'gender' => Yii::t('preferenceuser', 'Sesso'),
            'email' => Yii::t('preferenceuser', 'Email'),
            'birth_date' => Yii::t('preferenceuser', 'Data di nascita'),
            'residence_province' => Yii::t('preferenceuser', 'Provincia'),
            'residence_city' => Yii::t('preferenceuser', 'Comune'),
            'fiscal_code' => Yii::t('preferenceuser', 'Codice fiscale'),
        ];
    }

    public function getGenderChoices() 
    {
        return $this->genderChoices;
    }

    public function checkGender($attribute, $params)
    {
        if (!is_null($this->$attribute)) {
            switch ($this->$attribute) {
                case 'm':
                case 'f':
                case null:
                break;

                default: $this->addError($attribute, Yii::t('preferenceuser', 'Valore per il campo sesso non previsto'));
            }
            
        }
    }

    public function checkProvince($attribute, $params)
    {
        if (!is_null($this->$attribute)) {
            $p = IstatProvince::findOne(['id' => $this->$attribute]);
            if (empty($p)) {
                $this->addError($attribute, Yii::t('preferenceuser', 'Provincia non trovata'));
            }
        }
    }

    public function checkCity($attribute, $params)
    {
        if (!is_null($this->$attribute)) {
            $p = IstatComuni::findOne(['id' => $this->$attribute]);
            if (empty($p)) {
                $this->addError($attribute, Yii::t('preferenceuser', 'Comune non trovato'));
            }
        }
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function checkCodiceFiscale($attribute, $params)
    {
        $codiceFiscale = $this->$attribute;
        if (!$codiceFiscale) {
            $isValid = true;
        } // se non può essere null se ne deve occupare qualcun altro
        if (strlen($codiceFiscale) != 16) {
            $isValid = false;
        } else {
            $codiceFiscale = strtoupper($codiceFiscale);
            if (!preg_match("/^[A-Z0-9]+$/", $codiceFiscale)) {
                $isValid = false;
            }
            $s = 0;
            for ($i = 1; $i <= 13; $i += 2) {
                $c = $codiceFiscale[$i];
                if ('0' <= $c && $c <= '9') $s += ord($c) - ord('0');
                else $s += ord($c) - ord('A');
            }
            for ($i = 0; $i <= 14; $i += 2) {
                $c = $codiceFiscale[$i];
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
            if (isset($codiceFiscale[15])) {

                if (chr($s % 26 + ord('A')) != $codiceFiscale[15]) {
                    $isValid = false;
                } else {
                    $isValid = true;
                }
            }
        }
        if (!$isValid) {
            $this->addError($attribute, Yii::t('preferenceuser', 'Il codice fiscale non è in un formato consentito'));
        }
    }


}