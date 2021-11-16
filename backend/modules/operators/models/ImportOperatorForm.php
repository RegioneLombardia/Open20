<?php

namespace backend\modules\operators\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;

/**
 * This is the model class for table "preference_campain".
 */
class ImportOperatorForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Codice fiscale',
        ];
    }

}