<?php

namespace amos\newsletter\models\base;

use Yii;

/**
* This is the base-model class for table "newsletter_template_content".
*
    * @property integer $id
    * @property integer $newsletter_template_id
    * @property string $view_path_column_1
    * @property string $view_path_column_2
    * @property string $model_content_classname
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \amos\newsletter\models\NewsletterTemplate $newsletterTemplate
    */
 class  NewsletterTemplateContent extends \open20\amos\core\record\Record
{


/**
* @inheritdoc
*/
public static function tableName()
{
return 'newsletter_template_content';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['newsletter_template_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['view_path_column_1', 'view_path_column_2', 'model_content_classname'], 'string', 'max' => 255],
            [['newsletter_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsletterTemplate::className(), 'targetAttribute' => ['newsletter_template_id' => 'id']],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosnews', 'ID'),
    'newsletter_template_id' => Yii::t('amosnews', 'Template'),
    'view_path_column_1' => Yii::t('amosnews', 'Column 1'),
    'view_path_column_2' => Yii::t('amosnews', 'Column 2'),
    'model_content_classname' => Yii::t('amosnews', 'Model content'),
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
    public function getNewsletterTemplate()
    {
    return $this->hasOne(\amos\newsletter\models\NewsletterTemplate::className(), ['id' => 'newsletter_template_id']);
    }
}
