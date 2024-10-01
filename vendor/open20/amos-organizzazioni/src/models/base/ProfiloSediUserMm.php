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
 * Class ProfiloSediUserMm
 *
 * This is the base-model class for table "profilo_sedi_user_mm".
 *
 * @property integer $id
 * @property integer $profilo_sedi_id
 * @property integer $user_id
 * @property string $status
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\organizzazioni\models\ProfiloSedi $profiloSedi
 * @property \open20\amos\core\user\User $user
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class ProfiloSediUserMm extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profilo_sedi_user_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profilo_sedi_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status', 'role'], 'string', 'max' => 255],
            [['profilo_sedi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Module::instance()->createModel('ProfiloSedi')->className(), 'targetAttribute' => ['profilo_sedi_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amosorganizzazioni', 'ID'),
            'profilo_sedi_id' => Module::t('amosorganizzazioni', 'Sede'),
            'user_id' => Module::t('amosorganizzazioni', 'Utente'),
            'status' => Module::t('amosorganizzazioni', 'Stato'),
            'role' => Module::t('amosorganizzazioni', 'Ruolo'),
            'created_at' => Module::t('amosorganizzazioni', 'Creato il'),
            'updated_at' => Module::t('amosorganizzazioni', 'Aggiornato il'),
            'deleted_at' => Module::t('amosorganizzazioni', 'Cancellato il'),
            'created_by' => Module::t('amosorganizzazioni', 'Creato da'),
            'updated_by' => Module::t('amosorganizzazioni', 'Aggiornato da'),
            'deleted_by' => Module::t('amosorganizzazioni', 'Cancellato da'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloSedi()
    {
        return $this->hasOne(Module::instance()->createModel('ProfiloSedi')->className(), ['id' => 'profilo_sedi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
}
