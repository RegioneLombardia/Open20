<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentTypeRoles;

/**
 * This is the base-model class for table "agid_organizational_unit_content_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $content_type_icon
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class AgidOrganizationalUnitContentType extends \yii\db\ActiveRecord
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_organizational_unit_content_type';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'content_type_icon'], 'string', 'max' => 255],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosorganizationalunit', 'ID'),
            'name' => Yii::t('amosorganizationalunit', 'Name'),
            'description' => Yii::t('amosorganizationalunit', 'Description'),
            'content_type_icon' => Yii::t('amosorganizationalunit', 'Icon'),
            'created_at' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Yii::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Yii::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Yii::t('amosorganizationalunit', 'Created by'),
            'updated_by' => Yii::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Yii::t('amosorganizationalunit', 'Deleted by'),
        ];
    }

    public static function findRedactor()
    {
        $return = AgidOrganizationalUnitContentType::find();
        if (\Yii::$app->getUser()->can('REDACTOR_ORGANIZATIONALUNIT')) {
            $tableName = static::getTableSchema()->name;
            $ids = AgidOrganizationalUnitContentTypeRoles::find()->select('agid_organizational_unit_content_type_id')->andWhere(['user_id' =>\Yii::$app->getUser()->id ])->distinct()->column();
            $return->andWhere([$tableName . '.id' => $ids]);
        }
        return $return;
    }
}
