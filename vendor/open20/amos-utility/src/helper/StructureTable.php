<?php
namespace open20\amos\utility\helper;
use open20\amos\utility\models\AuthItemsTreeViewTemp;
use yii\db\IntegrityException;
use yii\rbac\Item;
use Yii;

/**
 * Created by PhpStorm.
 * User: michele.zucchini
 * Date: 03/05/2017
 * Time: 10:10
 * @property  myid
 */
class StructureTable
{
    private $myid;


    public function initializeStructure($childrens)
    {
        $listElements = [];
        /** @var Item $child */
        foreach ($childrens as $child) {
            $id = $this->getMyId();
            $ruolo['id'] = $id;
            $ruolo['parent'] = null;
            $ruolo['text'] = $child->name;
            $ruolo['nodes'] = $this->createStructure($child, $listElements['nodes'], $id);
            $listElements[] = $ruolo;
        }

//        pr($listElements[3]); die;

        AuthItemsTreeViewTemp::deleteAll();
        foreach ($listElements as $element) {
            $this->readStructure($element);
        }
    }

    /**
     * return an incremental ID
     * @return int
     */
    private function getMyId()
    {
        $this->myid++;
        return $this->myid;
    }

    /**
     * Recursive funcrion uset for create an array structure of the tree
     *
     * @param $root parent of the structure
     * @param $array array to change create
     * @param $parent
     * @return mixed
     */
    private function createStructure($root, &$array, $parent)
    {
        $childList = Yii::$app->authManager->getChildren($root->name);
        /** @var Item $child */
        $i = 0;
        foreach ($childList as $child) {
            $id = $this->getMyId();
            $array[$i]['text'] = $child->name;
            $array[$i]['id'] = $id;
            $array[$i]['parent'] = $parent;
            if (Yii::$app->authManager->getChildren($child->name)) {
                $array[$i]['nodes'] = $this->createStructure($child, $array[$i]['nodes'], $id);
            }
            $i++;
        }
        return $array;
    }

    private function readStructure($element)
    {
        $newItem = new AuthItemsTreeViewTemp;
        if (!isset($element['id'])) {
            return;
        }
        $newItem->id = $element['id'];
        $newItem->parent = $element['parent'];
        $newItem->item = $element['text'];
        try {
            $newItem->save(false);
        }catch (IntegrityException $e) {
            return;
        }
        if (isset($element['nodes']) && is_array($element['nodes'])) {
            foreach ($element['nodes'] as $el) {
                $this->readStructure($el);
            }
        }
    }

}