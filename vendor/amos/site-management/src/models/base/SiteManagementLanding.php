<?php

namespace amos\sitemanagement\models\base;

use Yii;

/**
 * This is the base-model class for table "site_management_landing".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $view_path
 * @property string $url_action
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class SiteManagementLanding extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_management_landing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['view_path', 'url_action'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'view_path', 'layout_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossitemanagement', 'ID'),
            'name' => Yii::t('amossitemanagement', 'Name'),
            'description' => Yii::t('amossitemanagement', 'Description'),
            'view_path' => Yii::t('amossitemanagement', 'View Path'),
            'url_action' => Yii::t('amossitemanagement', 'Url action'),
            'created_at' => Yii::t('amossitemanagement', 'Created at'),
            'updated_at' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_at' => Yii::t('amossitemanagement', 'Deleted at'),
            'created_by' => Yii::t('amossitemanagement', 'Created by'),
            'updated_by' => Yii::t('amossitemanagement', 'Updated at'),
            'deleted_by' => Yii::t('amossitemanagement', 'Deleted at'),
        ];
    }
}
