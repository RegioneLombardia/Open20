<?php

namespace amos\newsletter\models\base;

use Yii;

/**
* This is the base-model class for table "newsletter_content_type".
*
    * @property integer $id
    * @property string $name
    * @property string $description
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \amos\newsletter\models\NewsletterSectionContent[] $newsletterSectionContents
    */
 class  NewsletterContentType extends \open20\amos\core\record\Record
{


/**
* @inheritdoc
*/
public static function tableName()
{
return 'newsletter_content_type';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosnewsletter', 'ID'),
    'name' => Yii::t('amosnewsletter', 'Name'),
    'description' => Yii::t('amosnewsletter', 'Description'),
    'created_at' => Yii::t('amosnewsletter', 'Created at'),
    'updated_at' => Yii::t('amosnewsletter', 'Updated at'),
    'deleted_at' => Yii::t('amosnewsletter', 'Deleted at'),
    'created_by' => Yii::t('amosnewsletter', 'Created by'),
    'updated_by' => Yii::t('amosnewsletter', 'Updated at'),
    'deleted_by' => Yii::t('amosnewsletter', 'Deleted at'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNewsletterSectionContents()
    {
    return $this->hasMany(\amos\newsletter\models\NewsletterSectionContent::className(), ['newsletter_content_type_id' => 'id']);
    }
}
