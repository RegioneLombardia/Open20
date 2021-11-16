<?php

namespace amos\sitemanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_management_landing_pubblication".
 */
class SiteManagementLandingPubblication extends \amos\sitemanagement\models\base\SiteManagementLandingPubblication
{
    public function init(){
        parent::init();
        if(empty($this->expire_days_cookie)){
            $module = \Yii::$app->getModule('sitemanagement');
            if($module){
                $this->expire_days_cookie = $module->expireDaysLandingCookie;
            }
        }
    }

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


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'url',
                'label' => $labels['url'],
                'type' => 'string'
            ],
            [
                'slug' => 'landing_id',
                'label' => $labels['landing_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'start_date',
                'label' => $labels['start_date'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'end_date',
                'label' => $labels['end_date'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'start_time',
                'label' => $labels['start_time'],
                'type' => 'time'
            ],
            [
                'slug' => 'end_time',
                'label' => $labels['end_time'],
                'type' => 'datetime'
            ],
        ];
    }
}
