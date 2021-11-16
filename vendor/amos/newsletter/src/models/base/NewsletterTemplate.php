<?php

namespace amos\newsletter\models\base;

use Yii;

/**
* This is the base-model class for table "newsletter_template".
*
    * @property integer $id
    * @property string $layout_path
    * @property string $header_section_view_path
    * @property string $footer_section_view_path
    * @property string $view_path
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \amos\newsletter\models\Newsletter[] $newsletters
            * @property \amos\newsletter\models\NewsletterTemplateContent[] $newsletterTemplateContents
    */
 class  NewsletterTemplate extends \open20\amos\core\record\Record
{


/**
* @inheritdoc
*/
public static function tableName()
{
return 'newsletter_template';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['layout_path', 'header_section_view_path', 'footer_section_view_path', 'view_path'], 'string', 'max' => 255],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosnews', 'ID'),
    'layout_path' => Yii::t('amosnews', 'Layout'),
    'header_section_view_path' => Yii::t('amosnews', 'Header'),
    'footer_section_view_path' => Yii::t('amosnews', 'Footer'),
    'view_path' => Yii::t('amosnews', 'View path'),
    'created_at' => Yii::t('amosnews', 'Created at'),
    'updated_at' => Yii::t('amosnews', 'Updated at'),
    'deleted_at' => Yii::t('amosnews', 'Deleted at'),
    'created_by' => Yii::t('amosnews', 'Created by'),
    'updated_by' => Yii::t('amosnews', 'Updated at'),
    'deleted_by' => Yii::t('amosnews', 'Deleted at'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNewsletters()
    {
    return $this->hasMany(\amos\newsletter\models\Newsletter::className(), ['newsletter_template_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNewsletterTemplateContents()
    {
    return $this->hasMany(\amos\newsletter\models\NewsletterTemplateContent::className(), ['newsletter_template_id' => 'id']);
    }
}
