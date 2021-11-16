<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\base
 * @category   CategoryName
 */

namespace open20\agid\organizationalunit\models\base;

use open20\amos\core\record\Record;
use open20\amos\admin\AmosAdmin;
use open20\agid\organizationalunit\Module;
use Yii;



class AgidOrganizationalUnitContentTypeRoles extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_organizational_unit_content_type_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agid_organizational_unit_content_type_id', 'user_id','role'], 'required'],
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
            'id' => Yii::t('amosorganizationalunit', 'ID'),
            'documenti_agid_type_id' => Yii::t('amosorganizationalunit', 'Documenti agid type ID'),
            'role' => Yii::t('amosorganizationalunit', 'ruolo'),
            'user_id' => Yii::t('amosorganizationalunit', 'User Id'),
            'created_at' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Yii::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Yii::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Yii::t('amosorganizationalunit', 'Created by'),
            'updated_by' => Yii::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Yii::t('amosorganizationalunit', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitContentType()
    {
        return $this->hasOne(Module::instance()->model('AgidOrganizationalUnitContentType'), ['id' => 'agid_organizational_unit_content_type_id']);
    }

    /**
     * @inheritdoc
     */
    public function getUser()
    {
        return $this->hasOne(AmosAdmin::instance()->createModel('User')->className(), ['id' => 'user_id']);
    }
}
