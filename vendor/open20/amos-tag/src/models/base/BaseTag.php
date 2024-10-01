<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag\models\base
 * @category   CategoryName
 */

namespace open20\amos\tag\models\base;

use open20\amos\admin\models\UserProfile;
use open20\amos\cwh\models\CwhTagInterestMm;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\TagModelsAuthItemsMm;
use kartik\tree\models\Tree;

/**
 * Class BaseTag
 *
 * This is the base-model class for table "tag".
 *
 * @property integer $id
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $lvl
 * @property string  $nome
 * @property string  $nome_en
 * @property string  $codice
 * @property string  $codice_en
 * @property string  $descrizione
 * @property string  $descrizione_en
 * @property integer $limit_selected_tag
 * @property string  $icon
 * @property integer $icon_type
 * @property integer $active
 * @property integer $selected
 * @property integer $disabled
 * @property integer $readonly
 * @property integer $visible
 * @property integer $collapsed
 * @property integer $movable_u
 * @property integer $movable_d
 * @property integer $movable_l
 * @property integer $movable_r
 * @property integer $removable
 * @property integer $removable_all
 * @property integer $frequency
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\tag\models\TagModelsAuthItemsMm[] $tagAuthItemsMms
 * @property \open20\amos\cwh\models\CwhTagInterestMm[] $cwhTagInterestMm
 * @property \open20\amos\tag\models\EntitysTagsMm[] $entitysTagsMms
 *
 * @package open20\amos\tag\models\base
 */
class BaseTag extends Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'limit_selected_tag',
                'root',
                'lft',
                'rgt',
                'lvl',
                'icon_type',
                'active',
                'selected',
                'disabled',
                'readonly',
                'visible',
                'collapsed',
                'movable_u',
                'movable_d',
                'movable_l',
                'movable_r',
                'removable',
                'removable_all',
                'frequency',
                'created_by',
                'updated_by',
                'deleted_by',
                'version'
            ], 'integer'],
            // [['lft', 'rgt', 'lvl', 'nome'], 'required'],
            [['descrizione', 'descrizione_en'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nome', 'nome_en', 'codice',], 'string', 'max' => 500],
            [['icon'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosTag::t('amostag', 'ID'),
            'root' => AmosTag::t('amostag', 'Root'),
            'lft' => AmosTag::t('amostag', 'Lft'),
            'rgt' => AmosTag::t('amostag', 'Rgt'),
            'lvl' => AmosTag::t('amostag', 'Lvl'),
            'nome' => AmosTag::t('amostag', 'Nome'),
            'codice' => AmosTag::t('amostag', 'Codice'),
            'descrizione' => AmosTag::t('amostag', 'Descrizione'),
            'limit_selected_tag' => AmosTag::t('amostag', 'Limite di selezione'),
            'icon' => AmosTag::t('amostag', 'Icon'),
            'icon_type' => AmosTag::t('amostag', 'Icon Type'),
            'active' => AmosTag::t('amostag', 'Active'),
            'selected' => AmosTag::t('amostag', 'Selected'),
            'disabled' => AmosTag::t('amostag', 'Disabled'),
            'readonly' => AmosTag::t('amostag', 'Readonly'),
            'visible' => AmosTag::t('amostag', 'Visible'),
            'collapsed' => AmosTag::t('amostag', 'Collapsed'),
            'movable_u' => AmosTag::t('amostag', 'Movable U'),
            'movable_d' => AmosTag::t('amostag', 'Movable D'),
            'movable_l' => AmosTag::t('amostag', 'Movable L'),
            'movable_r' => AmosTag::t('amostag', 'Movable R'),
            'removable' => AmosTag::t('amostag', 'Removable'),
            'removable_all' => AmosTag::t('amostag', 'Removable All'),
            'frequency' => AmosTag::t('amostag', 'Frequency'),
            'created_at' => AmosTag::t('amostag', 'Creato il'),
            'updated_at' => AmosTag::t('amostag', 'Aggiornato il'),
            'deleted_at' => AmosTag::t('amostag', 'Cancellato il'),
            'created_by' => AmosTag::t('amostag', 'Creato da'),
            'updated_by' => AmosTag::t('amostag', 'Aggiornato da'),
            'deleted_by' => AmosTag::t('amostag', 'Cancellato da'),
            'version' => AmosTag::t('amostag', 'Versione numero'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(
            UserProfile::class,
            ['id' => 'user_profile_id']
        )
        ->viaTable('user_profile_tag_mm', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagModelsAuthItems()
    {
        return $this->hasMany(
            TagModelsAuthItemsMm::class,
            ['tag_id' => 'id']
        )
        ->inverseOf('tag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhTagInterestMm()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            return $this->hasMany(
                CwhTagInterestMm::class,
                ['tag_id' => 'id']
            )
            ->inverseOf('tag');
        }

        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitysTagsMms()
    {
        return $this->hasMany(
            EntitysTagsMm::class,
            ['tag_id' => 'id']
        );
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCwhConfig()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if($moduleCwh) 
        {
            return $this->hasOne(\open20\amos\cwh\models\CwhConfig::className(), ['id' => 'cwh_config_id']);
        }
        return null;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetwork()
    {
        $network = null;
        
        $config = $this->cwhConfig;
        if(!is_null($config))
        {
            $network = $this->hasOne($config->classname, ['id' => 'network_record_id']);
        }
        return $network;
    }
}
