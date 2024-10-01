<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\models\base;

use open20\amos\cwh\AmosCwh;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "cwh_config".
 *
 * @property integer $id
 * @property string $classname
 * @property string $raw_sql
 * @property string $tablename
 * @property string $visibility
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\cwh\models\CwhNodi[] $cwhNodis
 * @property \open20\amos\cwh\models\CwhPubblicazioni[] $cwhPubblicazionis
 */
class CwhConfig extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwh_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['classname', 'tablename', 'visibility'], 'string', 'max' => 255],
            [['classname'], 'validateClassname'],
            [['tablename'], 'validateTablename'],
//            [['raw_sql'], 'string', 'max' => 4000]
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function validateClassname($attribute, $params, $validator)
    {
        if (class_exists($this->classname)) {
            return true;
        } else {
            $this->addError($attribute, AmosCwh::t('amoscwh', '#class_not_exists'));
            return false;
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function validateTablename($attribute, $params, $validator)
    {


        if ($this->db->schema->getTableSchema($this->tablename, true) === null) {
            $this->addError($attribute, AmosCwh::t('amoscwh', '#table_not_exists'));
            return false;
        } else {
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosCwh::t('amoscwh', 'Id'),
            'classname' => AmosCwh::t('amoscwh', 'Classname'),
            'raw_sql' => AmosCwh::t('amoscwh', 'Sql filtraggio'),
            'tablename' => AmosCwh::t('amoscwh', 'Nome tabella'),
            'visibility' => AmosCwh::t('amoscwh', 'Nerwork visibility condition'),
            'created_at' => AmosCwh::t('amoscwh', 'Creato il'),
            'updated_at' => AmosCwh::t('amoscwh', 'Aggiornato il'),
            'deleted_at' => AmosCwh::t('amoscwh', 'Cancellato il'),
            'created_by' => AmosCwh::t('amoscwh', 'Creato da'),
            'updated_by' => AmosCwh::t('amoscwh', 'Aggiornato da'),
            'deleted_by' => AmosCwh::t('amoscwh', 'Cancellato da'),
            'version' => AmosCwh::t('amoscwh', 'Versione numero'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhNodis()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhNodi::className(), ['cwh_config_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhPubblicazionis()
    {
        return $this->hasMany(\open20\amos\cwh\models\CwhPubblicazioni::className(), ['cwh_config_id' => 'id']);
    }

}
