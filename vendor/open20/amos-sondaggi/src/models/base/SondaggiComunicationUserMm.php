<?php

namespace open20\amos\sondaggi\models\base;

use Yii;

/**
 * This is the base-model class for table "sondaggi_comunication_user_mm".
 *
 * @property integer $id
 * @property integer $sondaggi_id
 * @property integer $user_id
 * @property integer $sondaggi_comunication_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\sondaggi\models\User $user
 */
class SondaggiComunicationUserMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_communication_user_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sondaggi_id', 'user_id', 'sondaggi_comunication_id'], 'required'],
            [['sondaggi_id', 'user_id', 'sondaggi_comunication_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(),
                'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossondaggi', 'ID'),
            'sondaggi_id' => Yii::t('amossondaggi', 'Sondaggi ID'),
            'user_id' => Yii::t('amossondaggi', 'User ID'),
            'sondaggi_comunication_id' => Yii::t('amossondaggi', 'Sondaggi Comunication ID'),
            'created_at' => Yii::t('amossondaggi', 'Creato il'),
            'updated_at' => Yii::t('amossondaggi', 'Aggiornato il'),
            'deleted_at' => Yii::t('amossondaggi', 'Cancellato il'),
            'created_by' => Yii::t('amossondaggi', 'Creato da'),
            'updated_by' => Yii::t('amossondaggi', 'Aggiornato da'),
            'deleted_by' => Yii::t('amossondaggi', 'Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
}