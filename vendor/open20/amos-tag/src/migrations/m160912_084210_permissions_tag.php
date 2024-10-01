<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

class m160912_084210_permissions_tag extends \yii\db\Migration
{

    const TABLE_PERMISSION = '{{%auth_item}}';

    public function safeUp()
    {        
        $this->insert(self::TABLE_PERMISSION, [
            'name' => \open20\amos\tag\widgets\icons\WidgetIconTag::className(),
            'type' => '2',
            'description' => 'Permesso di VIEW sul widget WidgetIconTag'
        ]);
        $this->insert(self::TABLE_PERMISSION, [
            'name' => \open20\amos\tag\widgets\icons\WidgetIconTagManager::className(),
            'type' => '2',
            'description' => 'Permesso di VIEW sul widget WidgetIconTagManager'
        ]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_PERMISSION, [
            'name' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
        ]);
        echo "Eliminato il permesso: " . 'WIDGETICONTAG_VIEW';
        $this->delete(self::TABLE_PERMISSION, [
            'name' => \open20\amos\tag\widgets\icons\WidgetIconTagManager::className()
        ]);
        echo "Eliminato il permesso: " . 'WIDGETICONTAGMANAGER_VIEW';
        return true;
    }

}