<?php

namespace lispa\amos\projectmanagement\models\base;

use lispa\amos\projectmanagement\Module;
use Yii;

/**
 * This is the base-model class for table "spending_voices".
 *
 * @property integer $id
 * @property integer $projects_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \lispa\amos\projectmanagement\models\ProjectsProjectsStatesMm[] $projectsProjectsStatesMms
 */
class ProjectsCustomSpendingVoices extends \lispa\amos\core\record\Record
{

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub

        $this->name = $this->spending_voice_parent_id ? $this->parentSpendingVoice->name : $this->name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects_custom_spending_voices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['projects_id', 'spending_voice_parent_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Module::t('amosproject_management', 'Name'),
            'created_at' => Module::t('amosproject_management', 'Creato il'),
            'updated_at' => Module::t('amosproject_management', 'Aggiornato il'),
            'deleted_at' => Module::t('amosproject_management', 'Cancellato il'),
            'created_by' => Module::t('amosproject_management', 'Creato da'),
            'updated_by' => Module::t('amosproject_management', 'Aggiornato da'),
            'deleted_by' => Module::t('amosproject_management', 'Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentSpendingVoice()
    {
        return $this->hasOne(\lispa\amos\projectmanagement\models\ProjectsSpendingVoices::className(),
            ['id' => 'spending_voice_parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasOne(\lispa\amos\projectmanagement\models\Projects::className(),
            ['projects_id' => 'id']);
    }
}
