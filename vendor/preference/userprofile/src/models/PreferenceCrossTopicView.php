<?php

namespace preference\userprofile\models;

use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for view "preference_cross_topic_view".
 *
 * @property integer $first_id
 * @property integer $cod_topic
 * @property integer $numero
 * @property string $list_cross_ids
 */
class PreferenceCrossTopicView extends ActiveRecord
{

    public static function primaryKey()
    {
        return ['cod_topic'];
    }

    public static function tableName()
    {
        return 'preference_cross_topic_view';
    }

    public function rules()
    {
        return [
            [['list_cross_ids'], 'string'],
            [['first_id', 'cod_topic', 'numero'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }

}
