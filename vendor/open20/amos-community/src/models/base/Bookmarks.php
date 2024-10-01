<?php

namespace open20\amos\community\models\base;

use open20\amos\core\record\ContentModel;
use Yii;
use open20\amos\community\models\Community;
use open20\amos\core\user\User;
use open20\amos\core\record\Record;

/**
 * This is the base-model class for table "bookmarks".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $link
 * @property string $data_pubblicazione
 * @property string $status
 * @property integer $community_id
 * @property integer $creatore_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\community\models\Community $community
 * @property \open20\amos\core\user\User $creatore
 */
abstract class Bookmarks extends ContentModel
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookmarks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titolo', 'link'], 'required'],
            [['data_pubblicazione', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['community_id', 'creatore_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['titolo', 'status', 'link'], 'string', 'max' => 255],
            [['link'], 'url'],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Community::className(), 'targetAttribute' => ['community_id' => 'id']],
            [['creatore_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creatore_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'titolo' => Yii::t('app', 'Titolo'),
            'link' => Yii::t('app', 'Link'),
            'data_pubblicazione' => Yii::t('app', 'Data Pubblicazione'),
            'community_id' => Yii::t('app', 'Community ID'),
            'creatore_id' => Yii::t('app', 'Creatore ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(Community::className(), ['id' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'creatore_id']);
    }

    /**
     * @return string
     */
    public function getUserNames()
    {
        return $this->user->userProfile->nomeCognome;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'titolo',
            'link',
            [
                'label' => $this->getAttributeLabel('status'),
                'value' => function ($model) {
                    return $model->getWorkflowStatusLabel()[$model->status];
                }
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->titolo;
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        return $this->titolo;
    }
}
