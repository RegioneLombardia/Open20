<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Description of m190123_171252_add_columns_to_event_for_publication publication
 *
 */
class m190123_171252_add_columns_to_event_for_publication extends AmosMigrationTableCreation {


    public function up() {
        $this->addColumn('event', 'primo_piano', "TINYINT(1) DEFAULT '0' COMMENT 'Primo piano'");
        $this->addColumn('event', 'in_evidenza', "TINYINT(1) DEFAULT '0' COMMENT 'In evidenza'");
    }

    public function down() {
        $this->dropColumn('event', 'primo_piano');
        $this->dropColumn('event', 'in_evidenza');
    }

}
