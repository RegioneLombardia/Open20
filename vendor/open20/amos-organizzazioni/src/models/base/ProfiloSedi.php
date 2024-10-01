<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models\base
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\Record;
use open20\amos\organizzazioni\Module;
use yii\helpers\ArrayHelper;

/**
 * Class ProfiloSedi
 *
 * This is the base-model class for table "profilo_sedi".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property integer $is_main
 * @property integer $active
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $pec
 * @property string $address_text
 * @property string $cap_text
 * @property integer $profilo_id
 * @property integer $profilo_sedi_type_id
 * @property integer $country_id
 * @property integer $province_id
 * @property integer $city_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\organizzazioni\models\Profilo $profilo
 * @property \open20\amos\organizzazioni\models\ProfiloSediTypes $profiloSediType
 * @property \open20\amos\organizzazioni\models\ProfiloSediUserMm[] $profiloSediUserMms
 * @property \open20\amos\core\user\User[] $profiloSediUsers
 * @property \open20\amos\comuni\models\IstatNazioni $country
 * @property \open20\amos\comuni\models\IstatProvince $province
 * @property \open20\amos\comuni\models\IstatComuni $city
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloSedi extends Record
{
    /**
     * @var Module $organizzazioniModule
     */
    protected $organizzazioniModule;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profilo_sedi';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->organizzazioniModule = Module::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $requiredFields = [
            'profilo_id',
            'profilo_sedi_type_id',
        ];
        if (
            (($this->is_main == 1) && $this->organizzazioniModule->enableSediRequired) ||
            ($this->is_main == 0)
        ) {
            $requiredFields[] = 'name';
            if ($this->organizzazioniModule->oldStyleAddressEnabled) {
                $requiredFields[] = 'cap_text';
                $requiredFields[] = 'address_text';
                $requiredFields[] = 'country_id';
                $requiredFields[] = 'province_id';
                $requiredFields[] = 'city_id';
            } else {
                $requiredFields[] = 'address';
            }
        }
    
        if (!empty($this->organizzazioniModule->addRequired) && isset($this->organizzazioniModule->addRequired['ProfiloSedi'])) {
            $requiredFields = ArrayHelper::merge($requiredFields, $this->organizzazioniModule->addRequired['ProfiloSedi']);
            $requiredFields = array_unique($requiredFields);
        }

        return [
            [$requiredFields, 'required'],
            [['description'], 'string'],
            [[
                'is_main',
                'active',
                'profilo_id',
                'profilo_sedi_type_id',
                'country_id',
                'province_id',
                'city_id',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [[
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
            [['cap_text'], 'string', 'min' => 5, 'max' => 5],
            [['phone', 'fax'], 'string', 'max' => 50],
            [['name', 'address', 'email', 'pec', 'address_text'], 'string', 'max' => 255],
            [['email', 'pec'], 'email'],
            [['profilo_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->organizzazioniModule->model('Profilo'), 'targetAttribute' => ['profilo_id' => 'id']],
            [['profilo_sedi_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->organizzazioniModule->createModel('ProfiloSediTypes')->className(), 'targetAttribute' => ['profilo_sedi_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosorganizzazioni', 'ID'),
            'name' => Module::t('amosorganizzazioni', 'Name'),
            'description' => Module::t('amosorganizzazioni', 'Description'),
            'address' => Module::t('amosorganizzazioni', 'Address'),
            'is_main' => Module::t('amosorganizzazioni', 'Is Main'),
            'active' => Module::t('amosorganizzazioni', '#active_profilo_sedi'),
            'phone' => Module::t('amosorganizzazioni', 'Phone'),
            'fax' => Module::t('amosorganizzazioni', 'Fax'),
            'email' => Module::t('amosorganizzazioni', '#headquarter_email'),
            'pec' => Module::t('amosorganizzazioni', 'PEC'),
            'address_text' => Module::t('amosorganizzazioni', 'Address'),
            'cap_text' => Module::t('amosorganizzazioni', 'CAP'),
            'profilo_id' => Module::t('amosorganizzazioni', 'Profilo ID'),
            'profilo_sedi_type_id' => Module::t('amosorganizzazioni', 'Headquarter type'),
            'country_id' => Module::t('amosorganizzazioni', 'Country'),
            'province_id' => Module::t('amosorganizzazioni', 'Province'),
            'city_id' => Module::t('amosorganizzazioni', 'City'),
            'created_at' => Module::t('amosorganizzazioni', 'Created at'),
            'updated_at' => Module::t('amosorganizzazioni', 'Updated at'),
            'deleted_at' => Module::t('amosorganizzazioni', 'Deleted at'),
            'created_by' => Module::t('amosorganizzazioni', 'Created by'),
            'updated_by' => Module::t('amosorganizzazioni', 'Updated by'),
            'deleted_by' => Module::t('amosorganizzazioni', 'Deleted by'),

            'profilo' => Module::t('amosorganizzazioni', '#organization'),
            'profiloSediType' => Module::t('amosorganizzazioni', 'Headquarter type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfilo()
    {
        return $this->hasOne($this->organizzazioniModule->model('Profilo'), ['id' => 'profilo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloSediType()
    {
        return $this->hasOne($this->organizzazioniModule->createModel('ProfiloSediTypes')->className(), ['id' => 'profilo_sedi_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloSediUserMms()
    {
        return $this->hasMany($this->organizzazioniModule->createModel('ProfiloSediUserMm')->className(), ['profilo_sedi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloSediUsers()
    {
        return $this->hasMany(AmosAdmin::instance()->model('User'), ['id' => 'user_id'])->via('profiloSediUserMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatNazioni::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatProvince::className(), ['id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatComuni::className(), ['id' => 'city_id']);
    }
}
