<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets
 * @category   CategoryName
 */

namespace open20\amos\events\widgets;

use open20\amos\core\forms\ShowUserTagsWidget;

/**
 * Class EventsShowUserTagsWidget
 * @package open20\amos\events\widgets
 */
class EventsShowUserTagsWidget extends ShowUserTagsWidget
{
    /**
     * @var array $tagSessionIds Array of tag values selected by user in publication step
     */
    private $tagSessionIds = [];

    /**
     * @inheritdoc
     */
    protected function getArrayTagsId()
    {
        $ret = [];
        if (!empty($this->tagSessionIds)) {
            foreach ($this->tagSessionIds as $rootId => $treeTagIdsStr) {
                if (!strlen($treeTagIdsStr)) {
                    continue;
                }
                
                $treeTagIds = explode(',', $treeTagIdsStr);
                foreach ($treeTagIds as $tagId) {
                    $ret[] = $tagId;
                }
            }
        }
        
        return $ret;
    }

    /**
     * @inheritdoc
     */
    protected function getAllTagsRoots()
    {
        $this->userTagList = $this->getArrayTagsId();
        $ret = [];
        if (!empty($this->tagSessionIds)) {
            foreach ($this->tagSessionIds as $rootId => $treeTagIdsStr) {
                if (!strlen($treeTagIdsStr)) {
                    continue;
                }
                
                $root = $this->getTagById($rootId);
                $ret[$rootId]['el'] = $root->nome;
                $ret[$rootId]['level'] = $root->lvl;
            }
        }
        
        return $ret;
    }

    /**
     * @return array
     */
    public function getTagSessionIds()
    {
        return $this->tagSessionIds;
    }

    /**
     * @param array $tagSessionIds
     */
    public function setTagSessionIds($tagSessionIds)
    {
        $this->tagSessionIds = $tagSessionIds;
    }
}
