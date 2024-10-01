<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use amos\sitemanagement\models\PageContent;
use amos\sitemanagement\models\SiteManagementSection;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m180731_163840_insert_sections
 */
class m180731_163840_insert_sections extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(PageContent::tableName(), 'section_id', $this->integer()->notNull()->after('id'));

        $queryContents = new Query();
        $queryContents->from(PageContent::tableName());
        $contents = $queryContents->all();

        foreach ($contents as $content) {
            $section = SiteManagementSection::findOne(['name' => $content['tag']]);
            $ok = true;
            if (is_null($section)) {
                $section = new SiteManagementSection();
                $section->name = $content['tag'];
                $ok = $section->save(false);
            }
            if ($ok) {
                try {
                    $this->update(PageContent::tableName(), ['section_id' => $section->id], ['id' => $content['id']]);
                } catch (\Exception $exception) {
                    MigrationCommon::printConsoleMessage($exception->getMessage());
                    return false;
                }
            } else {
                $this->dropColumn(PageContent::tableName(), 'section_id');
                MigrationCommon::printConsoleMessage($section->getErrors());
                return false;
            }
        }

        $this->addForeignKey('fk_site_manage_page_content_section_id1', PageContent::tableName(), 'section_id', SiteManagementSection::tableName(), 'id');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_site_manage_page_content_section_id1', PageContent::tableName());
        $this->dropColumn(PageContent::tableName(), 'section_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        return true;
    }
}
