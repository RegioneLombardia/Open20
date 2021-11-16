<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace open20\amos\events\utility;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * Class MultipleModel
 * @package open20\amos\events\utility
 */
class MultipleModel extends \yii\base\Model
{

    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [], $forceSetAttributes = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $modelClass = new $modelClass;
                    foreach ($forceSetAttributes as $key => $value) {
                        $modelClass->$key = $value;
                    }
                    $models[] = $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

}
