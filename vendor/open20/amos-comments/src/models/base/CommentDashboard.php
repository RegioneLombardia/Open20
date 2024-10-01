<?php

namespace open20\amos\comments\models\base;

use Yii;

/**
 * This is the base-model class for table "comment_dashboard".
 *
 * @property integer $id
 * @property integer $netiquette_platform
 * @property integer $netiquette_communities
 * @property integer $moderation_platform
 * @property integer $moderation_communities
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  CommentDashboard extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_dashboard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'netiquette_platform',
                'netiquette_communities',
                'moderation_platform',
                'moderation_communities',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [[
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'netiquette_platform' => 'Enable netiquette for platform plugins',
            'netiquette_communities' => 'Enable netiquette for community contents',
            'moderation_platform' => 'Enable comments moderation for platform plugins',
            'moderation_communities' => 'Enable comments moderation for community contents',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
            'created_by' => 'Created by',
            'updated_by' => 'Updated by',
            'deleted_by' => 'Deleted by',
        ];
    }
}
