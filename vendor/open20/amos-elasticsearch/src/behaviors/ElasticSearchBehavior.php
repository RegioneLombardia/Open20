<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\behaviors;

use Exception;
use open20\elasticsearch\models\ElasticIndex;
use open20\elasticsearch\Module;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\log\Logger;

class ElasticSearchBehavior extends Behavior {

    /**
     * @inheritdoc
     */
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'createIndex',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateIndex',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteIndex',
        ];
    }

    /**
     * 
     */
    public function createIndex() {
        try {
            $index = new ElasticIndex(['model' => $this->owner]);
            $index->save();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * 
     */
    public function deleteIndex() {
        try {
            $index = new ElasticIndex(['model' => $this->owner]);
            $index->delete();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * 
     */
    public function updateIndex() {
        try {
            $module = Module::instance();
            $transformer = $module->transformerManager->getTrasnformerManager(get_class($this->owner));
            $mtoe = $transformer->getModelToElasticTransformer();
            $mtoe->setModel($this->owner);
            if ($this->owner->hasAttribute('deleted_at') && !empty($this->owner->deleted_at) || ! $mtoe->canSaveIndex()) {
                $this->deleteIndex();
            } else {
                $index = new ElasticIndex(['model' => $this->owner]);
                $index->save();
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
    }

    public function findIndex($id) {
        
    }

    /**
     * 
     * @return array
     */
    public function toElasticArray() {
        $values = [];
        $module = Module::instance();
        $transformer = $module->transformerManager->getTrasnformerManager(get_class($this->owner));
        if (!is_null($transformer)) {
            $values = $transformer->modelToElastic($this->owner);
        }

        return $values;
    }

    /**
     * 
     * @return boolean
     */
    public function canSaveIndex() {
        $ret = true;
        $module = Module::instance();
        $transformer = $module->transformerManager->getTrasnformerManager(get_class($this->owner));

        if (!is_null($transformer)) {
            $ret = $transformer->canSaveIndex($this->owner);
        }
        return $ret;
    }

}
