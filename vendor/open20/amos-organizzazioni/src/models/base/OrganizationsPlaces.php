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

use open20\amos\core\record\Record;
use open20\amos\organizzazioni\Module;
use yii\helpers\ArrayHelper;

/**
 * Class OrganizationsPlaces
 *
 * This is the base-model class for table "organizzazioni_places".
 *
 * @property string $place_id
 * @property string $place_response
 * @property string $place_type
 * @property string $country
 * @property string $region
 * @property string $province
 * @property string $postal_code
 * @property string $city
 * @property string $address
 * @property string $street_number
 * @property string $latitude
 * @property string $longitude
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class OrganizationsPlaces extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organizzazioni_places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id'], 'required'],
            [['place_response'], 'string'],
            [[
                'place_id',
                'place_type',
                'country',
                'region',
                'province',
                'city',
                'address',
                'latitude',
                'longitude',
                'postal_code',
                'street_number'
            ], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'place_id' => Module::t('amosorganizzazioni', 'Codice recupero place'),
            'place_response' => Module::t('amosorganizzazioni', 'Risposta'),
            'place_type' => Module::t('amosorganizzazioni', 'Tipologia di recupero dati'),
            'country' => Module::t('amosorganizzazioni', 'Paese'),
            'region' => Module::t('amosorganizzazioni', 'Regione'),
            'province' => Module::t('amosorganizzazioni', 'Provincia'),
            'postal_code' => Module::t('amosorganizzazioni', 'CAP'),
            'city' => Module::t('amosorganizzazioni', 'Città'),
            'address' => Module::t('amosorganizzazioni', 'Via/Piazza'),
            'street_number' => Module::t('amosorganizzazioni', 'Numero civico'),
            'latitude' => Module::t('amosorganizzazioni', 'Latitudine'),
            'longitude' => Module::t('amosorganizzazioni', 'Longitudine'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(Module::instance()->model('Profilo'), ['operational_headquarters_place_id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations0()
    {
        return $this->hasMany(Module::instance()->model('Profilo'), ['registered_office_place_id' => 'place_id']);
    }
}
