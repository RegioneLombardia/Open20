<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\news\models\News;
use yii\db\Migration;

/**
 * Class m170613_094755_update_news_add_commentable_field
 */
class m170613_094755_update_news_add_commentable_field extends Migration
{
    private $tablename;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tablename = News::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            $this->addColumn($this->tablename, 'comments_enabled', $this->boolean()->defaultValue(0)->after('status'));
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Error while add column 'comments_enabled' to " . $this->tablename . " table");
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            $this->dropColumn($this->tablename, 'comments_enabled');
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Error while drop column 'comments_enabled' from " . $this->tablename . " table");
            return false;
        }
        return true;
    }
}
