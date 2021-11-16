<?php

namespace amos\newsletter\models\base;

use Yii;

/**
* This is the base-model class for table "newsletter_section".
*
    * @property integer $id
    * @property integer $newsletter_id
    * @property string $title
    * @property string $description
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \amos\newsletter\models\Newsletter $newsletter
            * @property \amos\newsletter\models\NewsletterSectionContent[] $newsletterSectionContents
    */
 class  NewsletterSection extends \open20\amos\core\record\Record
{


/**
* @inheritdoc
*/
public static function tableName()
{
return 'newsletter_section';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['newsletter_id'], 'required'],
            [['newsletter_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Newsletter::className(), 'targetAttribute' => ['newsletter_id' => 'id']],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosnewsletter', 'ID'),
    'newsletter_id' => Yii::t('amosnewsletter', 'Template'),
    'title' => Yii::t('amosnewsletter', 'Title'),
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
    public function getNewsletter()
    {
    return $this->hasOne(\amos\newsletter\models\Newsletter::className(), ['id' => 'newsletter_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNewsletterSectionContents()
    {
    return $this->hasMany(\amos\newsletter\models\NewsletterSectionContent::className(), ['newsletter_section_id' => 'id']);
    }
}
