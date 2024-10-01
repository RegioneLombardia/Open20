<?php

namespace app\modules\cms\admin\apis;

use Yii;
use luya\admin\models\Property;
use app\modules\cms\components\AdminUser;

/**
 * Common Admin API Tasks.
 *
 * Delivers default values for the specifing table. It means it does not return a key numeric array,
 * it does only return 1 assoc array wich reperents the default row.
 *
 * @since 1.0.0
 */
class CommonController extends \luya\admin\apis\CommonController
{     
    /**
     * Get all available administration regisetered properties.
     *
     * @return array Get all properties.
     */
    public function actionDataProperties()
    {
       
        $isAdmin = AdminUser::isAdmin();
        
        $data = [];
        foreach (Property::find()->all() as $item) {
            
            if(!$isAdmin){
                if($item->module_name == 'cmsseo' || $item->var_name == 'bulletCounts')
                    continue;
            }
            
            $object = Property::getObject($item->class_name);
            $data[] = [
                'id' => $item->id,
                'var_name' => $object->varName(),
                'option_json' => $object->options(),
                'label' => $object->label(),
                'type' => $object->type(),
                'default_value' => $object->defaultValue(),
                'help' => $object->help(),
                'i18n' => $object->i18n,
            ];
        }
        
        return $data;
    }
        
}
