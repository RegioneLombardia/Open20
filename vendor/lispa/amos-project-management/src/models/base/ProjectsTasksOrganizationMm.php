<?php

namespace lispa\amos\projectmanagement\models\base;

use lispa\amos\projectmanagement\Module;
use Yii;

/**
 * This is the base-model class for table "projects_tasks_organization_mm".
 *
 * @property integer $id
 * @property integer $projects_tasks_id
 * @property integer $organization_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \lispa\amos\projectmanagement\models\ProjectsTasks $projectsTasks
 */
class ProjectsTasksOrganizationMm extends \lispa\amos\core\record\Record
{


    public $organizationModelClass;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects_tasks_organization_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projects_tasks_id', 'user_id', 'organization_id'], 'required'],
            [['projects_tasks_id', 'user_id', 'organization_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [
                ['organization_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => $this->organizationModelClass,
                'targetAttribute' => ['organization_id' => 'id']
            ],
            [
                ['projects_tasks_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProjectsTasks::className(),
                'targetAttribute' => ['projects_tasks_id' => 'id']
            ],
        ];
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        try {
            $this->organizationModelClass = Module::getModuleOrganization()->getOrganizationModelClass();
        } catch (\Exception $e) {
            // DO NOTHING (TO WORK AN CONSOLE WHIOUT ORGANIZATIONS!)
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'projects_tasks_id' => 'Projects Tasks ID',
            'organization_id' => 'Organization ID',
            'created_at' => 'Creato il',
            'updated_at' => 'Aggiornato il',
            'deleted_at' => 'Cancellato il',
            'created_by' => 'Creato da',
            'updated_by' => 'Aggiornato da',
            'deleted_by' => 'Cancellato da',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne($this->organizationModelClass,
            ['id' => 'organization_id'])->inverseOf('ProjectsTasksOrganizationMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectsTasks()
    {
        return $this->hasOne(\lispa\amos\projectmanagement\models\ProjectsTasks::className(),
            ['id' => 'projects_tasks_id'])->inverseOf('ProjectsTasksOrganizationMms');
    }
}
