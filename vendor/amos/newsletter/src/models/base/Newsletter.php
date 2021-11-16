<?php

namespace amos\newsletter\models\base;

use Yii;

/**
 * This is the base-model class for table "newsletter".
 *
 * @property integer $id
 * @property string $name
 * @property integer $newsletter_template_id
 * @property integer $service_email_list_id
 * @property string $subject
 * @property string $text
 * @property integer $welcome_type
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \amos\newsletter\models\NewsletterTemplate $newsletterTemplate
 * @property \amos\newsletter\models\NewsletterSection[] $newsletterSections
 */
class  Newsletter extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['newsletter_template_id', 'created_by', 'updated_by', 'deleted_by', 'service_email_list_id', 'service_email_group_id','welcome_type'], 'integer'],
            [['name','text'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['newsletter_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsletterTemplate::className(), 'targetAttribute' => ['newsletter_template_id' => 'id']],
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
            'newsletter_template_id' => Yii::t('amosnewsletter', 'Template'),
            'subject' => Yii::t('amosnewsletter', 'Subject'),
            'text' => Yii::t('amosnewsletter', 'Text'),
            'welcome_type' => Yii::t('amosnewsletter', 'Welcome'),
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
    public function getNewsletterTemplate()
    {
        return $this->hasOne(\amos\newsletter\models\NewsletterTemplate::className(), ['id' => 'newsletter_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterSections()
    {
        return $this->hasMany(\amos\newsletter\models\NewsletterSection::className(), ['newsletter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterServiceMailFieldsMm()
    {
        return $this->hasMany(\amos\newsletter\models\NewsletterServiceMailFieldsMm::className(), ['newsletter_id' => 'id']);
    }
}
