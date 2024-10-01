<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

class m170612_000001_bypass_cwhconfiguration extends \yii\db\Migration
{
    public function up()
    {
        $sql = '
		INSERT INTO `cwh_config_contents` (`id`, `classname`, `label`, `status_attribute`, `status_value`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
		(1,	\'open20\\\amos\\\events\\\models\\\Event\',	\'Event\',	\'status\',	\'EventWorkflow/DRAFT\',	\'2017-06-12 09:57:47\',	\'2017-06-12 09:57:47\',	NULL,	1,	1,	NULL),
		(2,	\'open20\\\amos\\\projectmanagement\\\models\\\Projects\',	\'Projects\',	\'status\',	\'ProjectsWorkflow/DRAFT\',	\'2017-06-12 09:58:28\',	\'2017-06-12 09:58:28\',	NULL,	1,	1,	NULL),
		(3,	\'open20\\\amos\\\news\\\models\\\News\',	\'News\',	\'status\',	\'NewsWorkflow/BOZZA\',	\'2017-06-12 09:58:40\',	\'2017-06-12 09:58:40\',	NULL,	1,	1,	NULL),
		(4,	\'open20\\\amos\\\discussioni\\\models\\\DiscussioniTopic\',	\'DiscussioniTopic\',	\'status\',	\'DiscussioniTopicWorkflow/BOZZA\',	\'2017-06-12 09:58:53\',	\'2017-06-12 09:58:53\',	NULL,	1,	1,	NULL),
		(5,	\'open20\\\amos\\\documenti\\\models\\\Documenti\',	\'Documenti\',	\'status\',	\'DocumentiWorkflow/BOZZA\',	\'2017-06-12 09:59:09\',	\'2017-06-12 09:59:09\',	NULL,	1,	1,	NULL);
        ';
        $this->execute($sql);

    }

    public function down()
    {


    }
}

