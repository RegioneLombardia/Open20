<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models\base
 * @category   CategoryName
 */

namespace open20\amos\news\models\base;

use open20\amos\news\AmosNews;
use yii\helpers\ArrayHelper;

/**
 * Class NewsCategoryRolesMm
 * @package open20\amos\news\models\base
 *
 * This is the base-model class for table "news_category_roles_mm".
 *
 * @property    integer $id
 * @property    integer $news_category_id
 * @property    string $role
 * @property    string $created_at
 * @property    string $updated_at
 * @property    string $deleted_at
 * @property    integer $created_by
 * @property    integer $updated_by
 * @property    integer $deleted_by
 *
 * @property \open20\amos\news\models\NewsCategorie $newsCategory
 */
class NewsCategoryRolesMm extends \open20\amos\core\record\Record
{
    /**
     */
    public static function tableName()
    {
        return 'news_category_roles_mm';
    }

    /**
     */
    public function rules()
    {
        return [
            [['news_category_id', 'role'], 'required'],
            [['role'], 'string'],
            [['news_category_id', 'created_by', 'updated_by', 'deleted_by',], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255]
        ];
    }

    /**
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosNews::t('amosnews', 'Id'),
            'news_category_id' => AmosNews::t('amosnews', '#news_category_id'),
            'role' => AmosNews::t('amosnews', '#role'),
        ]);
    }

    /**
     * Relation between news category role mm and category.
     * Returns an ActiveQuery related to model NewsCategorie.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategory()
    {
        return $this->hasOne(\open20\amos\news\models\NewsCategorie::className(), ['id' => 'news_category_id']);
    }

}