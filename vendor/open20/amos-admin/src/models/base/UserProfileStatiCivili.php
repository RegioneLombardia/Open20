<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models\base
 * @category   CategoryName
 */

namespace open20\amos\admin\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\AmosRecordAudit;
use Yii;

/**
 * This is the base-model class for table "user_profile_stati_civili".
 *
 * @property integer $id
 * @property string $nome
 * @property string $descrizione
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\UserProfile[] $userProfiles
 */
class UserProfileStatiCivili extends AmosRecordAudit
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_stati_civili';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['descrizione'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nome'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosAdmin::t('amosadmin', 'ID'),
            'nome' => AmosAdmin::t('amosadmin', 'Nome'),
            'descrizione' => AmosAdmin::t('amosadmin', 'Descrizione'),
            'created_at' => AmosAdmin::t('amosadmin', 'Creato il'),
            'updated_at' => AmosAdmin::t('amosadmin', 'Aggiornato il'),
            'deleted_at' => AmosAdmin::t('amosadmin', 'Cancellato il'),
            'created_by' => AmosAdmin::t('amosadmin', 'Creato da'),
            'updated_by' => AmosAdmin::t('amosadmin', 'Aggiornato da'),
            'deleted_by' => AmosAdmin::t('amosadmin', 'Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(AmosAdmin::instance()->createModel('UserProfile')->className(), ['user_profile_stati_civili_id' => 'id'])->inverseOf('userProfileStatiCivili');
    }
}
