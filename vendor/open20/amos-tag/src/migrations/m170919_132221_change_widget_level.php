<?php

use yii\db\Migration;
use open20\amos\dashboard\models\AmosWidgets;

class m170919_132221_change_widget_level extends Migration
{
    const MODULE_NAME = 'tag';

    /**
     * @return bool
     */
    public function safeUp()
    {
        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTagManager::className())) {

            $this->update(AmosWidgets::tableName(),
            [
             'dashboard_visible' => 1,
              'child_of' => ''
            ],[
                'classname' => \open20\amos\tag\widgets\icons\WidgetIconTagManager::className()
                ]);
        }
        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTag::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 0,
                    'status' => AmosWidgets::STATUS_DISABLED,
                ],[
                    'classname' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
                ]);
        }
        return true;
    }


    /**
     * @param $classname
     * @return mixed
     */
    private function checkWidgetExist($classname)
    {

        return AmosWidgets::find()
            ->andWhere([
                'classname' => $classname
            ])->count();
    }

    /**
     * @return bool
     */
    public function safeDown()
    {

        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTagManager::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 0,
                    'child_of' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
                ],[
                    'classname' => \open20\amos\tag\widgets\icons\WidgetIconTagManager::className()
                ]);
        }
        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTag::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 1,
                    'status' => AmosWidgets::STATUS_ENABLED,
                ],[
                    'classname' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
                ]);
        }

        return true;
    }
}
