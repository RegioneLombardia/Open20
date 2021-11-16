<?php
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace open20\amos\sondaggi\modules\v1\models;

/**
 * Description of TaskSondaggi
 *
 */
class TaskSondaggi extends \open20\amos\core\record\Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_sondaggi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['status'], 'integer'],
            [['command'], 'string', 'max' => 255],
            [['filename'], 'string', 'max' => 255],

            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by','updated_by','deleted_by'], 'safe']
        ];
    }
}