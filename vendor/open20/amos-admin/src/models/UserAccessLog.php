<?php

namespace open20\amos\admin\models;

use open20\amos\admin\AmosAdmin;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_access_log".
 */
class UserAccessLog extends \open20\amos\admin\models\base\UserAccessLog
{
    const TYPE_LOGIN_STANDARD = 1;
    const TYPE_LOGIN_IDPC = 2;
    const TYPE_LOGIN_IMPERSONATE = 3;


    const ACCESS_METHOD_CNS = 'ARCHIVIO_CARTE';
    const ACCESS_METHOD_EIDAS = 'EIDAS';
    const ACCESS_METHOD_SPID = 'SPID';
    const ACCESS_METHOD_IDM = 'IDM';
    const ACCESS_METHOD_CIE = 'SMARTCARD';
    const ACCESS_METHOD_BASIC_AUTH_STRANIERI = 'UTENTE';
    const ACCESS_METHOD_LOGIN_STANDARD = 'LOGIN_STANDARD';

    public static $impersonator_user_id = null;

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    public function getAccessTypes()
    {
        if ($this->access_type == self::TYPE_LOGIN_IDPC) {
            switch ($this->access_method) {
                case self::ACCESS_METHOD_CNS:
                    $label = AmosAdmin::t('amosadmin', "CNS");
                    break;
                case self::ACCESS_METHOD_EIDAS:
                    $label = AmosAdmin::t('amosadmin', "eIDAS");
                    break;
                case self::ACCESS_METHOD_CIE:
                    $label = AmosAdmin::t('amosadmin', "CIE");
                    break;
                case self::ACCESS_METHOD_SPID:
                    $label = AmosAdmin::t('amosadmin', "SPID");
                    break;
                case self::ACCESS_METHOD_IDM:
                    $label = AmosAdmin::t('amosadmin', "Login unico di Regione Lombardia");
                    break;
                case self::ACCESS_METHOD_BASIC_AUTH_STRANIERI:
                    $label = AmosAdmin::t('amosadmin', "BASIC AUTH stranieri ");
                    break;
                default:
                    $label = AmosAdmin::t('amosadmin', "Altro");
                    break;

            }
        } else {
            $label = AmosAdmin::t('amosadmin', "Basic autentication");
        }
        return $label;
    }

    /**
     * @return array
     */
    public function getAccessTypeLabels()
    {
        return [
            self::ACCESS_METHOD_CNS => AmosAdmin::t('amosadmin', "CNS"),
            self::ACCESS_METHOD_EIDAS => AmosAdmin::t('amosadmin', "eIDAS"),
            self::ACCESS_METHOD_CIE => AmosAdmin::t('amosadmin', "CIE"),
            self::ACCESS_METHOD_SPID => AmosAdmin::t('amosadmin', "SPID"),
            self::ACCESS_METHOD_IDM => AmosAdmin::t('amosadmin', "Login unico di Regione Lombardia"),
            self::ACCESS_METHOD_BASIC_AUTH_STRANIERI => AmosAdmin::t('amosadmin', "BASIC AUTH stranieri "),
            self::ACCESS_METHOD_LOGIN_STANDARD => AmosAdmin::t('amosadmin', "Basic autentication")
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'access_type',
                'label' => $labels['access_type'],
                'type' => 'integer'
            ],
            [
                'slug' => 'access_method',
                'label' => $labels['access_method'],
                'type' => 'string'
            ],
            [
                'slug' => 'access_level',
                'label' => $labels['access_level'],
                'type' => 'string'
            ],
            [
                'slug' => 'enabled',
                'label' => $labels['enabled'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @param int $type
     * @param null $accessMethod
     * @param null $accessLevel
     * @return bool
     */
    public static function saveLog()
    {
        $idpcAccessMethod = \Yii::$app->session->get('idpcAccessMethod');
        $accessLogBeFe = \Yii::$app->session->get('beOrfFe');
        $impersonator = UserAccessLog::$impersonator_user_id;

        $accessLog = new UserAccessLog();
        $accessLog->access_type = self::TYPE_LOGIN_STANDARD;
        $accessLog->access_method = self::ACCESS_METHOD_LOGIN_STANDARD;
        $accessLog->user_id = \Yii::$app->user->id;

        if(!empty($accessLogBeFe)){
            $accessLog->be_or_fe= $accessLogBeFe;
        }

        if(!empty($impersonator)){
            $accessLog->access_type = self::TYPE_LOGIN_IMPERSONATE;
            $accessLog->impersonator_user_id = $impersonator;
            UserAccessLog::$impersonator_user_id = null;

        }else if (!empty($idpcAccessMethod['accessMethod']) && !empty($idpcAccessMethod['accessLevel'])) {

            $accessLog->access_type = self::TYPE_LOGIN_IDPC;
            $accessLog->access_method = $idpcAccessMethod['accessMethod'];
            $accessLog->access_level = $idpcAccessMethod['accessLevel'];
        }
        \Yii::$app->session->remove('idpcAccessMethod');
        \Yii::$app->session->remove('beOrfFe');
        return $accessLog->save(false);

    }


}
