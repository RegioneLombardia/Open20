<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\taskmanager\rules\ReadOwnTaskManagerJobRule;
use open20\amos\taskmanager\rules\DeleteOwnTaskManagerJobRule;
use yii\rbac\Permission;


/**
* Class m220831_122149_permissions_taskmanagerjobs*/
class m220831_122149_permissions_taskmanagerjobs extends AmosMigrationPermissions
{
	/**
	* @inheritdoc
	*/
	protected function setRBACConfigurations()
	{
		$prefixStr = '';

		return [
			[
				'name' =>  'TASKMANAGER_MANAGER',
				'type' => Permission::TYPE_ROLE,
				'description' => 'Can manage task jobs',
				'parent' => ['ADMIN']
			],
			[
				'name' =>  'TASKMANAGER_USER',
				'type' => Permission::TYPE_ROLE,
				'description' => 'Can access task jobs',
				'parent' => ['ADMIN','BASIC_USER']
			],
			[
				'name' =>  'TASKMANAGER_CREATE',
				'type' => Permission::TYPE_PERMISSION,
				'description' => 'Can create task jobs',
				'parent' => ['TASKMANAGER_MANAGER','TASKMANAGER_USER']
			],
			[
				'name' =>  'TASKMANAGER_READ',
				'type' => Permission::TYPE_PERMISSION,
				'description' => 'Can read task jobs',
				'ruleName' => ReadOwnTaskManagerJobRule::className(),
				'parent' => ['TASKMANAGER_MANAGER','TASKMANAGER_USER']
			],
			[
				'name' =>  'TASKMANAGER_DELETE',
				'type' => Permission::TYPE_PERMISSION,
				'description' => 'Can delete task jobs',
				'ruleName' => DeleteOwnTaskManagerJobRule::className(),
				'parent' => ['TASKMANAGER_MANAGER','TASKMANAGER_USER']
			],
		];
	}
}
