<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use yii\db\Migration;

class m210628_093036_add_widgets_subdashboard_community  extends Migration
{
    public function safeUp()
    {
        
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\chat\widgets\graphics\WidgetGraphicChatAssistance',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        
        $this->insert('amos_widgets', [
            'classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsCmsUltimeNews',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->insert('amos_widgets', [
            'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->insert('amos_widgets', [
            'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsCmsUltimeDiscussioni',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
    }
    
    public function safeDown()
    {
       $this->insert('amos_widgets', [
            'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->insert('amos_widgets', [
            'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->insert('amos_widgets', [
            'classname' => '	open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->insert('amos_widgets', [
            'classname' => '	open20\amos\chat\widgets\graphics\WidgetGraphicChatAssistance',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsCmsUltimeNews',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
        $this->delete('amos_widgets', [
            'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsCmsUltimeDiscussioni',
            'type' => 'GRAPHIC',
            'module' => 'community',
            'status' => 1,
            'dashboard_visible' => 0,
            'sub_dashboard' => 1,
        ]);
    }
}
