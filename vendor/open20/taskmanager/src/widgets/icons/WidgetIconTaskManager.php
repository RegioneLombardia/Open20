<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager
 * @category   CategoryName
 */

namespace open20\amos\taskmanager\widgets\icons;

use open20\amos\core\widget\WidgetIcon;

use open20\amos\taskmanager\models\TaskManagerJob;
use open20\amos\taskmanager\TaskManager;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconTaskManager
 * @package open20\amos\taskmanager\widgets\icons
 */
class WidgetIconTaskManager extends WidgetIcon
{

		/**
		 * @inheritdoc
		 */
		public function init()
		{
				parent::init();

				$this->setLabel(TaskManager::tHtml('amostaskmanager', 'running_commands'));
				$this->setDescription(TaskManager::t('amostaskmanager', 'running_commands_description'));

				$this->setIcon('gears');
				$this->setUrl(['/taskmanager']);
				$this->setCode('TASKMANAGER');
				$this->setModuleName('taskmanager');
				$this->setNamespace(__CLASS__);

				$this->setClassSpan(
						ArrayHelper::merge(
								$this->getClassSpan(),
								[
										'bk-backgroundIcon',
										'color-primary'
								]
						)
				);

				if ($this->disableBulletCounters == false) {
						$counter = TaskManagerJob::getBulletCount();

						$this->setBulletCount($counter);
				}
		}

		/**
		 * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
		 *
		 * @return array
		 */
		public function getOptions()
		{
				return ArrayHelper::merge(
								parent::getOptions(),
								['children' => []]
				);
		}

}
