<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models\base
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models\base;

use open20\amos\sondaggi\AmosSondaggi;

/**
 * Class Sondaggi
 *
 * This is the base-model class for table "sondaggi".
 *
 * @property integer $id
 * @property integer $sondaggi_id
 * @property string $name
 * @property string $query
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\sondaggi\models\base
 */
abstract class SondaggiInvitations extends \open20\amos\core\record\Record
{
    const SEARCH_ALL = 0;
    const SEARCH_FILTER = 1;
    const FILTER_GROUPS = 0;
    const FILTER_INVITED_TAG = 1;
    const FILTER_COMPILED_TAG = 2;

    const FILTER_EXCLUDE = 0;
    const FILTER_INCLUDE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_invitations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sondaggi_id', 'type', 'filter_type','invited'], 'integer'],
            [['name', 'query',], 'string'],
            [['sondaggi_id', 'query'], 'required'],
            [['sondaggi_id', 'type', 'filter_type','name', 'query','search_tags', 'search_groups', 'field', 'value', 'include_exclude','sondaggi_id', 'name', 'query','invited','created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }


    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if (empty($this->name)) $this->name = AmosSondaggi::t('amossondaggi', 'Lista');
        $this->search_tags = json_encode($this->search_tags);
        $this->search_groups = json_encode($this->search_groups);
        $this->field = json_encode($this->field);
        $this->value = json_encode($this->value);
        $this->include_exclude = json_encode($this->include_exclude);
        return true;
    }

    public function afterFind(){

        parent::afterFind();

        $this->search_tags = json_decode($this->search_tags, true);
        $this->search_groups = json_decode($this->search_groups, true);
        $this->field = json_decode($this->field, true);
        $this->value = json_decode($this->value, true);
        $this->include_exclude = json_decode($this->include_exclude, true);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosSondaggi::t('amossondaggi', 'ID'),
            'name' => AmosSondaggi::t('amossondaggi', 'Nome'),
            'sondaggi_id' => AmosSondaggi::t('amossondaggi', 'Poll ID'),
            'invitation_class' => AmosSondaggi::t('amossondaggi', 'Classe To'),
            'invitation_id' => AmosSondaggi::t('amossondaggi', 'ID To')
        ];
    }
}
