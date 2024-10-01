<?php

namespace open20\amos\utility\models;

use yii\base\Model;

class ImportForm extends Model
{



    public $importType;
    public $nav_container_id = 1;
    public $nav_item_sub_container;
    public $nav_item_id;
    public $versionName;

    public function rules()
    {
        return [
            [['nav_item_sub_container','nav_container_id', 'nav_item_id'], 'integer'],
            [['versionName','importType'], 'safe'],
            [['importType'], 'required'],
        ];
    }

}