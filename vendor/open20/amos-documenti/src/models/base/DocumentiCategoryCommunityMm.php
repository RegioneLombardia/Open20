<?php

namespace open20\amos\documenti\models\base;

use open20\amos\documenti\AmosDocumenti;

/**
 * Class DocumentiCategoryCommunityMm
 *
 * This is the base-model class for table "documenti_category_community_mm".
 *
 * @property integer $id
 * @property integer $documenti_categorie_id
 * @property string $community_id
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
class  DocumentiCategoryCommunityMm extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti_category_community_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documenti_categorie_id', 'community_id'], 'required'],
            [['visible_to_cm', 'visible_to_participant', 'documenti_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['community_id'], 'string', 'max' => 255],
            [['documenti_categorie_id'], 'exist', 'skipOnError' => true, 'targetClass' => AmosDocumenti::instance()->model('DocumentiCategorie'), 'targetAttribute' => ['documenti_categorie_id' => 'id']],
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
            'community_id' => AmosDocumenti::t('amosdocumenti', 'Community'),
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
