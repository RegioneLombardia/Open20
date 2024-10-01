<?php

namespace open20\amos\moodle\models\base;

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\MoodleCategory;

use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityInterface;

use Yii;

/**
 * This is the base-model class for table "moodle_course".
 *
 * @property integer $id
 * @property integer $moodle_courseid
 * @property integer $moodle_categoryid
 * @property integer $community_id
 * @property string $enrollment_methods
 * @property string $course_id
 * @property string $student_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class MoodleCourse
    extends \open20\amos\core\record\Record 
    implements CommunityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'moodle_course';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['moodle_courseid'], 'required'],
            [['moodle_courseid', 'community_id', 'moodle_categoryid', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [
                [
                    'enrollment_methods',
                    'course_id',
                    'student_id',
                    'created_at', 'updated_at', 'deleted_at'
                ], 
                'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => AmosMoodle::_t('ID'),
            'moodle_courseid' => AmosMoodle::_t('Id corso in Moodle'),
            'moodle_categoryid' => AmosMoodle::_t('Id categoria in Moodle'),
            'community_id' => AmosMoodle::_t('Id Community'),
            'enrollment_methods' => AmosMoodle::_t('Enrollment methods'),
            'course_id' => AmosMoodle::_t('Course ID into the platform'),
            'student_id' => AmosMoodle::_t('User ID to subscribe course'),
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
        return $this->hasOne(Community::class, [
            'id' => 'community_id'
        ]);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoodleCategory() {
        return $this->hasOne(MoodleCategory::class, [
            'moodle_categoryid' => 'moodle_categoryid'
        ]);
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
