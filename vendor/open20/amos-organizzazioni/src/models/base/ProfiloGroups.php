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
 * Class ProfiloGroups
 *
 * This is the base-model class for table "profilo_groups".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\organizzazioni\models\ProfiloGroupsMm[] $profiloGroupsMms
 * @property \open20\amos\organizzazioni\models\Profilo[] $groupProfilos
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloGroups extends Record
{
    /**
     * @var Module $organizzazioniModule
     */
    protected $organizzazioniModule;
    
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
    public static function tableName()
    {
        return 'profilo_groups';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],
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
    public function getProfiloGroupsMms()
    {
        return $this->hasMany($this->organizzazioniModule->model('ProfiloGroupsMm'), ['profilo_group_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupProfilos()
    {
        return $this->hasMany($this->organizzazioniModule->model('Profilo'), ['id' => 'profilo_id'])->via('profiloGroupsMms');
    }
}
