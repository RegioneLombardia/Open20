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
use yii\helpers\ArrayHelper;

/**
 * Class ProfiloTipoStruttura
 *
 * This is the base-model class for table "profilo_types_pmi".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloTipoStruttura extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profilo_tipologia_struttura';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amosorganizzazioni', 'ID'),
            'name' => Module::t('amosorganizzazioni', 'Tipo Struttura'),
            'created_at' => Module::t('amosorganizzazioni', 'Creato il'),
            'updated_at' => Module::t('amosorganizzazioni', 'Aggiornato il'),
            'deleted_at' => Module::t('amosorganizzazioni', 'Cancellato il'),
            'created_by' => Module::t('amosorganizzazioni', 'Creato da'),
            'updated_by' => Module::t('amosorganizzazioni', 'Aggiornato da'),
            'deleted_by' => Module::t('amosorganizzazioni', 'Cancellato da'),
        ]);
    }
}
