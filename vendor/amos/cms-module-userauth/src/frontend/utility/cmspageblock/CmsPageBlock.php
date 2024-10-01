<?php

namespace amos\userauth\frontend\utility\cmspageblock;

use Exception;
use luya\cms\models\Block;
use luya\cms\models\NavItemPageBlockItem;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\log\Logger;

abstract class CmsPageBlock extends NavItemPageBlockItem
{

    /**
     *
     * @param array $values
     * @return array
     */
    protected function flatArray(array &$values)
    {
        foreach ($values as $keys => $value) {
            if (is_array($value)) {
                $this->flatArray($value);
                $values = ArrayHelper::merge($values, $value);
                unset($values[$keys]);
            }
        }
    }

    /**
     *
     * @param string $name
     * @return Block
     */
    protected static function findBlock(string $name): Block
    {
        $block = null;

        try {
            $block = Block::findOne(['class' => $name]);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $block;
    }

    public abstract static function findBlocks($nav_item_page_id);

    /**
     *
     * @return array
     */
    public function getCfgValues()
    {
        $values = Json::decode($this->json_config_values);
        return $values;
    }
}