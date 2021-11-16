<?php

namespace app\modules\uikit\blocks;

use app\modules\uikit\Module;
use luya\cms\frontend\blockgroups\MediaGroup;

class AccordionAttachmentsBlock extends \app\modules\uikit\BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    protected $component = "accordionattachments";

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return MediaGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Module::t('accordionattachments');
    }

    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'attach_file';
    }

    /**
     * @inheritdoc
     */
    public function admin()
    {
        if(count($this->getVarValue('items', []))) {
            return $this->frontend();
        } else {
            return '<div><span class="block__empty-text">' . Module::t('no_content') . '</span></div>';
        }
    }
}