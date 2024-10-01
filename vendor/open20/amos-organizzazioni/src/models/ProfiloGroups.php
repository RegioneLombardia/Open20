<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models;

use open20\amos\core\interfaces\BaseContentModelInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\organizzazioni\i18n\grammar\ProfiloGroupsGrammar;
use open20\amos\organizzazioni\Module;
use yii\db\ActiveQuery;

/**
 * Class ProfiloGroups
 * This is the model class for table "profilo_groups".
 * @package open20\amos\organizzazioni\models
 */
class ProfiloGroups extends \open20\amos\organizzazioni\models\base\ProfiloGroups implements BaseContentModelInterface, ModelLabelsInterface
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return Module::getModuleName();
    }
    
    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'profilo-groups';
    }
    
    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->name;
    }
    
    /**
     * @inheritDoc
     */
    public function getShortDescription()
    {
        return $this->__shortText($this->description, 100);
    }
    
    /**
     * @inheritDoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;
        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }
    
    /**
     * @inheritDoc
     */
    public function getGrammar()
    {
        return new ProfiloGroupsGrammar();
    }
    
    /**
     * @param int|null $id
     * @return ActiveQuery
     */
    public function getAssociationTargetQuery($id = null)
    {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        /** @var Profilo $profiloModel */
        $profiloModel = $this->organizzazioniModule->createModel('Profilo');
        $alreadyAssociatedIds = $this->getGroupProfilos()->select(['id'])->column();
        
        /** @var ActiveQuery $query */
        $query = $profiloModel::find()
            ->andFilterWhere(['not in', $profiloModel::tableName() . '.id', $alreadyAssociatedIds])
            ->orderBy([$profiloModel::tableName() . '.name' => SORT_ASC]);
    
        if ($this->organizzazioniModule->enableWorkflow) {
            $query->andWhere([$profiloModel::tableName() . '.status' => Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED]);
        }
        
        return $query;
    }
}
