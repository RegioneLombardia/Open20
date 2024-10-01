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
 * Class ProfiloGroupsMm
 *
 * This is the base-model class for table "profilo_groups_mm".
 *
 * @property integer $id
 * @property integer $profilo_group_id
 * @property integer $profilo_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\organizzazioni\models\ProfiloGroupsMm[] $profiloGroupsMms
 * @property \open20\amos\organizzazioni\models\Profilo[] $groupsProfilos
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloGroupsMm extends Record
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
        return 'profilo_groups_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profilo_group_id', 'profilo_id'], 'required'],
            [['profilo_group_id', 'profilo_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['profilo_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->organizzazioniModule->model('ProfiloGroups'), 'targetAttribute' => ['ente_id' => 'id']],
            [['profilo_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->organizzazioniModule->model('Profilo'), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosorganizzazioni', 'ID'),
            'profilo_group_id' => Module::t('amosorganizzazioni', 'Gruppo ID'),
            'profilo_id' => Module::t('amosorganizzazioni', 'Profilo ID'),
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
    public function getProfiloGroup()
    {
        return $this->hasOne($this->organizzazioniModule->model('ProfiloGroups'), ['id' => 'profilo_group_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfilo()
    {
        return $this->hasOne($this->organizzazioniModule->model('Profilo'), ['id' => 'profilo_id']);
    }
}
