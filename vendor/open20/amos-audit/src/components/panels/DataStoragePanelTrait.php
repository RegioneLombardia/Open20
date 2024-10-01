<?php

namespace open20\amos\audit\components\panels;

/**
 * DataStoragePanelTrait
 * @package open20\amos\audit\components\panels
 */
trait DataStoragePanelTrait
{
    use PanelTrait;

    /**
     * @inheritdoc
     */
    public function hasEntryData($entry)
    {
        $data = $entry->data;
        return isset($data[$this->id]);
    }
}