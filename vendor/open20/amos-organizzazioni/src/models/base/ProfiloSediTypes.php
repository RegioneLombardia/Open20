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

/**
 * Class ProfiloSediTypes
 *
 * This is the base-model class for table "profilo_sedi_types".
 *
 * @property integer $id
 * @property string $name
 * @property integer $active
 * @property integer $read_only
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\organizzazioni\models\ProfiloSedi[] $profiloSedi
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloSediTypes extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profilo_sedi_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['active', 'read_only', 'order', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
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
            'active' => Module::t('amosorganizzazioni', '#active_sede_type'),
            'read_only' => Module::t('amosorganizzazioni', 'Read Only'),
            'order' => Module::t('amosorganizzazioni', 'Order'),
            'created_at' => Module::t('amosorganizzazioni', 'Created at'),
            'updated_at' => Module::t('amosorganizzazioni', 'Updated at'),
            'deleted_at' => Module::t('amosorganizzazioni', 'Deleted at'),
            'created_by' => Module::t('amosorganizzazioni', 'Created by'),
            'updated_by' => Module::t('amosorganizzazioni', 'Updated by'),
            'deleted_by' => Module::t('amosorganizzazioni', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloSedi()
    {
        return $this->hasMany(Module::instance()->createModel('ProfiloSedi')->className(), ['profilo_sedi_type_id' => 'id']);
    }
}
