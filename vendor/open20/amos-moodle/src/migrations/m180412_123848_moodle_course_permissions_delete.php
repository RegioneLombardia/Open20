<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180412_123848_moodle_course_permissions_delete
 */
class m180412_123848_moodle_course_permissions_delete extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'MOODLECOURSE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model MoodleCourse',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'MOODLECOURSE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model MoodleCourse',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'MOODLECOURSE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model MoodleCourse',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'MOODLECOURSE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model MoodleCourse',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}
