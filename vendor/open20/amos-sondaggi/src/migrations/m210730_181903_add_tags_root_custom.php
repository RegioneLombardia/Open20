<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    cruscotto-lavoro\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\tag\models\Tag;
use yii\db\Migration;

/**
 * Class m210730_181903_add_tags_root_custom
 */
class m210730_181903_add_tags_root_custom extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $roleRootTag1 = new Tag();
        $roleRootTag1->nome = "Pools tag custom";
//        $roleRootTag1->limit_selected_tag = 3;
        $roleRootTag1->codice = \open20\amos\sondaggi\models\Sondaggi::ROOT_TAG_CUSTOM_POLLS;
        $roleRootTag1->makeRoot();
        $roleRootTag1->save(false);


        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180904_084129_add_cl_roles_tags cannot be reverted.\n";

        return true;
    }
}
