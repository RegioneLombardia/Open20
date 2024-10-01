<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\base
 * @category   CategoryName
 */

namespace open20\amos\documenti\models\base;

use open20\amos\core\record\Record;
use open20\amos\documenti\AmosDocumenti;

/**
 * Class DocumentiCategoryRolesMm
 *
 * This is the base-model class for table "documenti_category_roles_mm".
 *
 * @property integer $id
 * @property integer $documenti_categorie_id
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\DocumentiCategorie $documentiCategorie
 *
 * @package open20\amos\documenti\models\base
 */
class DocumentiCategoryRolesMm extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti_category_roles_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documenti_categorie_id', 'role'], 'required'],
            [['documenti_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255],
            [
                ['documenti_categorie_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AmosDocumenti::instance()->model('DocumentiCategorie'),
                'targetAttribute' => ['documenti_categorie_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'ID'),
            'documenti_categorie_id' => AmosDocumenti::t('amosdocumenti', 'Documenti Category ID'),
            'role' => AmosDocumenti::t('amosdocumenti', 'Community'),
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
    public function getDocumentiCategorie()
    {
        return $this->hasOne(AmosDocumenti::instance()->model('DocumentiCategorie'), ['id' => 'documenti_categorie_id']);
    }
}
