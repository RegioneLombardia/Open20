<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */
namespace open20\elasticsearch\transformer;

use open20\elasticsearch\base\interfaces\ModelToElasticTransformerInterface;
use open20\elasticsearch\Module;
use yii\db\ActiveRecord;

abstract class AbstractModelToElasticTransformer extends AbstractTransformer implements ModelToElasticTransformerInterface
{

    public function transform()
    {
        return $this->model->toArray();
    }

    /**
     *
     * @param string $value
     * @return string
     */
    protected function filterString($value)
    {
        if (is_null($value)) {
            $retValue = "";
        } else {
            $retValue = urldecode(html_entity_decode(strip_tags($value)));
        }
        return $retValue;
    }

    /**
     *
     * @param ActiveRecord $model
     * @return boolean
     */
    public function canSaveIndex()
    {
        return true;
    }

    public function cwh($values)
    {
        $ret_values = $values;
        $module = Module::instance();
        if ($module->enableCwh) {
            $ret_values = $this->cwhEvaluation($values);
        }
        return $ret_values;
    }
}
