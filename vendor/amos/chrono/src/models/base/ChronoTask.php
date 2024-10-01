<?php

namespace amos\chrono\models\base;

use amos\chrono\Module;
use Cron\CronExpression;
use open20\amos\core\record\Record;
use Exception;
use Yii;

/**
 * This is the base-model class for table "chrono_task".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $schedule
 * @property string $command
 * @property datetime $started_at
 * @property datetime $last_run
 * @property datetime $next_run
 * @property integer $user_id
 * @property integer $status
 * @property integer $active
 * @property integer $overdueThreshold
 *
 */
class ChronoTask extends Record
{
    /**
     * @var Module $module
     */
    protected $module = null;
    public $isSearch  = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module = \Yii::$app->getModule(Module::getModuleName());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chrono_task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'command', 'schedule'],
                'required'],
            [['started_at', 'created_at', 'last_run', 'next_run',
                'updated_at',
                'deleted_at',], 'safe'],
            [['created_by',
                'updated_by',
                'deleted_by'], 'integer'],
            [['user_id',
                'status', 'active', 'overdueThreshold'],
                'integer'],
            [['name', 'command', 'schedule', 'description'], 'string', 'max' => 255],
             ['schedule', 'chronoValidator'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amoschrono', 'ID'),
            'name' => Module::t('amoschrono', '#name'),
            'command' => Module::t('amoschrono', '#command'),
            'schedule' => Module::t('amoschrono', '#schedule'),
            'description' => Module::t('amoschrono', '#description'),
            'started_at' => Module::t('amoschrono', '#started_at'),
            'last_run' => Module::t('amoschrono', '#last_run'),
            'next_run' => Module::t('amoschrono', '#next_run'),
            'user_id' => Module::t('amoschrono', '#user_id'),
        ];
    }

    /**
     *
     * @param string $attribute
     * @param array $params
     * @param mixed $validator
     */
    public function chronoValidator($attribute, $params, $validator)
    {
        try {
            CronExpression::factory($this->schedule);
        } catch (Exception $e) {
            $this->addError($attribute,
                \Yii::t('amoschrono', '#time expression_is_not_valid'));
        }
    }
}