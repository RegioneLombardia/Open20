<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\models;

use open20\amos\cwh\models\CwhTagInterestMm;
use open20\amos\tag\models\search\TagQuery;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "tag".
 */
class Tag extends \open20\amos\tag\models\base\BaseTag
{
    /**
     * 
     * @return TagQuery
     */
    public static function find()
    {
        return new TagQuery(get_called_class());
    }

    /**
     * 
     * @return type
     */
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'depthAttribute' => 'lvl',
                'treeAttribute' => 'root',
            ],
        ];
    }

    /**
     * 
     * @return type
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * 
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (isset($_POST['Tag']) && isset($_POST['Tag']['id'])) {
            $tagId = $this->id;

            // set of all occurence of $tagId
            if (isset($_POST['ModelsRoles'])) {
                // delete all occurence of $tagId
                TagModelsAuthItemsMm::deleteAll(['tag_id' => $tagId]);
                $modelsRoles = $_POST['ModelsRoles'];
                foreach ($modelsRoles as $moduleName => $rolesArray) {
                    foreach ($rolesArray as $key => $roleName) {
                        $modelTagModels = new TagModelsAuthItemsMm();
                        $modelTagModels->tag_id = $tagId;
                        $modelTagModels->classname = $moduleName;
                        $modelTagModels->auth_item = $roleName;
                        // TODO Su database sono da aggiungere i campi
                        // per le behaviors, e qua è togliere il detach!
                        $modelTagModels->detachBehaviors();
                        $modelTagModels->save(false);
                    }
                }
            } else {
                TagModelsAuthItemsMm::deleteAll(['tag_id' => $tagId]);
            }
            
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh)) {
                if (isset($_POST['CwhTagInterestMm'])) {
                    // delete all occurence of $tagId
                    CwhTagInterestMm::deleteAll(['tag_id' => $tagId]);
                    $CwhTagInterestMm = $_POST['CwhTagInterestMm'];
                    foreach ($CwhTagInterestMm as $moduleName => $rolesArray) {
                        foreach ($rolesArray as $key => $roleName) {
                            $modelTagModels = new \open20\amos\cwh\models\CwhTagInterestMm();
                            $modelTagModels->tag_id = $tagId;
                            $modelTagModels->classname = $moduleName;
                            $modelTagModels->auth_item = $roleName;
                            $modelTagModels->detachBehaviors();
                            $modelTagModels->save(false);
                        }
                    }
                } else {
                    CwhTagInterestMm::deleteAll(['tag_id' => $tagId]);
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array - the list of distinct classname of the models
     */
    public function getClassname()
    {
        $ret = [];
        $listaElementi = TagModelsAuthItemsMm::findAll(['tag_id' => $this->id]);
        foreach ($listaElementi as $elemento) {
            $ret[] = $elemento->classname;
        }

        return $ret;
    }

    /**
     *
     * @return array
     */
    public function getRoles()
    {
        $ret = [];
        $listaElementi = TagModelsAuthItemsMm::findAll(['tag_id' => $this->id]);
        foreach ($listaElementi as $elemento) {
            $ret[] = $elemento->auth_item;
        }

        return $ret;
    }

    /**
     * 
     * @return type
     */
    public function getModelsRoles()
    {
        $ret = [];
        $listaElementi = TagModelsAuthItemsMm::findAll(['tag_id' => $this->id]);
        foreach ($listaElementi as $elemento) {
            $ret[] = $elemento->getAttributes();
        }

        return $ret;
    }

    /**
     * 
     * @param type $id
     * @param type $classname
     * @param type $role
     * @return boolean
     */
    public function isModelRole($id, $classname, $role)
    {
        $listaElementi = TagModelsAuthItemsMm::findAll(['tag_id' => $this->id]);
        foreach ($listaElementi as $elemento) {
            $valori = $elemento->getAttributes();
            if (
                $valori['tag_id'] == $id
                && $valori['classname'] == $classname
                && $valori['auth_item'] == $role
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * 
     * @param type $classname
     * @return type
     */
    public function getAssignedRolesByClassname($classname)
    {
        $ret = [];
        $listaElementi = TagModelsAuthItemsMm::findAll([
            'tag_id' => $this->id, 'classname' => $classname
        ]);
        foreach ($listaElementi as $elemento) {
            $ret[] = $elemento->auth_item;
        }

        return $ret;
    }

    /**
     * 
     * @param type $classname
     * @return type
     */
    public function getAssignedInterestByClassname($classname)
    {
        $ret = [];
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $listaElementi = CwhTagInterestMm::findAll([
                'tag_id' => $this->id,
                'classname' => $classname
            ]);
            foreach ($listaElementi as $elemento) {
                $ret[] = $elemento->auth_item;
            }
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $pathParents = $this->parents()->orderBy('lvl ASC')->all();

        $parentsPath = [];
        foreach ($pathParents as $padre) {
            //esclude la root in quanto è già indicata
            if ($padre->lvl != 0) {
                $parentsPath[] = $padre->nome;
            }
        }
        $path = implode(" / ", $parentsPath);

        return $path;
    }

    /**
     * @return mixed
     */
    public function getTagRoot()
    {
        $root = Tag::find()->andWhere(['id' => $this->root])->one();

        return $root;
    }

    /**
     * get Name for root tag
     *
     * @return string
     */
    public function getRootName(){
		$root = $this->getTagRoot();
		return $root->nome;
	}
	
	/**
     * get Name for Father and Son tag 
     *
     * @return string
     */
	public function getFather(){		
		$lvl = $this->lvl - 1;
		$father = null;
		if($lvl > 0){
			$father = Tag::find()
			->andWhere(['<','lft', $this->lft])
			->andWhere(['>', 'rgt', $this->rgt])
			->andWhere(['lvl' => $lvl])
			->andWhere(['root' => $this->root])
			->andWhere(['<>', 'root', $this->id])
			->andWhere(['>', 'lvl', 0])
			->one();			
		}
		if(!empty($father)){
			return $father->nome . ' - ' . $this->nome;
		}
		return $this->nome;
	}

}
