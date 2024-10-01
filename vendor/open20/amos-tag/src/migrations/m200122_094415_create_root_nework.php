<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m200122_094415_create_root_nework extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $roleRootTag1 = new \open20\amos\tag\models\Tag();
        $roleRootTag1->nome = "Network";
        $roleRootTag1->codice = 'root_networks';
        $roleRootTag1->makeRoot();
        $roleRootTag1->save(false);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        \open20\amos\tag\models\Tag::deleteAll([
            'codice' => 'root_networks'
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}
