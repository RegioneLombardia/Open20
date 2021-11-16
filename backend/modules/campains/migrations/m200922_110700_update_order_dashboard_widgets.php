<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * this migration remove status of 
 * 
 * Manifestazione di interesse - module partnershipprofiles
 * 
 * 
 */
class m200922_110700_update_order_dashboard_widgets extends Migration {


    /**
     * update table amos_widgets
     *
     * @return void
     */
    public function safeUp() {

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "10"], 
            // where clause
            [ 
                'classname' => "open20\amos\community\widgets\icons\WidgetIconCommunityDashboard",
                'type' => 'ICON',
                'module' => 'community'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "20"], 
            // where clause
            [ 
                'classname' => "open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard",
                'type' => 'ICON',
                'module' => 'discussioni'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "30"], 
            // where clause
            [ 
                'classname' => "open20\amos\news\widgets\icons\WidgetIconNewsDashboard",
                'type' => 'ICON',
                'module' => 'news'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "40"], 
            // where clause
            [ 
                'classname' => "amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard",
                'type' => 'ICON',
                'module' => 'sitemanagement'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "50"], 
            // where clause
            [ 
                'classname' => "amos\cmsbridge\widgets\icons\WidgetIconCmsDashboard",
                'type' => 'ICON',
                'module' => 'cmsbridge'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "60"], 
            // where clause
            [ 
                'classname' => "open20\amos\dashboard\widgets\icons\WidgetIconManagement",
                'type' => 'ICON',
                'module' => 'dashboard'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "70"], 
            // where clause
            [ 
                'classname' => "backend\modules\campains\widgets\icons\WidgetCampains",
                'type' => 'ICON',
                'module' => 'campains'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "75"], 
            // where clause
            [ 
                'classname' => "backend\modules\landings\widgets\icons\WidgetIconPreferenceLandings",
                'type' => 'ICON',
                'module' => 'landings'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "80"], 
            // where clause
            [ 
                'classname' => "backend\modules\operators\widgets\icons\WidgetOperators",
                'type' => 'ICON',
                'module' => 'operators'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "90"], 
            // where clause
            [ 
                'classname' => "backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser",
                'type' => 'ICON',
                'module' => 'registeredusers'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "100"], 
            // where clause
            [ 
                'classname' => "amos\newsletter\widgets\icons\WidgetIconNewsletterDashboard",
                'type' => 'ICON',
                'module' => 'newsletter'
            ]
        );

        $this->update(
            // table
            'amos_widgets', 
            // update columns with
            ['default_order' => "110"], 
            // where clause
            [ 
                'classname' => "backend\modules\thematics\widgets\icons\WidgetThematic",
                'type' => 'ICON',
                'module' => 'thematics'
            ]
        );
 
    }




    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {

    }
}


