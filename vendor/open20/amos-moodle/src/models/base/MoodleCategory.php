<?php

namespace open20\amos\moodle\models\base;

use open20\amos\community\models\CommunityInterface;
use open20\amos\moodle\AmosMoodle;

use Yii;

/**
 * This is the base-model class for table "moodle_category".
 *
 * @property integer $id
 * @property integer $moodle_categoryid
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class MoodleCategory extends \open20\amos\core\record\Record implements CommunityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'moodle_category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['moodle_categoryid'], 'required'],
            [['moodle_categoryid', 'community_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => AmosMoodle::_t('ID'),
            'moodle_categoryid' => AmosMoodle::_t('Id categoria in Moodle'),
            'community_id' => AmosMoodle::_t('Id Community'),
            'created_at' => AmosMoodle::_t('Creato il'),
            'updated_at' => AmosMoodle::_t('Aggiornato il'),
            'deleted_at' => AmosMoodle::_t('Cancellato il'),
            'created_by' => AmosMoodle::_t('Creato da'),
            'updated_by' => AmosMoodle::_t('Aggiornato da'),
            'deleted_by' => AmosMoodle::_t('Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity() {
        return $this->hasOne(\open20\amos\moodle\models\Community::class, ['id' => 'community_id']);
    }

    // CommunityInterface - start

    /**
     * @inheritdoc
     */
    public function getCommunityId() {
        return $this->community_id;
    }

    /**
     * @inheritdoc
     */
    public function setCommunityId($communityId) {
        $this->community_id = $communityId;
    }

    // CommunityInterface - end
}
