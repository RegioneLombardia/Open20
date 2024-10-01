<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m211109_104613_fix_widgets_for_community extends Migration
{
    const TABLE = "amos_widgets";
    const MODULE_NAME = 'community';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
         $this->update(self::TABLE, ['status' => 0,], ['classname' => 'open20\amos\projectmanagement\widgets\icons\WidgetIconprojects','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['status' => 0,], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsCmsUltimeNews',], ['classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsCmsUltimeDiscussioni',], ['classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti',], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->update(self::TABLE, ['status' => 1,], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
        $this->update(self::TABLE, ['status' => 1,], ['classname' => 'open20\amos\projectmanagement\widgets\icons\WidgetIconprojects','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews',], ['classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsCmsUltimeNews','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni',], ['classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsCmsUltimeDiscussioni','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
         $this->update(self::TABLE, ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti',], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti','module'=>self::MODULE_NAME, 'deleted_at'=>null ]);
    }
}
