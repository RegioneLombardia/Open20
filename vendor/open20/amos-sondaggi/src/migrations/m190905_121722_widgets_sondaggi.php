<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190227_143522_create_sondaggi_model_content
 */
class m190905_121722_widgets_sondaggi extends Migration
{
    const TABLE = '{{%sondaggi}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiAll',
                'module' => 'sondaggi'
            ]);
        $this->update('amos_widgets', ['child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiOwnInterest',
                'module' => 'sondaggi'
            ]);
        $this->update('amos_widgets', ['child_of' => null],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi',
                'module' => 'sondaggi'
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->update('amos_widgets', ['child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi'],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiAll',
                'module' => 'sondaggi'
            ]);
        $this->update('amos_widgets', ['child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi'],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiOwnInterest',
                'module' => 'sondaggi'
            ]);
        $this->update('amos_widgets', ['child_of' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral'],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi',
                'module' => 'sondaggi'
            ]);

    }

}
