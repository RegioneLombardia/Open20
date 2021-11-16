<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\news\models\News;
use yii\db\Migration;

/**
 * Class m170213_083749_add_news_workflow_metadata
 */
class m170213_083749_add_news_workflow_metadata extends Migration
{
    /**
     * The table name
     */
    const TABLE_WORKFLOW_METADATA = '{{%sw_metadata}}';

    /**
     * @var array $fieldsToCheck Array of fields to check if a configuration already exists before adding it.
     */
    private $fieldsToCheck = ['workflow_id', 'status_id', '`key`'];

    /**
     * @var array $newConfs Array of all new configurations ready to insert in the database.
     */
    private $newConfs;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->setNewConfigurations();
        return $this->addConfs();
    }

    /**
     * Method to set all new configurations in the specific array.
     */
    private function setNewConfigurations()
    {
        $this->newConfs = [
            // "Modifica in corso" status
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'BOZZA',
                '`key`' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'BOZZA',
                '`key`' => 'description',
                'value' => 'Notizia in modifica'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'BOZZA',
                '`key`' => 'label',
                'value' => 'Modifica in corso'
            ],

            // "Da validare" status
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'DAVALIDARE',
                '`key`' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'DAVALIDARE',
                '`key`' => 'description',
                'value' => 'Sottopone a validazione la notizia'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'DAVALIDARE',
                '`key`' => 'label',
                'value' => 'Da validare'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'DAVALIDARE',
                '`key`' => 'message',
                'value' => 'Vuoi mettere in validazione questa notizia?'
            ],

            // "Validato" status
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'VALIDATO',
                '`key`' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'VALIDATO',
                '`key`' => 'description',
                'value' => 'La notizia verrà validata'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'VALIDATO',
                '`key`' => 'label',
                'value' => 'Validata'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'VALIDATO',
                '`key`' => 'message',
                'value' => 'Vuoi validare questa notizia?'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'VALIDATO',
                '`key`' => 'order',
                'value' => '1'
            ],

            // "Non validato" status
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'NONVALIDATO',
                '`key`' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'NONVALIDATO',
                '`key`' => 'description',
                'value' => 'La notizia non verrà validata'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'NONVALIDATO',
                '`key`' => 'label',
                'value' => 'Non validata'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'NONVALIDATO',
                '`key`' => 'message',
                'value' => 'Vuoi non validare questa notizia?'
            ],
            [
                'workflow_id' => News::NEWS_WORKFLOW,
                'status_id' => 'NONVALIDATO',
                '`key`' => 'order',
                'value' => '2'
            ]
        ];
    }

    /**
     * This method adds all configurations set in the global specific array. Checks for each configuration and creates
     * if it does not exist, otherwise it moves on.
     *
     * @return  boolean
     */
    private function addConfs()
    {
        foreach ($this->newConfs as $newConf) {
            $this->createConf($newConf);
        }
        return true;
    }

    /**
     * This method create single configuration. Befor this, it checks if the configuration exists.
     *
     * @param array $newConf Key => value array that contains the data to insert inthe table.
     */
    private function createConf($newConf)
    {
        $confName = $this->composeConsoleConfName($newConf);
        $msgPrefix = "Metadata $confName per il modulo news ";

        if ($this->checkConfExist($newConf)) {
            echo $msgPrefix . "esistente. Skippo...\n";
        } else {
            $this->insert(self::TABLE_WORKFLOW_METADATA, $newConf);
            echo $msgPrefix . "creata.\n";
        }
    }

    /**
     * Return the configuration name to be displayed in the printed message.
     *
     * @param array $newConf Key => value array that contains the data to insert inthe table.
     *
     * @return  string
     */
    private function composeConsoleConfName($newConf)
    {
        $confName = '';
        foreach ($this->fieldsToCheck as $fieldName) {
            if (strlen($confName) > 0) {
                $confName .= ' - ';
            }
            $confName .= $newConf[$fieldName];
        }
        return $confName;
    }

    /**
     * Method that verifies the existence of the configuration.
     *
     * @param   array $fieldsValues Configuration values to checks
     *
     * @return bool
     */
    private function checkConfExist($fieldsValues)
    {
        $whereCondition = "";
        foreach ($this->fieldsToCheck as $fieldName) {
            $whereCondition .= ((strlen($whereCondition) > 0) ? " AND " : "") . $fieldName . " LIKE '" . $fieldsValues[$fieldName] . "'";
        }
        $sql = "SELECT COUNT(*) FROM " . self::TABLE_WORKFLOW_METADATA . " WHERE " . $whereCondition;

        $cmd = $this->db->createCommand();
        $cmd->setSql($sql);
        $valuesCount = $cmd->queryScalar();
        return ($valuesCount > 0);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->setNewConfigurations();
        return $this->removeConfs();
    }

    /**
     * This method removes all of the configurations previously added.
     *
     * @return  boolean
     */
    private function removeConfs()
    {
        foreach ($this->newConfs as $newCompleteConf) {
            $where = "";
            foreach ($this->fieldsToCheck as $fieldName) {
                $where .= ((strlen($where) > 0) ? " AND " : "") . $fieldName . " LIKE '" . $newCompleteConf[$fieldName] . "'";
            }
            $this->delete(self::TABLE_WORKFLOW_METADATA, $where);
        }

        return true;
    }
}
