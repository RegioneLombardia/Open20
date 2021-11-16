<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171130_123848_moodle_course_permissions
 */
class m171130_123848_moodle_course_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
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
