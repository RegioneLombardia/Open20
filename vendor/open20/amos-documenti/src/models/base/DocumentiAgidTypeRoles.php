<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\base
 * @category   CategoryName
 */

namespace open20\amos\documenti\models\base;

use open20\amos\core\record\Record;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\admin\AmosAdmin;


/**
 * Class DocumentiCategoryRolesMm
 *
 * This is the base-model class for table "documenti_agid_type_roles".
 *
 * @property integer $id
 * @property integer $documenti_agid_type_id
 * @property integer $user_id
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 *
 * @package open20\amos\documenti\models\base
 */
class DocumentiAgidTypeRoles extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti_agid_type_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documenti_agid_type_id', 'user_id','role'], 'required'],
            [['user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'ID'),
            'documenti_agid_type_id' => AmosDocumenti::t('amosdocumenti', 'Documenti agid type ID'),
            'role' => AmosDocumenti::t('amosdocumenti', 'ruolo'),
            'user_id' => AmosDocumenti::t('amosdocumenti', 'User Id'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Created at'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Updated at'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Deleted at'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Created by'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Updated by'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiAgidType()
    {
        return $this->hasOne(AmosDocumenti::instance()->model('DocumentiAgidType'), ['id' => 'documenti_agid_type_id']);
    }

    /**
     * @inheritdoc
     */
    public function getUser()
    {
        return $this->hasOne(AmosAdmin::instance()->createModel('User')->className(), ['id' => 'user_id']);
    }
}
