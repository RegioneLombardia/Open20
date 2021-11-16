<?php

namespace open20\amos\events\models;


use yii\base\Model;

class FormAssignSeat extends Model
{
    public $sector;
    public $row;
    public $seat;
    public $event_id;

    public function init()
    {
        parent::init();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['sector','row', 'seat','event_id'],'safe']
        ];
    }

}