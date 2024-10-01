<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models\base
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models\base;

use open20\amos\core\record\Record;
use open20\amos\organizzazioni\Module;
use Yii;

/**
 * Class ProfiloEntiType
 *
 * This is the base-model class for table "profilo_enti_type".
 *
 * @property integer $id
 * @property integer $priority
 * @property string $name
 *
 * @property \open20\amos\organizzazioni\models\Profilo[] $profili
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloEntiType extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profilo_enti_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['priority'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosorganizzazioni', 'ID'),
            'name' => Yii::t('amosorganizzazioni', 'Name'),
            'priority' => Yii::t('amosorganizzazioni', 'Priority'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfili()
    {
        return $this->hasMany(Module::instance()->model('Profilo'), ['profilo_enti_type_id' => 'id']);
    }
}
