<?php
/**
 * Aria S.p.A.
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
 * @property integer $target
 * @property integer $type
 * @property integer $filter_type
 * @property integer $search_users
 * @property integer $search_tags
 * @property integer $search_groups
 * @property integer $field
 * @property integer $value
 * @property integer $include_exclude
 * @property integer $invited
 * @property integer $count
 * @property integer $active
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
            [['sondaggi_id', 'target', 'type', 'filter_type','invited'], 'integer'],
            [['name', 'query',], 'string'],
            [['sondaggi_id', 'query', 'target'], 'required'],
            [['sondaggi_id', 'type', 'filter_type','name', 'query', 'search_users', 'search_tags', 'search_groups', 'field', 'value', 'include_exclude','sondaggi_id', 'name', 'query','invited','created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }


    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if (empty($this->name)) $this->name = AmosSondaggi::t('amossondaggi', 'Lista');
        $this->search_tags = json_encode($this->search_tags);
        $this->search_groups = json_encode($this->search_groups);
        $this->search_users = json_encode($this->search_users);
        $this->field = json_encode($this->field);
        $this->value = json_encode($this->value);
        $this->include_exclude = json_encode($this->include_exclude);
        return true;
    }

    public function afterFind(){

        parent::afterFind();

        $this->search_tags = json_decode($this->search_tags, true);
        $this->search_groups = json_decode($this->search_groups, true);
        $this->search_users = json_decode($this->search_users, true);
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
            'target' => AmosSondaggi::t('amossondaggi', 'Target'),
            'count' => AmosSondaggi::t('amossondaggi', 'Conteggio invitati'),
            'active' => AmosSondaggi::t('amossondaggi', 'Attiva'),
            'invited' => AmosSondaggi::t('amossondaggi', 'Invitati'),
            'sondaggi_id' => AmosSondaggi::t('amossondaggi', 'Poll ID'),
            'type' => AmosSondaggi::t('amossondaggi', 'Tipo'),
            'filter_type' => AmosSondaggi::t('amossondaggi', 'Tipo di filtro'),
            'search_users' => AmosSondaggi::t('amossondaggi', 'Utenti per la ricerca'),
            'search_groups' => AmosSondaggi::t('amossondaggi', 'Gruppi per la ricerca'),
            'search_tags' => AmosSondaggi::t('amossondaggi', 'Tag per la ricerca'),
            'field' => AmosSondaggi::t('amossondaggi', 'Campo'),
            'value' => AmosSondaggi::t('amossondaggi', 'Valore'),
            'include_exclude' => AmosSondaggi::t('amossondaggi', 'Include Esclude'),
            'query' => AmosSondaggi::t('amossondaggi', 'Query'),
            'created_at' => AmosSondaggi::t('amossondaggi', 'Creato il'),
            'updated_at' => AmosSondaggi::t('amossondaggi', 'Aggiornato il'),
            'deleted_at' => AmosSondaggi::t('amossondaggi', 'Cancellato il'),
            'created_by' => AmosSondaggi::t('amossondaggi', 'Creato da'),
            'updated_by' => AmosSondaggi::t('amossondaggi', 'Aggiornato da'),
            'deleted_by' => AmosSondaggi::t('amossondaggi', 'Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggi(){
        return $this->hasOne(\open20\amos\sondaggi\models\Sondaggi::className(), ['id' => 'sondaggi_id']);
    }
}
