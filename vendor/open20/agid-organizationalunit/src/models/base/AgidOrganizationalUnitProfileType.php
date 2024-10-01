<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;

/**
 * This is the base-model class for table "agid_person_content_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\person\models\AgidOrganizationalUnitProfileType[] $agidPeople
 */
abstract class AgidOrganizationalUnitProfileType extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_organizational_unit_profile_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosorganizationalunit', 'ID'),
            'name' => Yii::t('amosorganizationalunit', 'Tipologia di profilo'),
            'created_at' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Yii::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Yii::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Yii::t('amosorganizationalunit', 'Created by'),
            'updated_by' => Yii::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Yii::t('amosorganizationalunit', 'Deleted by'),
        ];
    }
}



